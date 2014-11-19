<?
/**
	Class representation of each application
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
     */
	public function __construct() {
		foreach($COMPATIBLE_PLATFORMS as $platform)
            $compatible_status["$platform"] = false;
        $this->keywords = array();
        $this->id = -1; //ids must be positive
	}
    
    /**
     * Create an application object using an existing id (from the database)
     */
    public function __construct(Integer $id) {
        //TODO: create Application object from information in the database
    }
	
    public function setTitle(String $title) {
        $this->title = $title;
    }    
    
	public function setDeveloper(String $developer) {
		$this->developer = $developer;
	}
    
    public function setPrice(double $price) {
        $this->price = $price;
    }
    
    public function setCategory(String $category) {
        $this->category = $category;
    }
    
    private function changeCompatible(String $platform, boolean $value) {
        if(!in_array($platform, $COMPATIBLE_PLATFORMS)) {
            throw new Exception("Invalid platform provided", 1);
        }
        else {
            $this->compatible_status["$platform"] = $value;
        }
    }
    
    public function setCompatible(String $platform) {
        changeCompatible($platform, true);
    }
    
    public function unsetCompatible(String $platform) {
        changeCompatible($platform, false);
    }
    
    public function setStoreLink(String $platform, String $link) {
        if(!in_array($platform, $COMPATIBLE_PLATFORMS)) {
            throw new Exception("Invalid platform provided", 1);
        }
        else {
            $this->link_store["$platform"] = $link;
        }
    }
    
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }
    
    public function setDescription(String $desc) {
        $this->description = $desc;
    }
    
    public function setModerationState(String $state) {
        if(!in_array($state, $MODERATION_STATES)) {
            throw new Exception("Invalid moderation state", 1);        
        }
        else {
           $this->moderation_state = $state; 
        } 
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
    
    public function getCompatible(String $platform) {
        if(!in_array($platform, $COMPATIBLE_PLATFORMS)) {
            throw new Exception("Invalid platform provided", 1);
        }
        else {
            return $this->compatible_status["$platform"];
        }
    }
    
    /**
     * Returns an array of platforms (strings) that an
     * application is compatible with
     */ 
    public function getCompatiblePlatforms() {
        $platforms = array();
        foreach($this->compatible_status as $platform => $value) {
            if($value === true) {
                $platforms[] = $platform;
            }
        }
        return $platforms;
    }
    
    /**
     * Returns the link for a particular platform store
     */
    public function getStoreLink(String $platform) {
        if(!in_array($platform, $COMPATIBLE_PLATFORMS)) {
            throw new Exception("Invalid platform provided", 1);
        }
        else {
            return $this->link_store["$platform"];
        }
    }
    
    /**
     * Returns all store links
     */ 
    public function getStoreLinks() {
        return $this->link_store;
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
     */
    public function save() {
        $this->validate(); //ensure all fields are properly set
        
        if($this->id === -1) {
            $this->id = Database::applicationSet($this);
        }
        else {
            Database::applicationSet($this);
        }
        
        return $this->id;
    }
    
    /**
     * Ensures all fields of the application contain valid values
     */
    private function validate() {
        if(strlen($this->title) < 3)
            throw new Exception("Title must be at least 3 characters");
        if(strlen($this->developer) < 3)
            throw new Exception("Developer must be at least 3 characters");
        if($this->price < 0)
            throw new Exception("Price must be a positive number");
        if(strlen($this->category) < 4)
            throw new Exception("Category must be at least 4 characters");
        if(sizeof($this->compatible_status) === 0)
            throw new Exception("Must be compatible with at least one platform");
        if(strlen($this->link_developer) > 0 && substr($this->link_developer, 0, 4) !== "http")
            throw new Exception("Developer link must be a valid URL");
        if(sizeof($this->keywords) < 3)
            throw new Exception("Must provide at least 3 keywords");
        if(strlen($this->description) < 10)
            throw new Exception("Description must be at least 10 characters");
        if(!isset($this->moderation_state))
            throw new Exception("Moderation state required");
    }
}