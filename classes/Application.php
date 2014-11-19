<?
/*
	Class representation of each application
*/

class Application {
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
	
	public function __construct(String $title) {
		$this->title = $title;
	}
	
	public function setDeveloper(String $developer) {
		$this->developer = $developer;
	}
}