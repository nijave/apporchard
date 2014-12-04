<div class="row">
    <div id="Decline">
		if ($user->isSigned()) {
			if($user->GroupID == 1 || $user->GroupID == 2){
								echo "You do not have access to this page";
			}
			
			else if (applicationGet($_POST[$id]).getModerationState() != "PENDING") {
								echo "This application is not up for approval"
			}
			
			else {
        <?php
		applicationGet($_POST[$id])->setModerationState("DELETED");
		save();
		?>
			}
		}
		
		else{
			echo 'You must be logged in to view this page';
		}
    </div>
</div>