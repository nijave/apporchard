<?php
/**
 * This class is a template for Form Action handlers
 */
 
abstract class Form_Action {
    private $requiredParams; //parameters required to complete the request
    private $requestData; //request payload/data
    private $object; //object created by class
    
    /**
     * Construct object
     * @param $request pass-by-reference $_REQUEST array
     */
    abstract public function __construct(&$request);
    
    /**
     * Checks the parameters required against what was submitted
     * @return boolean whether or not required parameters are set
     */
    abstract public function checkParams();
    
    /**
     * Processes the data and creates an object representation based on the request
     * @return object representation of the data
     */
     abstract public function processData();        
}
 
