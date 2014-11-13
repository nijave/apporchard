<!DOCTYPE html>
<html lang="en">
	<?php
		require("templates/header.php");
	?>
  <body>
    <div class="container">
	
		<?php
		require("templates/navigation.php"); //navigation of the page
	
		if(isset($_REQUEST['action'])) {
			//handle data submitted by a form
			/*
				Debugging code -- needs to be removed after implementing
			*/
			echo "<br>Action: " . $_REQUEST['action'] . "<br>";
			echo "<pre>" . print_r($_REQUEST, true) . "</pre>";
		} else {
			require("templates/" . $page . ".php"); //page is set in templates/navigation.php
		}
		
		require("templates/footer.php"); //footer of the page
		?>
		
	</div> <!-- /container -->
	
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="assets/js/ie10-viewport-bug-workaround.js"></script>
	
  </body>
</html>