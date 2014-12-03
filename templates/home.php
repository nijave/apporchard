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
        echo '
        <div class="col-xs-2">
          <img src="'.$app->getImageURL().'" alt="'.$app->getTitle().'">
          <h2>'.$app->getTitle().'</h2>
          <p>
                <img src="assets/img/star_full.png" alt="Star">
                <img src="assets/img/star_full.png" alt="Star">
                <img src="assets/img/star_full.png" alt="Star">
                <img src="assets/img/star_half.png" alt="Half Star">
                <img src="assets/img/star_none.png" alt="Empty Star">
          </p>
          <p>'.$app->getDescription().'</p>
          <p><a class="btn btn-primary" href="/?page=details&id='.$app->getID().'" role="button">View details &raquo;</a></p>
        </div>
        ';
    }
    ?>

</div>