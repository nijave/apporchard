<?php
$page = "home"; //default page is home
if(isset($_GET['page']) && file_exists("templates/" . $_GET['page'] . ".php"))
    $page = $_GET['page'];

require_once('classes/Application.php');
require_once('classes/Database.php');
//require_once('classes/User.php');
?>
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
			$form = str_replace(" ", "_", $_REQUEST['action']);
            if(file_exists("form_actions/" . $form . ".php")) {
                require_once('form_actions/' . $form . ".php");
            }
            else {
                echo "<p>Invalid form action specified</p>";
            }
			// Debugging code -- needs to be removed after implementing
			/*
			echo "<br>Action: " . $_REQUEST['action'] . "<br>"; //debugging code
			echo "<pre>" . print_r($_REQUEST, true) . "</pre>"; //debugging code
             */
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