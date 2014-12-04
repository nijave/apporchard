<div class="row">
	<div id="Approval Form">
		<h2>Admin New App Approval</h2>
		<?php
			if ($user->isSigned()) {
				if($user->GroupID == 1 || $user->GroupID == 2){
				echo "<h2>You do not have acces to this page</h2>";
				}
				else{
					$apps = Database::applicationGetPending();
		
					for($i = 0; $i < count($apps); $i++):
					echo'
						<form action="/" method="post">
							<h3>'; echo Database::applicationGet($apps[$i])->getTitle(); echo '</h3>
							<p>'; echo Database::applicationGet($apps[$i])->getDeveloper(); echo '</p>
							<p>'; echo Database::applicationGet($apps[$i])->getDescription(); echo '</p>
				
							<input type="submit" class="btn btn-default" name="action" value="Accept">
							<input type="submit" class="btn btn-default" name="action" value="Decline">
						</form>';
					endfor;			
				}
			}
			else{
				echo 'You must be logged in to view this page';
			}
		?>	
	</div>
</div>