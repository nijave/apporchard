<div class="row">
    <div>
        <?php
        $a = Database::applicationGet($_POST["id"]);
        if ($user->isSigned()) {
            if ($user->GroupID < $user::MODERATOR) {
                echo "You do not have access to this page";
            } else if ( $a->getModerationState() !== "PENDING") {
                echo "This application is not up for approval";
            } else {
                echo 'Application successfully ';
                if($_POST['response'] === 'Accept') {
                    $a->setModerationState("ACTIVE");
                    echo 'approved.';
                }
                else if($_POST['response'] == 'Decline') {
                   $a->setModerationState("DELETED");
                   echo 'denied.';
                }
                $a->save(); 
            }
            echo '<a href="'. $_uri .'">Return to previous page</a>';
        } 
        else {
            echo 'You must be logged in to view this page';
        }
        ?>
    </div>
</div>
