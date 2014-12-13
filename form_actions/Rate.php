<div class="row">
    <div id="Rate">
        <?php
        require_once('classes/Form_Action.php');

        class Rate extends Form_Action {

            private $requestData;
            private $requiredParams;
            private $user;

            public function __construct(&$request, &$user) {
                $this->requiredParams = [
                    "id",
                    "rating"];
                $this->requestData = $request;
                $this->user = $user;
            }

            public function processData() {
                $rating = $this->requestData["rating"];
                $id = $this->requestData["id"];
                $email = $this->user->Email;
                return Database::ratingAdd($email, $id, $rating);
            }
        }
        
        if($user->isSigned()) {
            $rate = new Rate($_POST, $user);
            if ($rate->checkParams()) {
                $status = $rate->processData();
                if($status) {
                    echo "Rating successfully added!";
                } else {
                    echo "Rating not added. There was an error processing your request.";
                }
            }
        }
        ?>
    </div>
    <script type="text/javascript">
        setTimeout(function () {
            window.location.href = "/?page=details&id=<?php echo $_POST['id']; ?>";
        //}, 2500);
        }, 10);
    </script>
    </div>
</div>