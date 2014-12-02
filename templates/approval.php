<div class="row">
	<div id="Approval Form">
		<h2>Admin New App Approval</h2>
		
		<?php
		$apps = applicationGetPending();
		
		for($i = 0; $i < $apps.size(); $i++) { ?>
			<form action="/" method="post">
				<h3><?php applicationGet($apps[$i]).title; ?></h3>
				<p>Developer: Developer</p>
				<p>Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
				
				<input type="submit" class="btn btn-default" name="action" value="Accept">
				<input type="submit" class="btn btn-default" name="action" value="Decline">
			</form>
		<?php 
		}
		?>
	</div>
</div>