<div class="row">
	<div id="login-form">
		<h2>Register</h2>
		<form action="/" method="post">
			<label>Name: 
			<input type="text" class="form-control" name="name" placeholder="Name" autofocus></label>
			<br>
			<label>Email Address: 
			<input type="email" class="form-control" name="username" placeholder="Email address"></label>
			<br>
			<label>Password:
			<input type="password" class="form-control" name="password" placeholder="Password"></label>
			<br>
			<label>Confirm Password:
			<input type="password" class="form-control" name="confirmPassword" placeholder="Password"></label>
			<br>
			<label>Account Type:
			<select>
				<option value="Standard User">Volvo</option>
				<option value="Developer">Saab</option>
				<option value="Moderator">Mercedes</option>
				<option value="Administrator">Audi</option>
			</select>
			</label>
			<br>
			<input type="submit" class="btn btn-default" name="action" value="Register">
			
		</form>
	</div>
</div>