<?php

?>
<div class="row">
	<div id="login-form">
		<h2>Login</h2>
		<form action="/" method="post">
			<label>Username: 
			<input type="text" class="form-control" name="Username" placeholder="Username" autofocus></label>
			<br>
			<label>Password:
			<input type="password" class="form-control" name="Password" placeholder="Password"></label>
			<br>
                        <label>Remember me?:</label>
                        <input type="checkbox" name="auto">
                        <br>
                        <input type="hidden" name="return_uri" value="<?php echo $uri; ?>">
			<input type="submit" class="btn btn-default" name="action" value="Login">
		</form>
	</div>
</div>