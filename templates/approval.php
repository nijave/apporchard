<div class="row">
	<div id="Approval Form">
		<h2>Admin New App Approval</h2>
		<?php
			if ($user->isSigned()) {
				if($user->GroupID == 1 || $user->GroupID == 2){
				echo "<h2>You do not have acces to this page</h2>";
				}
				else{
					<?php
					$apps = Database::applicationGetPending();
		
					for($i = 0; $i < count($apps); $i++): ?>
						<form action="/" method="post">
							<h3><?php echo Database::applicationGet($apps[$i])->getTitle(); ?></h3>
							<p><?php echo Database::applicationGet($apps[$i])->getDeveloper(); ?></p>
							<p><?php echo Database::applicationGet($apps[$i])->getDescription(); ?></p>
				
							<input type="submit" class="btn btn-default" name="action" value="Accept">
							<input type="submit" class="btn btn-default" name="action" value="Decline">
						</form>
					<?php 
					endfor;
					?>				
				}
			}
			else{
				echo 'You must be logged in to view this page';
			}
		?>	
	</div>
</div>