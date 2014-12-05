<div class="row">
	<div id="add-form">
		<h2>Add Application</h2>
		<?php
		//check submitted parameters and make sure they all exist
		require_once('classes/Form_Action.php');
		class Add_Application extends Form_Action {
		    private $requiredParams; //parameters required to complete the request
                    private $requestData; //request payload/data
                    private $object; //object created by class
    
                    public function __construct(&$request) {
                        $this->requiredParams = [
                            "title", 
                            "developer", 
                            "price",
                            "category",
                            "developer_link",
                            "image_link",
                            "keywords", 
                            "description"];
                        $this->requestData = $request;
                    }

                    /**
                     * This only checks that they exist/are set
                     */
                    public function checkParams() {
                        $paramsPresent = true;
                        foreach($this->requiredParams as $param) {
                            if(!isset($_REQUEST[$param]))
                                $paramsPresent = false;
                        }
                        return $paramsPresent;
                    }

                    public function processData() {
                        $this->object = new Application();
                        $this->object->setTitle($this->requestData['title']);
                        $this->object->setDeveloper($this->requestData['developer']);
                        $this->object->setPrice($this->requestData['price']);
                        $this->object->setCategory($this->requestData['category']);
                        $this->object->setDeveloperLink($this->requestData['developer_link']);
                        $this->object->setKeywords($this->requestData['keywords']);
                        $this->object->setImageURL($this->requestData['image_link']);
                        $this->object->setDescription($this->requestData['description']);
                        $this->object->setModerationState("PENDING");

                        //set links and compatibility dynamically
                        foreach($this->requestData as $key => $value) {
                            $substring = substr($key, 0, 4);
                            if($substring === "link")
                                $this->object->setStoreLink(substr($key, 4), $value);
                            if($substring === "comp")
                                $this->object->setCompatible(substr($key, 10), $value);
                        }

                        return $this->object;
                    }
                }
		
		$formAction = new Add_Application($_REQUEST);
		
		if($formAction->checkParams()) {
		    $object = $formAction->processData();
			$applicationSuccess = true;
			try {
				$object->save();
			}
			catch(Exception $e) {
				echo "<p class=\"bg-danger form-status\">The following error was encountered while trying to submit your application: <br><i>";
				echo $e->getMessage();
				echo "</i><br>Please fix the error and try submitting your application again.</p>";
				$applicationSuccess = false;
			}
			if($applicationSuccess)
				echo "<p class=\"bg-success form-status\">Your application <strong>{$object->getTitle()}</strong> has been successfully submitted! Note, your application will not appear until it's been approved by a moderator.</p>";
		}
		else {
			echo '<p>An invalid application request has been submitted. Please go back to <a href="/?page=add">add application</a> and try again.</p>';
        }
        ?>
	</div>
</div>