<?php
$setCats = array();
$setDevs = array();
foreach($_GET as $key => $value) {
    $name = substr($key, 0, 3);
    if($key === "cat".$value) {
        $setCats[] = substr($key, 3);
    }
    else if ($key === "dev".$value) {
        $setDevs[] = substr($key, 3);
    }
}

//Code for handling dynamically generating filter lists
$categories = Database::applicationGetCategories();
$category_filters = "";
foreach($categories as $cat) {
    $_set = in_array($cat, $setCats) ? " checked" : "";
    $category_filters .= "<li><input type='checkbox' name='cat{$cat}' value='{$cat}'{$_set}></li>\n";
}

$developers = Database::applicationGetDevelopers();
$developer_filters = "";
foreach($developers as $dev) {
    $_set = in_array($dev, $setDevs) ? " checked" : "";
    $category_filters .= "<li><input type='checkbox' name='dev{$dev}' value='{$dev}'{$_set}></li>\n";
}
?>

<div class="row">
    <div id="search-filters" class="col-xs-4 col-sm-4 col-lg-2">
        <h2>Filter</h2>
        <form action="/" method="get">
        <h3>Category</h3>
            <ul>
                <?php echo $category_filters; ?>
            </ul>
            <h3>Developer</h3>
            <ul>
                <?php echo $developer_filters; ?>
            </ul>
        </form>
    </div>
    <div id="search-results" class="col-xs-12 col-sm-8 col-lg-10">
            <h2>Search Results</h2>
            <?php
            //check submitted parameters and make sure they all exist
            require_once('classes/Form_Action.php');

            class Search extends Form_Action {
                private $requiredParams; //parameters required to complete the request
                private $requestData; //request payload/data
                private $object; //object created by class

                public function __construct(&$request) {
                    //Set required parameters
                    $this->requiredParams = [
                        "search"
                        ];

                    //Set requestData variable with information from request
                    $this->requestData = $request;
                }

                /**
                 * This only checks that they exist/are set
                 */
                public function checkParams() {
                    $paramsPresent = true;
                    foreach($this->requiredParams as $param) {
                        if(!isset($_REQUEST[$param]))
                            $paramsPresent = false;
                    }
                    return $paramsPresent;
                }

                /**
                 * 
                 * @return array Application IDs
                 */
                public function processData() {
                    //Search string is split at spaces
                    $keywords = explode(' ', urldecode($this->requestData["search"]));

                    //Database is searched for keywords and resulting
                    //IDs are stored in object
                    $this->object = Database::applicationSearch($keywords);

                    return $this->object;
                }
            }

            $formAction = new Search($_REQUEST);

            if($formAction->checkParams()) {
                //Get list of Application IDs returned from search
                $app_ids = $formAction->processData();

                //Array of Applications
                $applications = array();

                //Create an Application object from each ID
                foreach($app_ids as $app_id) {
                    //Add new Application object to array
                    $applications[] = new Application($app_id);
                }

                //Go through applications and generate HTML
                echo "<ul id=\"search-results-list\">";
                foreach($applications as $app) {
                    echo "<li>"
                    . "<img src=\"{$app->getImageURL()}\" alt=\"{$app->getTitle()}\">"
                    . "<h3><a href=\"/?page=details&id={$app->getID()}\">{$app->getTitle()}</a></h3>"
                    . "<p>{$app->getDescription()}</p>"
                    . "</li>";
                }
                echo "</ul>";
            }
            else {
                //parameters incorrect/not present
            }
    ?>
    </div>
</div>