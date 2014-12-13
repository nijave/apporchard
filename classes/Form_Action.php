<?php
/**
 * This class is a template for Form Action handlers
 */
 
class Form_Action {
    private $requiredParams; //parameters required to complete the request
    private $requestData; //request payload/data
    private $object; //object created by class
    
    /**
     * Construct object
     * @param $request pass-by-reference $_REQUEST array
     */
    public function __construct(&$request);
    
    /**
     * Checks the parameters required against what was submitted
     * @return boolean whether or not required parameters are set
     */
    public function checkParams() {
        $paramsPresent = true;
        foreach ($this->requiredParams as $param) {
            if (!isset($param)) {
                $paramsPresent = false;
            }
        }
        return $paramsPresent;
    }
    
    /**
     * Processes the data and creates an object representation based on the request
     * @return object representation of the data
     */
     public function processData();        
}
 
