<div class="row">
    <div id="Rate">
        <?php
        require_once('classes/Form_Action.php');

        class Rate extends Form_Action {

            private $requestData;
            private $requiredParams;

            public function __construct(&$request) {
                $this->requiredParams = [
                    "id",
                    "rating"];
                $this->requestData = $request;
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
                $rating = $this->requestData["rating"];
                $id = $this->requestData["id"];
                $email = $user->Email;
                return Database::ratingAdd($email, $id, $rating);
            }
        }
        if($user->isSigned()) {
            $rate = new Rate($_POST);
            if ($rate->checkParams()) {
                $status = $rate->processData();
                if($status) {
                    echo "Rating successfully added!";
                } else {
                    echo "Rating not added. You can only rate each app once.";
                }
            }
        }
        ?>
    </div>
    <script type="text/javascript">
        setTimeout(function () {
            window.location.href = "/?page=details&id=<?php echo $_POST['id']; ?>";
        }, 2000);
    </script>
    </div>
</div>