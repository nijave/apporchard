<div id="app-tiles" class="row">

    <?php
    if ($category === '') {
        $app_ids = Database::applicationGetNewest();
    }
    else {
        if($category === 'HighestRated') {
            $app_ids = Database::applicationGetHighestRated();
        }
        else {
           $app_ids = Database::applicationGetByCategory($category);
        }
    }
    
    foreach($app_ids as $id) {
        $app = new Application($id);
        if(strlen($app->getDescription()) > 141) {
            $desc = substr($app->getDescription(), 0, 130) . "&hellip;";
        }
        else {
            $desc = $app->getDescription();
        }
        echo '
        <div class="col-xs-2 app-box">
          <img src="'.$app->getImageURL().'" alt="'.$app->getTitle().'">
          <h2>'.$app->getTitle().'</h2>';
          HTMLGen::ratings($app->getID(), false);
          echo '<p class="app-desc">'.$desc.'</p>
          <p><a class="btn btn-primary" href="/?page=details&id='.$app->getID().'" role="button">View details &raquo;</a></p>
        </div>
        ';
    }
    ?>

</div>