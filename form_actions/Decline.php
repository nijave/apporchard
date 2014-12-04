<div class="row">
    <div id="Decline">
        <?php
        $a = Database::applicationGet($_POST["id"]);
        if ($user->isSigned()) {
            if ($user->GroupID == 1 || $user->GroupID == 2) {
                echo "You do not have access to this page";
            } else if ($a->getModerationState() !== "PENDING") {
                echo "This application is not up for approval";
            } else {
                $a->setModerationState("DELETED");
                $a->save();
				echo 'Application successfully denied';
            }
        } else {
            echo 'You must be logged in to view this page';
        }
        ?>
		<script type="text/javascript">
			setTimeout(function () {
				window.location.href = "/?=approval";
			}, 1250);
		</script>
    </div>
</div>