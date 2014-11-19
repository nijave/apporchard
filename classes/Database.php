<?php
/*
	This wraps around the Medoo library and sets the 
	relevant variables and adjusts behavior--if necessary
*/

require_once('../libraries/medoo/medoo.php');
require_once('Application.php');

class Database extends medoo {

    private static $instance = null;
    
    /*
	// Overriding settings in medoo.php
	//  -- Fields automatically populated by openshift environmental variables
	protected $database_type = 'mysql';
	protected $server = getenv('OPENSHIFT_MYSQL_DB_HOST');
	protected $username = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
	protected $password = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
	protected $port = getenv('OPENSHIFT_MYSQL_DB_PORT');
	protected $charset = 'utf8';
	protected $database_name = getenv('OPENSHIFT_GEAR_NAME');
*/
    private static function setInstance() {
        if($instance === null) {
            $instance = new medoo([
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
    
    public static function applicationGet($id) {
        self::setInstance();
        //TODO: gets an application based on the id
        return $id; //for DEBUGGING
    }
    
    public static function applicationSet(Application $app) {
        self::setInstance();
        $exists = $instance->select("applications", ["id"], ["id"=>$app->getID()]);
        
        //TODO: updates an application in the database
    }
}

/*
 * DEBUGGING CODE
 */
echo Database::applicationGet(1);
