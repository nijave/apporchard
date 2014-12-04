<div class="row">
    <div id="register">
        <h2>Thank you for registering!</h2>
        <?php
        print_r($_REQUEST);
        require_once('classes/Form_Action.php');

        class Register extends Form_Action {

            private $requiredParams;
            private $requestData;

            public function __construct(&$request) {
                $this->requiredParams = [
                    "username",
                    "email",
                    "password",
                    "confirmPassword",
                    "groupID"];
                $this->requestData = $request;
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

                $input = ptejada\uFlex\Collection($_POST);

                $registered = $user->register(array(
                    'Username' => $input->Username,
                    'Password' => $input->passsword,
                    'Password2' => $input->confirmPasssword,
                    'email' => $input->email,
                    'groupID' => $input->groupID,
                        ), false);

                if ($registered) {
                    echo "<h2>Thank you for registering!</2>";
                } else {
                    //Display Errors
                    foreach ($user->log->getErrors() as $err) {
                        echo "<b>Error:</b> {$err} <br/ >";
                    }
                }
            }
        }
        $register = new Register($_POST);
        if($register->checkParams()) {
            $register->processData();
        }
        ?>
    </div>
</div>