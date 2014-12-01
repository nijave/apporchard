<?php

/**
 * Class representation of each application
 */
class Application {

    private static $MODERATION_STATES = array("ACTIVE", "PENDING", "DELETED"); //possible moderation states/visibility of an application    
    private static $COMPATIBLE_PLATFORMS = array("Apple", "Android", "Windows"); //possible platforms that are supported
    private $id; //application unique identifier integer
    private $title; //string
    private $developer; //string
    private $price; //double
    private $category; //category of the application
    private $compatible_status; //boolean array, compatible with Apple
    private $link_store; //string array, store links
    private $link_developer; //link to developer website
    private $keywords; //array[string] of keywords
    private $description; //string, description of app
    private $moderation_state; //string (pending, active, deleted), current moderation state of app

    /**
     * Create a new application that doesn't exist in the database 
     * @param integer $id of existing application to load into object
     */
    public function __construct($id = null) {
        if ($id === null) {
            foreach (self::$COMPATIBLE_PLATFORMS as $platform) {
                $this->compatible_status["$platform"] = false;
            }
            $this->keywords = array();
            $this->id = -1; //ids must be positive, if valid
        } else {
            $obj = Database::applicationGet($id);
            $this->id = $obj->id;
            $this->title = $obj->title;
            $this->developer = $obj->developer;
            $this->price = $obj->price;
            $this->category = $obj->category;
            $this->compatible_status = $obj->compatible_status;
            $this->link_store = $obj->link_store;
            $this->link_developer = $obj->link_developer;
            $this->keywords = $obj->keywords;
            $this->description = $obj->description;
            $this->moderation_state = $obj->moderation_state;
        }
    }
    
    /**
     * Dumps all the information about the application as a string
     * @return string of application contents
     */
    public function __toString() {
        return print_r($this, true);
    }

    /**
     * Sets the ID of an application
     *  ** this is needed for creating an 
     *     Application object from the database
     * @param int $id
     */
    public function setID($id) {
        $this->id = $id;
    }
    
    /**
     * Sets title
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Sets developer
     * @param string $developer
     */
    public function setDeveloper($developer) {
        $this->developer = $developer;
    }

    /**
     * Sets price
     * @param double $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * Sets category
     * @param string $category
     */
    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * Sets an app to compatible or incompatible with a given platform
     * Performs checking to ensure platform is valid
     * @param string $platform representing platform
     * @param boolean $value support for platform
     */
    private function changeCompatible($platform, $value) {
        if (!in_array($platform, self::$COMPATIBLE_PLATFORMS)) {
            throw new Exception("Invalid platform provided", 1);
        } else {
            $this->compatible_status["$platform"] = $value;
        }
    }

    /**
     * Sets an application compatible with given platform
     * @param string $platform representing platform
     */
    public function setCompatible($platform) {
        $this->changeCompatible($platform, true);
    }

    /**
     * Unsets an application being compatible for a given platform
     * @param string $platform representing platform
     */
    public function unsetCompatible($platform) {
        changeCompatible($platform, false);
    }

    /**
     * Sets a store link for a given store
     * @param string $platform representing platform name
     * @param string $link URL for store
     */
    public function setStoreLink($platform, $link) {
        if (!in_array($platform, self::$COMPATIBLE_PLATFORMS)) {
            throw new Exception("Invalid platform provided", 1);
        } else {
            $this->link_store["$platform"] = $link;
        }
    }

    public function setDeveloperLink($link) {
        $this->link_developer = $link;
    }

    /**
     * Sets keywords from array or comma separated list
     * @param array[string] $keywords
     * @param boolean $isArray true if array, false if comma separated list
     */
    public function setKeywords($keywords, $isArray = false) {
        if ($isArray) {
            $this->keywords = $keywords;
        } else {
            $this->keywords = explode(',', $keywords);
        } //changes list of comma separated keywords to an array

        for ($i = 0; $i < sizeof($this->keywords); $i++) {
            $this->keywords[$i] = trim($this->keywords[$i]);
        } //removes spaces around keywords
    }

    /**
     * Sets description of the application
     * @param string $desc description
     */
    public function setDescription($desc) {
        $this->description = $desc;
    }

    /**
     * Sets the moderation state of the application
     * i.e. whether is is displayed or needs moderator approval first 
     * @param string $state (see MODERATION_STATES for available values)
     * @throws Exception when invalid state provided
     */
    public function setModerationState($state) {
        if (!in_array($state, self::$MODERATION_STATES)) {
            throw new Exception("Invalid moderation state", 1);
        } else {
            $this->moderation_state = $state;
        }
    }

    public function getID() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDeveloper() {
        return $this->developer;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getCompatible($platform) {
        if (!in_array($platform, self::$COMPATIBLE_PLATFORMS)) {
            throw new Exception("Invalid platform provided", 1);
        } else {
            if (isset($this->compatible_status["$platform"])) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Returns an array of platforms (strings) that an
     * application is compatible with
     * @return array string of compatible platforms
     */
    public function getCompatiblePlatforms() {
        $platforms = array();
        foreach ($this->compatible_status as $platform => $value) {
            if ($value === true) {
                $platforms[] = $platform;
            }
        }
        return $platforms;
    }

    /**
     * Returns the link for a particular platform store
     * @param string $platform 
     * @return string link to application entry at specified store
     * @throws Exception when invalid store provided
     */
    public function getStoreLink($platform) {
        if (!in_array($platform, self::$COMPATIBLE_PLATFORMS)) {
            throw new Exception("Invalid platform provided", 1);
        } else {
            if (isset($this->link_store["$platform"])) {
                return $this->link_store["$platform"];
            } else {
                return "";
            } //return nothing if this isn't set
        }
    }

    /**
     * Gets store links
     * @return array of store links
     */
    public function getStoreLinks() {
        return $this->link_store;
    }
    
    public function getDeveloperLink() {
        return $this->link_developer;
    }

    public function getKeywords() {
        return $this->keywords;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getModerationState() {
        return $this->moderation_state;
    }

    /**
     * Saves the application to the database 
     * or updates it if it already exists
     * @return integer ID of application
     */
    public function save() {
        $this->validate(); //ensure all fields are properly set

        if ($this->id === -1) {
            $this->id = Database::applicationSet($this);
        } else {
            Database::applicationSet($this);
        }

        return $this->id;
    }

    /**
     * Ensures all fields of the application contain valid values
     * @throws Exception if invalid data is provided
     */
    private function validate() {
        if (strlen($this->title) < 3) {
            throw new Exception("Title must be at least 3 characters");
        }
        if (strlen($this->developer) < 3) {
            throw new Exception("Developer must be at least 3 characters");
        }
        if ($this->price < 0) {
            throw new Exception("Price must be a positive number");
        }
        if (strlen($this->category) < 4) {
            throw new Exception("Category must be at least 4 characters");
        }
        if (sizeof($this->compatible_status) === 0) {
            throw new Exception("Must be compatible with at least one platform");
        }
        if (strlen($this->link_developer) > 0 && substr($this->link_developer, 0, 4) !== "http") {
            throw new Exception("Developer link must be a valid URL");
        }
        if (sizeof($this->keywords) < 3) {
            throw new Exception("Must provide at least 3 keywords");
        }
        if (strlen($this->description) < 10) {
            throw new Exception("Description must be at least 10 characters");
        }
        if (!isset($this->moderation_state)) {
            throw new Exception("Moderation state required");
        }
    }
}
