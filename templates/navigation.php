<div class="masthead">
	<div class="clearfix">
		<h1>AppOrchard</h1>
		<div id="search-box">
		<?php
			if ($user->isSigned()) {
				if($user->GroupID == 1){
				echo
				'<span>Signed in | <a href="/?page=logout">Logout</a></span>';
				}
				if($user->GroupID == 2{
				echo
				'<span>Signed in | <a href="/?page=add">Add new App</a> | <a href="/?page=logout">Logout</a></span>';
				}
			}
			else {
			echo
			'<span><a href="/?page=login">Login</a> | <a href="/?page=register">Register</a></span>';
			}
		?>
			<form action="/" method="get">
				<input type="search" class="form-control" name="search">
				<input type="submit" class="btn btn-default" name="action" value="Search">
			</form>
		</div>
	</div>
	<ul class="nav nav-justified">
	  <li <?php if($page === "home" && $category === '') echo 'class="active"'; ?>><a href="/?page=home">Home</a></li>
	  <li <?php if($category === "productivity") echo 'class="active"'; ?>><a href="/?category=productivity">Productivity</a></li>
	  <li <?php if($category === "games") echo 'class="active"'; ?>><a href="/?category=games">Games</a></li>
	  <li <?php if($category === "music") echo 'class="active"'; ?>><a href="/?category=music">Music</a></li>
	  <li <?php if($category === "social") echo 'class="active"'; ?>><a href="/?category=social">Social</a></li>
	</ul>
</div>