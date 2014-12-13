<?php
$page = "home"; //default page is home
$category = '';

//Check to see if page is set and check to see if php file exists
//for the particular page being requested
if (isset($_GET['page']) && file_exists("templates/" . $_GET['page'] . ".php")) {
    $page = preg_replace("/[^A-Za-z0-9_]/", "", $_GET['page']);
}
else if (isset($_GET['category'])) {
    $category = $_GET['category'];
}

require_once('classes/Application.php');
require_once('classes/Database.php');
require_once('classes/User.php');
require_once('classes/HTMLGen.php');

//Create and start the user object
$user = new AO_User();
$user->start();
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

            //Check to see if action is set, if so, data is being sent in and needs
            //to be handled by the appropriate page (in form_actions)
            if (isset($_REQUEST['action'])) {
                //Replace spaces with underscores
                $form = str_replace(" ", "_", $_REQUEST['action']);
                $form = preg_replace("/[^A-Za-z0-9_]/", "", $form);
                
                //Check to see if php file corresponding to action exists and include
                //the code if it does, else report an error
                if (file_exists("form_actions/" . $form . ".php")) {
                    require_once('form_actions/' . $form . ".php");
                } else {
                    echo "<p>Invalid form action specified</p>";
                }
            } else {
                //If data is not being submitted, display the appropriate page
                require("templates/" . $page . ".php");
            }

            require("templates/footer.php"); //footer of the page
            ?>

        </div> <!-- /container -->

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="assets/js/ie10-viewport-bug-workaround.js"></script>

    </body>
</html>