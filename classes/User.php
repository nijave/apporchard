<?php
/*
	Class representation of each user
*/

require_once('libraries/ptejada/uFlex/autoload.php');

class AO_User extends ptejada\uFlex\User { //AppOrchard user extends uFlex User
    //Constants for user/groupIDs
    const STANDARD_USER = 0;
    const DEVELOPER = 100;
    const MODERATOR = 200;
    const ADMINISTRATOR = 500;
    const SUPER_ADMINISTRATOR = 1000; //unused
    
    
    /**
     * Creates an object and initializes the database connection
     * @see User::__construct(array $userData = array())
     */
    public function __construct(array $userData = array()) {
        //call parent constructor first
        parent::__construct($userData);
        
        //setup database connection
        $this->config->database->update(array(
            'host' => getenv('OPENSHIFT_MYSQL_DB_HOST'),
            'user' => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
            'Password' => getenv('OPENSHIFT_MYSQL_DB_PASSWORD'),
            'name' => getenv('OPENSHIFT_GEAR_NAME')
        ));
    }
}