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
            $obj->setKeywords(self::applicationGetKeywords($id), true);
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
            $pending_ids[] = $app["app_id"];
        }
        return $pending_ids;
    }
    
    /**
     * Returns the app ids for the 20 top rated applications
     * @return interger array of the 20 highest rated apps
     */
    public static function applicationGetHighestRated() {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        $apps = self::$instance->query("
            SELECT app_id FROM ratings
            GROUP BY app_id
            ORDER BY AVG(rating) DESC
            LIMIT 20;
        ");
        
        //Create an array to hold app ids
        $app_ids = array();
        
        //Generate clean array from SQL results
        foreach($apps as $app) {
            $app_ids[] = $app["id"];
        }
        return $app_ids;
    }
    
    private static function applicationGetKeywords($id) {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        $kw = self::$instance->select("keywords", ["word"], ["id" => $id]);
        
        //Create an array to hold app ids
        $keywords = array();
        
        //Generate clean array from SQL results
        foreach($kw as $word) {
            $keywords[] = $word["word"];
        }
        
        return $keywords;
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
        $constraints_query = " ";
        if($constraints !== null) {
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
        }
        
        //create an array of LIKE SELECT statements for each word 
        //-- this way the query will match titles if it's only part of the title
        $title_selects = array();
        foreach($keywords as $word) {
            $title_selects[] = "SELECT id, '5' as weight FROM applications WHERE("
                    . " moderation_state = 'ACTIVE'"
                    . " AND title LIKE $word"
                    . $constraints_query
                    . ")";
        }
        
        //create a comma separated list of keywords
        $keyword_list = implode(',', $keywords);
        
        //generate query for search using parts from above
        $query =  "SELECT id FROM("
                    . "SELECT id, weight FROM("
                        . "SELECT applications.*, keywords.word, '2' as weight FROM keywords"
                        . " JOIN applications ON (keywords.id = applications.id)"
                        . " WHERE word IN ($keyword_list)"
                        . " ) AS keyword_results"
                    . " WHERE moderation_state = 'ACTIVE'";
        $query .= $constraints_query;
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
    
    /**
     * Add a user rating for a particular application
     * @param string $email of user
     * @param int $id of application
     * @param int $rating to add
     * @return boolean whether rating was added
     */
    public static function ratingAdd($email, $id, $rating) {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        //Get user id from email address
        $user_id = self::$instance->select("Users", ["ID"], ["Email" => $email]);
        
        //Check to see if user for email address exists
        if(isset($user_id[0]["ID"])) {
            $user_id = $user_id[0]["ID"];
        }
        else {
            throw new Exception("User id for: $email not found.");
        }
        
        //Where condition for checking see if rating exists or updating
        //it if it does
        $where_query = ["AND" => ["user_id" => $user_id, "app_id" => $id]];
        
        //Search for existing rating
        $_rating = self::$instance->select("ratings", ["user_id"], $where_query);
        
        $data = [
            "user_id" => $user_id,
            "app_id" => $id,
            "rating" => $rating
        ];
        
        //Update rating if rating already exists
        if(count($_rating) > 0) {
            //Update rating in database
           $update = self::$instance->update("ratings", $data, $where_query);
        } else {
            //Add rating to database
            $insert = self::$instance->insert("ratings", $data);
        }
        
        return true; //true is added, false if already rated
    }

    /**
     * Returns the average rating for an application
     * @param int $id of application
     * @return double average rating
     */
    public static function ratingGet($id) {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        //Get average
        $avg = self::$instance->avg("ratings", "rating", ["app_id" => $id]);

        return floor($avg * 2) / 2; //returns doubles in format #.#
    }

    /**
     * Returns the number of ratings for a particular application
     * @param int $id of application
     * @return int number of ratings
     */
    public static function ratingGetCount($id) {
        //creates database connection if one doesn't already exist
        self::setInstance();
        
        $count = self::$instance->count("ratings", ["app_id" => $id]);
        
        return $count;
    }
}
