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
            //'keyword' => implode(',', $app->getKeywords()), //separate keywords with a comma
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
        self::setInstance(); //creates database connection if one doesn't already exist
        
        $exists = self::$instance->select("applications", ["id"], ["id"=>$app->getID()]); //determine if application already exists in database
        
        if (sizeof($exists) === 0) { //check to see if application already exists in database
            $id = self::$instance->insert("applications", self::makeSQLArray($app));
        } //create new application if one doesn't exist
        else {
            self::$instance->update("applications", self::makeSQLArray($app), ["id" => $app->getID()]);
            $id = $app->getID();
        } //update application if it already exists
        
        self::$instance->delete("keywords", ["id" => $id]); //remove all existing keywords
        $keyword_array = array();
        foreach($app->getKeywords() as $word) { //create an array of the keywords to be inserted
            $keyword_array[] = array("id" => $id, "word" => $word);
        }
        self::$instance->insert("keywords", $keyword_array); //insert keywords into keyword table
        return $id;
    }
    
    /**
     * Returns a list of application IDs ordered by
     * the number of hits returned from the search
     * @param type $keywords array
     * @return array of applications IDs ordered by hits
     */
    public static function applicationSearch($keywords) {
        for($i = 0; $i < count($keywords); $i++) { //remove whitespace and escape keywords, surround in single quotes
            $keywords[$i] = "'" . (mysql_escape_string($keywords[$i])) . "'";
        }
        $keyword_list = implode(',', $keywords); //create a comma separated list of keywords
        $title_selects = array();
        foreach($keywords as $word) {
            $title_selects[] = "SELECT id, '5' as weight FROM applications WHERE title LIKE '$word'";
        }
        $query =  "SELECT id FROM("
                    . "SELECT id, '2' as weight FROM keywords WHERE word IN ($keyword_list)"
                    . " UNION ALL "
                    . implode(" UNION ALL ", $title_selects)
                . ") AS search_results GROUP BY id ORDER BY SUM(weight) DESC;";
        var_dump($query); //DEBUGGING
        //$results = self::$instance->query($query)->fetchAll();
        //return $results;
    }
}
