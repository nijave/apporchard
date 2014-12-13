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
?>
<div id="app-tiles" class="row">
    <?php foreach($app_ids as $id) {
        $app = new Application($id);
        
        //Shorten long description
        if(strlen($app->getDescription()) > 141) {
            $desc = substr($app->getDescription(), 0, 130) . "&hellip;";
        }
        else {
            $desc = $app->getDescription();
        }
    ?>
    <div class="col-xs-2 app-box">
        <img src="<?php echo $app->getImageURL(); ?>" alt="<?php echo $app->getTitle(); ?>">
        <h2><?php echo $app->getTitle(); ?></h2>
        <?php HTMLGen::ratings($app->getID(), false); ?>
        <p class="app-desc">
            <?php echo $desc; ?>
        </p>
        <p><a class="btn btn-primary" href="/?page=details&id=<?php echo $app->getID(); ?>" role="button">View details &raquo;</a></p>
    </div>
    <?php } ?>
</div>