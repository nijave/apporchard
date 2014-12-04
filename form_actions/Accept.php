<div class="row">
    <div id="Decline">
        <?php
        if ($user->isSigned()) {
            if ($user->GroupID == 1 || $user->GroupID == 2) {
                echo "You do not have access to this page";
            } else if (applicationGet($_POST["id"]) . getModerationState() != "PENDING") {
                echo "This application is not up for approval";
            } else {
                $a = applicationGet($_POST["id"]);
                $a->setModerationState("ACTIVE");
                $a->save();
            }
        } else {
            echo 'You must be logged in to view this page';
        }
        ?>
    </div>
</div>