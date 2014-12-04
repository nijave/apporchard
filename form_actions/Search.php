<?php
$setCats = array();
$setDevs = array();
$setPlats = array();

foreach($_GET as $key => $value) {
    $value = urldecode($value);
    $name = substr($key, 0, 3);
    if($name === "cat") {
        $setCats[] = $value;
    }
    else if ($name === "dev") {
        $setDevs[] = $value;
    }
	else if ($name === "pla") {
		$setPlats[] = $value;
	}
}

//Code for handling dynamically generating filter lists
$categories = Database::applicationGetCategories();
$category_filters = "";
$increment = 0;
foreach($categories as $cat) {
    $_set = in_array($cat, $setCats) ? " checked" : "";
    $category_filters .= "<li><input type='checkbox' name='cat{$increment}' value='{$cat}'{$_set}> {$cat}</li>\n";
    $increment++;
}

$developers = Database::applicationGetDevelopers();
$developer_filters = "";
$increment = 0;
foreach($developers as $dev) {
    $_set = in_array($dev, $setDevs) ? " checked" : "";
    $developer_filters .= "<li><input type='checkbox' name='dev{$increment}' value='{$dev}'{$_set}> {$dev}</li>\n";
    $increment++;
}

$platforms = Application::$COMPATIBLE_PLATFORMS;
$platform_filters = "";
$increment = 0;
foreach($platforms as $p) {
    $_set = in_array($p, $setPlats) ? " checked" : "";
    $platform_filters .= "<li><input type='checkbox' name='pla{$increment}' value='{$p}'{$_set}> {$p}</li>\n";
    $increment++;
}
?>

<div class="row">
    <div id="search-filters" class="col-xs-4 col-sm-4 col-lg-2">
        <h2>Filter</h2>
        <form action="/" method="get">
			<h3>Platform</h3>
            <ul>
                <?php echo $platform_filters; ?>
            </ul>
			<h3>Category</h3>
            <ul>
                <?php echo $category_filters; ?>
            </ul>
            <h3>Developer</h3>
            <ul>
                <?php echo $developer_filters; ?>
            </ul>
            <?php echo "<input type='hidden' name='search' value='{$_GET['search']}'>"; ?>
            <input type="submit" name="action" value="Search">
        </form>
    </div>
    <div id="search-results" class="col-xs-12 col-sm-8 col-lg-10">
            <h2>Search Results</h2>
            <?php
            //check submitted parameters and make sure they all exist
            require_once('classes/Form_Action.php');

            class Search {
                private $requiredParams; //parameters required to complete the request
                private $requestData; //request payload/data
                private $object; //object created by class
                private $filters;

                public function __construct(&$request, $filters) {
                    //Set required parameters
                    $this->requiredParams = [
                        "search"
                        ];

                    //Set requestData variable with information from request
                    $this->requestData = $request;
					foreach($filters["Platform"] as $plat) {
						$filters["compat_" . strtolower($plat)] = ["1"];
					}
					unset($filters["Platform"]);
                    $this->filters = $filters;
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
                    $this->object = Database::applicationSearch($keywords, $this->filters);

                    return $this->object;
                }
            }

            $formAction = new Search($_REQUEST, ["Platform" => $setPlats, "Category" => $setCats, "Developer" => $setDevs]);

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