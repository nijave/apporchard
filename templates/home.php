<div id="app-tiles" class="row">

    <?php
    if ($category === '') {
        for ($i = 0; $i < 8; $i++)
            echo '
            <div class="col-xs-2">
              <img src="//placehold.it/150x150" alt="Sample App">
              <h2>Sample App</h2>
              <p>
                    <img src="assets/img/star_full.png" alt="Star">
                    <img src="assets/img/star_full.png" alt="Star">
                    <img src="assets/img/star_full.png" alt="Star">
                    <img src="assets/img/star_half.png" alt="Half Star">
                    <img src="assets/img/star_none.png" alt="Empty Star">
              </p>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo.</p>
              <p><a class="btn btn-primary" href="/?page=details" role="button">View details &raquo;</a></p>
            </div>
            ';
    }
    else {
        /*
         * Display applications corresponding to $category
         */
    }
    ?>

</div>