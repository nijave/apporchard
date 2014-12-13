<div class="row">
    <div id="register">
        <?php
        require_once('classes/Form_Action.php');

        class Register extends Form_Action {
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

            public function processData() {

                //$input = ptejada\uFlex\Collection($this->requestData);

                $registered = $this->user->register(array(
                    'Username' => $this->requestData["Username"],
                    'Password' => $this->requestData["Password"],
                    'Password2' => $this->requestData["Password2"],
                    'Email' => $this->requestData["email"],
                    'groupID' => $this->requestData["groupID"],
                        ), false);

                if ($registered) {
                    echo "<h2>Thank you for registering!</2>";
                    echo '
                    <script type="text/javascript">
                        setTimeout(function () {
                            window.location.href = "/";
                        }, 2000);
                    </script>';
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
            echo '<br><a href="'. $_uri .'">Return to previous page</a>';
        }
        ?>
    </div>
</div>