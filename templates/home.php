<div id="app-tiles" class="row">

    <?php
    if ($category === '') {
        $app_ids = Database::applicationGetNewest();
    }
    else {
        $app_ids = Database::applicationGetByCategory($category);
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
          <h2>'.$app->getTitle().'</h2>
		  <!-- This feature doens\'t exist yet
          <p>';
                $rating = Database::ratingGet($app->getID());
                for($i = 0; $i < 5; $i++) {
                    if($i <= $rating - .5) {
                        echo "<img src=\"assets/img/star_full.png\" alt=\"Star\">";
                    }
                    else if($i + .5 === $rating) {
                        echo "<img src=\"assets/img/star_half.png\" alt=\"Half Star\">";
                    }
                    else {
                        echo "<img src=\"assets/img/star_none.png\" alt=\"Empty Star\">";
                    }
                }
          echo '</p>
		  -->
          <p class="app-desc">'.$desc.'</p>
          <p><a class="btn btn-primary" href="/?page=details&id='.$app->getID().'" role="button">View details &raquo;</a></p>
        </div>
        ';
    }
    ?>

</div>