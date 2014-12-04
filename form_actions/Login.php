<div class="row">
    <div id="login">
        <?php
        require_once('classes/Form_Action.php');

        class Login {

            private $requiredParams;
            private $requestData;
            private $user;

            public function __construct(&$request, $user) {
                $this->requiredParams = [
                    "Username",
                    "Password"];
                $this->requestData = $request;
                unset($this->requestData["action"]);
                $this->user = $user;
            }

            public function checkParams() {
                $paramsPresent = true;
                foreach ($this->requiredParams as $param) {
                    if (!isset($_REQUEST[$param]))
                        $paramsPresent = false;
                }
                return $paramsPresent;
            }

            public function processData() {

				$username = $_POST['Username'];
				$password = $_POST['Password'];
				//$auto = $_POST['auto'];  //To remember user with a cookie for autologin

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
        $register = new Login($_POST, $user);
        if($register->checkParams()) {
            $register->processData();
        }
        ?>
    </div>
</div>