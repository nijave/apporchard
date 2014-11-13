<?php
$page = "home"; //default page is home
if(isset($_GET['page']) && file_exists("templates/" . $_GET['page'] . ".php"))
	$page = $_GET['page'];
?>
<div class="masthead">
	<div class="clearfix">
		<h1>AppOrchard</h1>
		<div id="search-box">
			<span><a href="/?page=login">Login</a> | <a href="/?page=register">Register</a></span>
			<form action="/" method="get">
				<input type="search" class="form-control" name="search">
				<input type="submit" class="btn btn-default" name="action" value="Search">
			</form>
		</div>
	</div>
	<ul class="nav nav-justified">
	  <li <?php if($page === "home") echo 'class="active"'; ?>><a href="/?page=home">Home</a></li>
	  <li <?php if($page === "popular") echo 'class="active"'; ?>><a href="#">Popular</a></li>
	  <li <?php if($page === "games") echo 'class="active"'; ?>><a href="#">Games</a></li>
	  <li <?php if($page === "books") echo 'class="active"'; ?>><a href="#">Books</a></li>
	  <li <?php if($page === "music") echo 'class="active"'; ?>><a href="#">Music</a></li>
	  <li <?php if($page === "media") echo 'class="active"'; ?>><a href="#">Media</a></li>
	</ul>
</div>