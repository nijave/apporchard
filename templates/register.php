<div class="row">
	<div id="login-form">
		<h2>Register</h2>
		<form action="/" method="post">
			<label>Username: 
			<input type="text" class="form-control" name="Username" placeholder="Username" autofocus></label>
			<br>
			<label>Email Address: 
			<input type="email" class="form-control" name="email" placeholder="Email address"></label>
			<br>
			<label>Password:
			<input type="password" class="form-control" name="Password" placeholder="Password"></label>
			<br>
			<label>Confirm Password:
			<input type="password" class="form-control" name="Password2" placeholder="Password"></label>
			<br>
			<label>Account Type:
			<select name="groupID">
				<option value="1">Standard User</option>
				<option value="2">Developer</option>
				<option value="3">Moderator</option>
				<option value="4">Administrator</option>
			</select>
			</label>
			<br>
			<input type="submit" class="btn btn-default" name="action" value="Register">
			
		</form>
	</div>
</div>