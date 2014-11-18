<?php
/*
	This wraps around the Medoo library and sets the 
	relevant variables and adjusts behavior--if necessary
*/

require_once('../libraries/medoo/medoo.php');

class Database extends medoo {

	// Overriding settings in medoo.php
	//  -- Fields automatically populated by openshift environmental variables
	protected $database_type = 'mysql';
	protected $server = getenv('OPENSHIFT_MYSQL_DB_HOST');
	protected $username = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
	protected $password = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
	protected $port = getenv('OPENSHIFT_MYSQL_DB_PORT');
	protected $charset = 'utf8';
	protected $database_name = getenv('OPENSHIFT_GEAR_NAME');


}