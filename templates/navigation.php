<?php
if(isset($_GET['return'])) {
    $uri = filter_input(INPUT_GET, "return", FILTER_SANITIZE_STRING);
}
else {
    $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);  
}
?>
<div class="masthead">
	<div class="clearfix">
		<h1>AppOrchard</h1>
		<div id="search-box">
                    <?php
                    if ($user->isSigned()) {
                        //Keep track of which links should be shown
                        $nav_options = array();

                        //Moderator and above can approve
                        if ($user->GroupID >= $user::MODERATOR){
                                $nav_options[] = '<a href="/?page=approval">Approve Apps</a>';
                        }

                        //Developer and above can submit
                        if($user->GroupID >= $user::DEVELOPER){
                                $nav_options[] = '<a href="/?page=add">Add new App</a>';
                        }

                        //Anyone logged in can logout
                        $nav_options[] = '<a href="/?page=logout&return='. $uri .'">Logout</a>';

                        //Display nav options separated by vertical pipes
                        echo "<span>" . implode(' | ', $nav_options) . "</span>";
                    }
                    else {
                        echo '<span><a href="/?page=login&return='. $uri .'">Login</a> | <a href="/?page=register&return='. $uri .'">Register</a></span>';
                    }
                    ?>
                    <br>
                    <form action="/" method="get">
                            <input type="search" class="form-control" name="search">
                            <input type="submit" class="btn btn-default" name="action" value="Search">
                    </form>
		</div>
	</div>
	<ul class="nav nav-justified">
	  <li <?php if($page === "home" && $category === '' && !isset($_REQUEST["action"])) echo 'class="active"'; ?>><a href="/?page=home">Home</a></li>
          <li <?php if($category === "HighestRated") echo 'class="active"'; ?>><a href="/?category=HighestRated">Highest Rated</a></li>
          <li <?php if($category === "productivity") echo 'class="active"'; ?>><a href="/?category=productivity">Productivity</a></li>
	  <li <?php if($category === "games") echo 'class="active"'; ?>><a href="/?category=games">Games</a></li>
	  <li <?php if($category === "social") echo 'class="active"'; ?>><a href="/?category=social">Social</a></li>
	</ul>
</div>