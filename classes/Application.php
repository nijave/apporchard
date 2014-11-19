<?
/*
	Class representation of each application
*/

class Application {
    
    const MODERATION_ACTIVE = "ACTIVE";
    const MODERATION_PENDING = "PENDING";
    const MODERATION_DELETED = "DELETED";
    
	private $title; //string
	private $developer; //string
	private $price; //double
	private $compatible_apple; //boolean, compatible with Apple
	private $compatible_android; //boolean, compatible with Android
	private $compatible_windows; //boolean, compatible with Windows
	private $link_apple; //string, link to Apple app
	private $link_android; //string, link to Android app
	private $link_windows; //string, link to Windows app
	private $link_developer; //link to developer website
	private $keywords; //array[string] of keywords
	private $description; //string, description of app
	private $moderation_state; //string (pending, active, deleted), current moderation state of app
	
	public function __construct() {
		$this->compatible_apple = false;
        $this->compatible_android = false;
        $this->compatible_windows = false;
        $this->keywords = array();
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
    
    private function changeCompatible(String $platform, boolean $value) {
        switch($platform) {
            case "Apple":
                $this->compatible_apple = true;
                break;
            case "Android":
                $this->compatible_android = true;
                break;
            case "Windows":
                $this->compatible_windows = true;
                break;
            default:
                throw new Exception("Invalid platform provided", 1);
                break;
        }
    }
    
    public function setCompatible(String $platform) {
        changeCompatible($platform, true);
    }
    
    public function unsetCompatible(String $platform) {
        changeCompatible($platform, false);
    }
    
    public function setAppleLink(String $link) {
        $this->link_apple = $link;
    }
    
    public function setAndroidLink(String $link) {
        $this->link_android = $link;
    }
    
    public function setWindowsLink(String $link) {
        $this->link_windows = $link;
    }
    
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }
    
    public function setDescription(String $desc) {
        $this->description = $desc;
    }
    
    public function setModerationState(String $state) {
        $this->moderation_state = $state;
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
    
    private function getCompatible(String $platform) {
        switch($platform) {
            case "Apple":
                return $this->compatible_apple;
            case "Android":
                return $this->compatible_android;
            case "Windows":
                return $this->compatible_windows;
            default:
                throw new Exception("Invalid platform provided", 1);
                break;
        }
    }
    
    public function getAppleLink() {
        return $this->link_apple;
    }
    
    public function getAndroidLink() {
        return $this->link_android;
    }
    
    public function getWindowsLink() {
        return $this->link_windows;
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
}