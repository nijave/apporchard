<?php
/*
	This wraps around the Medoo library and sets the 
	relevant variables and adjusts behavior--if necessary
*/

require_once('libraries/medoo/medoo.php');

class Database {

    private static $instance = null;

    private static function setInstance() {
        if(self::$instance === null) {
            self::$instance = new medoo([
                'database_type' => 'mysql',
                'server' => getenv('OPENSHIFT_MYSQL_DB_HOST'),
                'username' => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
                'password' => getenv('OPENSHIFT_MYSQL_DB_PASSWORD'),
                'port' => getenv('OPENSHIFT_MYSQL_DB_PORT'),
                'database_name' => getenv('OPENSHIFT_GEAR_NAME'),
                'charset' => 'utf8'
            ]);
        }
    }
    
    /**
     * Changes an application object into an array for use in SQL queries
     * $app Application to change to array
     * @return SQL compatible array
     */
    private static function makeSQLArray(Application $app) {
        $app_array = array(
            'title' => $app->getTitle(),
            'developer' => $app->getDeveloper(),
            'category' => $app->getCategory(),
            'compat_apple' => $app->getCompatible("Apple"),
            'compat_android' => $app->getCompatible("Android"),
            'compat_windows' => $app->getCompatible("Windows"),
            'link_apple' => $app->getStoreLink("Apple"),
            'link_android' => $app->getStoreLink("Android"),
            'link_windows' => $app->getStoreLink("Windows"),
            'developer_link' => $app->getDeveloperLink(),
            'description' => $app->getDescription(),
            'moderation_state' => $app->getModerationState()
        );
        return $app_array;
    }
    
    public static function applicationGet($id) {
        self::setInstance();
        //TODO: gets an application based on the id
        return $id; //for DEBUGGING
    }
    
    /**
     * Inserts a new application or updates an existing one if one already exists
     * @return int ID of the application modified or inserted
     */
    public static function applicationSet(Application $app) {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        //determine if application already exists in database
        $exists = self::$instance->select("applications", ["id"], ["id"=>$app->getID()]);
        
        //check to see if application already exists in database
        if (sizeof($exists) === 0) {
            //create new application if one doesn't exist
            $id = self::$instance->insert("applications", self::makeSQLArray($app));
        } 
        else {
            //update application if it already exists
            self::$instance->update("applications", self::makeSQLArray($app), ["id" => $app->getID()]);
            $id = $app->getID();
        }
        
        //remove all existing keywords
        self::$instance->delete("keywords", ["id" => $id]);
        
        //create an array of the keywords to be inserted
        $keyword_array = array();
        foreach($app->getKeywords() as $word) {
            $keyword_array[] = array("id" => $id, "word" => $word);
        }
        
        //insert keywords into keyword table
        self::$instance->insert("keywords", $keyword_array);
        
        return $id;
    }
    
    /**
     * Returns a list of application IDs ordered by
     * the number of hits returned from the search
     * @param type $keywords array
     * @return array of applications IDs ordered by hits
     */
    public static function applicationSearch($keywords) {
        //creates database connection if one doesn't already exist
        self::setInstance();
         
        //remove whitespace and escape keywords, surround in single quotes
        for($i = 0; $i < count($keywords); $i++) {
            $keywords[$i] = "'" . (mysql_escape_string($keywords[$i])) . "'";
        }
        
        //create an array of LIKE SELECT statements for each word 
        //-- this way the query will match titles if it's only part of the title
        $title_selects = array();
        foreach($keywords as $word) {
            $title_selects[] = "SELECT id, '5' as weight FROM applications WHERE title LIKE $word";
        }
        
        //create a comma separated list of keywords
        $keyword_list = implode(',', $keywords);
        
        //generate query for search using parts from above
        $query =  "SELECT id FROM("
                    . "SELECT id, '2' as weight FROM keywords WHERE word IN ($keyword_list)"
                    . " UNION ALL "
                    . implode(" UNION ALL ", $title_selects)
                . ") AS search_results GROUP BY id ORDER BY SUM(weight) DESC;";
        
        //get array of results
        $raw_results = self::$instance->query($query)->fetchAll(PDO::FETCH_ASSOC);
        
        //cleanup array so it only returns application ids
        $results = array();
        foreach($raw_results as $result) { //cleanup MySQL results and change into an array of ints
            $results[] = $result["id"];
        }
        
        return $results;
    }
}
