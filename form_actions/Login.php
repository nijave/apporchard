<div class="row">
	<div id="login">
		<h2>Success.</h2>
		<?php
		//print_r($_REQUEST);
		require_once('classes/Form_Action.php');
		class Login extends Form_Action{
			private $requiredParams;
			private $requestData;
			
			public function __construct(&$request){
				$this->requiredParams =[
					"name",
					"username",
					"password",
					"confirmPassword",
					"groupID"];
				$this->requestData = $request;
			}

			public function checkParams() {
                        $paramsPresent = true;
                        foreach($this->requiredParams as $param) {
                            if(!isset($_REQUEST[$param]))
                                $paramsPresent = false;
                        }
                        return $paramsPresent;
            }
			
			public function processData(){

			$username = $_POST['Username'];
			 $password = $_POST['Password'];
			 $auto = $_POST['auto'];  //To remember user with a cookie for autologin

			 $user = new ptejada\uFlex\User();

			 //Login with credentials
			 $user->login($username,$password,$auto);

			 //not required, just an example usage of the built-in error reporting system
			 if($user->signed){
				echo "User Successfully Logged in";
			 }else{
				//Display Errors
				foreach($user->log->getErrors() as $err){
				   echo "<b>Error:</b> {$err} <br/ >";
				}
			  }
					
			}			
		}
		?>
	</div>
</div>