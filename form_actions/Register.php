<div class="row">
    <div id="register">
        <h2>Thank you for registering!</h2>
        <?php
        require_once('libraries/ptejada/uFlex/Collection.php');
        print_r($_REQUEST);
        require_once('classes/Form_Action.php');

        class Register {

            private $requiredParams;
            private $requestData;
            private $user;

            public function __construct(&$request, $user) {
                $this->requiredParams = [
                    "Username",
                    "email",
                    "Password",
                    "Password2",
                    "groupID"];
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

                $input = ptejada\uFlex\Collection($this->requestData);

                $registered = $this->user->register(array(
                    'Username' => $input->Username,
                    'Password' => $input->Passsword,
                    'Password2' => $input->Passsword2,
                    'email' => $input->email,
                    'groupID' => $input->groupID,
                        ), false);

                if ($registered) {
                    echo "<h2>Thank you for registering!</2>";
                } else {
                    //Display Errors
                    foreach ($this->user->log->getErrors() as $err) {
                        echo "<b>Error:</b> {$err} <br/ >";
                    }
                }
            }
        }
        $register = new Register($_POST, $user);
        if($register->checkParams()) {
            $register->processData();
        }
        ?>
    </div>
</div>