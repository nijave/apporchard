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
            'image_url' => $app->getImageURL(),
            'description' => $app->getDescription(),
            'moderation_state' => $app->getModerationState()
        );
        return $app_array;
    }
    
    /**
     * Returns an application given an ID
     * @param int $id of application to get
     * @return Application
     * @throws Exception when application corresponding to ID isn't found
     */
    public static function applicationGet($id) {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        //try to find applications given the id
        $application = self::$instance->select("applications", "*", ["id" => $id]);
        
        //check to see if any applications are found
        if(count($application) < 1) {
            //throws an Exception if no applications were found (invalid id)
            throw new Exception("Application ID not found");
        }
        else {
            //create an application object from the database information
            
            //use the first (and only) result
            $application = $application[0];
            
            $obj = new Application();
            $obj->setID($application["id"]);
            $obj->setTitle($application["title"]);
            $obj->setDeveloper($application["developer"]);
            $obj->setPrice($application["price"]);
            $obj->setCategory($application["category"]);
            
            //set links and store compatibility
            foreach($application as $field => $value) {
                $substring = substr($field, 0, 4);
                if ($substring === "link") {
                    $obj->setStoreLink(\ucfirst(substr($field, 5)), $value);
                }
                if ($substring === "comp") {
                    if($value == "1") {
                        $obj->setCompatible(\ucfirst(substr($field, 7)));
                    }
                }
            }
            
            $obj->setDeveloperLink($application["developer_link"]);
            //APPLICATION KEYWORDS ARE NEVER SET FROM DATABASE
            $obj->setImageURL($application["image_url"]);
            $obj->setDescription($application["description"]);
            $obj->setModerationState($application["moderation_state"]);
        }
        
        return $obj;
    }
    
    /**
     * Gets all the application categories from the database
     * @return string array of application categories
     */
    public static function applicationGetCategories() {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        $query = "SELECT DISTINCT category FROM applications WHERE moderation_state = 'ACTIVE'";
        
        //Submit the query and store results
        $cats = self::$instance->query($query)->fetchAll(PDO::FETCH_ASSOC);
        
        //Create an array to hold categories
        $application_categories = array();
        
        //Generate clean array from SQL results
        foreach($cats as $cat) {
            $application_categories[] = $cat["category"];
        }
        
        return $application_categories;
    }
    
    /**
     * Gets all the developers from the database with submitted apps
     * @return string array of developers
     */
    public static function applicationGetDevelopers() {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        $query = "SELECT DISTINCT developer FROM applications WHERE moderation_state = 'ACTIVE'";
        
        //Submit the query and store results
        $devs = self::$instance->query($query)->fetchAll(PDO::FETCH_ASSOC);
        
        //Create an array to hold developers
        $application_developers = array();
        
        //Generate clean array from SQL results
        foreach($devs as $dev) {
            $application_developers[] = $dev["developer"];
        }
        return $application_developers;
    }
    
    /**
     * Gets applications in a certain category
     * @param string $category
     * @return type
     */
    public static function applicationGetByCategory($category) {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        $apps = self::$instance->select("applications", ["id"], ["AND" =>["moderation_state" => "ACTIVE", "category" => $category]]);
        
        //Create an array to hold app ids
        $app_ids = array();
        
        //Generate clean array from SQL results
        foreach($apps as $app) {
            $app_ids[] = $app["id"];
        }
        return $app_ids;
    }
    
    /**
     * Returns an array of integers for the 20 most recently added applications
     * @return integer array of application ids
     */
    public static function applicationGetNewest() {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        $apps = self::$instance->select("applications", ["id"], ["moderation_state" => "ACTIVE", "ORDER" => "id DESC", "LIMIT" => 20]);
        
        //Create an array to hold app ids
        $newest_ids = array();
        
        //Generate clean array from SQL results
        foreach($apps as $app) {
            $newest_ids[] = $app["id"];
        }
        return $newest_ids;
    }
    
    
    /**
     * Gets pending apps ordered by oldest to newest
     * @return integer array of applications ids
     */
    public static function applicationGetPending() {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        $apps = self::$instance->select("applications", ["id"], ["moderation_state" => "PENDING", "ORDER" => "id ASC"]);
        
        //Create an array to hold app ids
        $pending_ids = array();
        
        //Generate clean array from SQL results
        foreach($apps as $app) {
            $pending_ids[] = $app["id"];
        }
        return $pending_ids;
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
    public static function applicationSearch($keywords, $constraints = null) {
        //creates database connection if one doesn't already exist
        self::setInstance();
         
        //remove whitespace and escape keywords, surround in single quotes
        for($i = 0; $i < count($keywords); $i++) {
            $keywords[$i] = "'" . (htmlspecialchars($keywords[$i], ENT_QUOTES)) . "'";
        }
        
        //prevent SQL injection and create constraints SQL
        $constraints_query = "";
        foreach($constraints as $field => $array) {
            //ensure there's at least one constraint
            if(count($array) > 0) {
                $constraints_query .= " AND $field IN(";
                foreach($array as $key => $value) {
                    //escape and quote value
                    $array[$key] = "'".htmlspecialchars($value, ENT_QUOTES)."'";
                }
                $constraints_query .= implode(',', $array).")";
            }
        }
        print_r($constraints_query);
        //create an array of LIKE SELECT statements for each word 
        //-- this way the query will match titles if it's only part of the title
        $title_selects = array();
        foreach($keywords as $word) {
            $title_selects[] = "SELECT id, '5' as weight FROM applications WHERE"
                    . " moderation_state = 'ACTIVE'"
                    . " AND title LIKE $word";
        }
        
        //create a comma separated list of keywords
        $keyword_list = implode(',', $keywords);
        
        //generate query for search using parts from above
        $query =  "SELECT id FROM("
                    . "SELECT id, '2' as weight FROM keywords WHERE"
                    . " moderation_state = 'ACTIVE'"
                    . "AND word IN ($keyword_list)";
        
        $query .= " UNION ALL "
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
