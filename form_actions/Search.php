<div class="row">
    <div id="search-filters" class="col-xs-6 col-lg-4">
        <ul>
            <li> Filter 1 </li>
            <li> Filter 2 </li>
            <li> Filter 3 </li>
        </ul>
    </div>
    <div id="search-results" class="col-xs-12 col-sm-6 col-lg-8">
            <h2>Search Results</h2>
            <?php
            //check submitted parameters and make sure they all exist
            require_once('classes/Form_Action.php');

            class Search extends Form_Action {
                private $requiredParams; //parameters required to complete the request
                private $requestData; //request payload/data
                private $object; //object created by class

                public function __construct(&$request) {
                    //Call parent constructor (does nothing currently)
                    parent::__construct($request);

                    //Set required parameters
                    $this->requiredParams = [
                        "search"];

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
                    $keywords = explode(' ', $this->requestData);

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
                foreach($object as $app_id) {
                    //Add new Application object to array
                    $applications[] = new Application($app_id);
                }

                //Go through applications and generate HTML
                echo "<dl>";
                foreach($applications as $app) {
                    echo "<dt><a href=\"/?page=details&id={$app->getID()}\">{$app->getTitle()}</a></dt>\n";
                    echo "<dd>"
                        . "<img src=\"{$app->getImageURL()}\" alt=\"{$app->getTitle()}\">"
                        . "{$app->getDescription()}"
                        . "</dd>";
                }
                echo "</dl>";
            }
            else {
                //parameters incorrect/not present
            }
    ?>
    </div>
</div>