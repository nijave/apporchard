<div class="row">
    <div id="login">
        <?php
        require_once('classes/Form_Action.php');

        class Login extends Form_Action {
            private $requestData;
            private $requiredParams;
            
            public function __construct(&$request) {
                $this->requiredParams = [
                    "Username",
                    "Password"];
                $this->requestData = $request;
                unset($this->requestData["action"]);
            }

            public function checkParams() {
                $paramsPresent = true;
                foreach ($this->requiredParams as $param) {
                    if (!isset($param)) {
                        $paramsPresent = false;
                    }
                }
                return $paramsPresent;
            }

            public function processData() {

				$username = $this->requestData['Username'];
				$password = $this->requestData['Password'];
				//$auto = $_POST['auto'];  //To remember user with a cookie for autologin

				$user = new AO_User();

				//Login with credentials
				$user->login($username,$password);

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
        $register = new Login($_POST);
        if($register->checkParams()) {
            $register->processData();
        }
        ?>
    </div>
</div>