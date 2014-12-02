<div class="row">
	<div id="Approval Form">
		<h2>Admin New App Approval</h2>
		
		$apps = array(database::applicationGetPending());
		
		for ($i = 0; $x <= sizeof($apps); $i++) {
		<form action="/" method="post">
			<?php
				<h3>echo database::applicationGet($apps($i))->name;</h3>
				<p>Developer: Developer</p>
				<p>Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
			?>
			<input type="submit" class="btn btn-default" name="action" value="Accept">
			<input type="submit" class="btn btn-default" name="action" value="Decline">
		</form>
		}
	</div>
</div>