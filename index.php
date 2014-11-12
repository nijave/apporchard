<!DOCTYPE html>
<html lang="en">
	<?php
		require("templates/header.php");
	?>
  <body>
    <div class="container">
	
		<?php
		require("templates/navigation.php"); //navigation of the page
	
		require("templates/" . $page . ".php"); //page is set in templates/navigation.php 
		
		require("templates/footer.php"); //footer of the page
		?>
		
	</div> <!-- /container -->
	
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="assets/js/ie10-viewport-bug-workaround.js"></script>
	
  </body>
</html>