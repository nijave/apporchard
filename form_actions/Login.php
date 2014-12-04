<div class="row">
	<div id="login">
		<h2>Success.</h2>
		<?php

			$username = $_POST['Username'];
			 $password = $_POST['Password'];
			 $auto = $_POST['auto'];  //To remember user with a cookie for autologin

			 //$user = new ptejada\uFlex\User(); This is already defined at the top of index

			 //Login with credentials
			 $user->login($username,$password,$auto);

			 //not required, just an example usage of the built-in error reporting system
			 if($user->signed){
				echo "User Successfully Logged in";
			 }else{
				//Display Errors
				foreach($user->log->getErrors() as $err){
				   echo "<b>Error:</b> {$err} <br/ >";
				}
			 }
		?>
	</div>
</div>