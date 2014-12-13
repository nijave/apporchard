<?php
if (isset($_GET['id'])) {
    $appId = preg_replace("/[0-9]/", "", $_GET['id']);
    try {
        $app = new Application($appId);
    }
    catch(Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

//Security check to make sure user it allowed to access this page
if($app->getModerationState() === "ACTIVE" || ($user->isSigned() && $user->GroupID >= $user::MODERATOR)) {
    $platforms = $app->getCompatiblePlatforms(); //Array of this apps platforms
    $platformStr = implode(", ", $platforms);
?>
<div class="details">
    <div id="detailsContent">
        <div id="detailsLeftCol"> 
        <img src="<?php echo $app->getImageURL(); ?>" alt="<?php echo $app->getTitle(); ?>">
        </div>
        <div id="detailsCenter">
            <h2><?php echo $app->getTitle(); ?></h2>
            <p>
                <?php if(strlen($app->getDeveloperLink()) > 0){ ?>
                    Developer: <a href="<?php echo $app->getDeveloperLink(); ?>" target="_blank"><?php echo $app->getDeveloper(); ?></a>
                <?php } else { ?>
                    Developer: <?php echo $app->getDeveloper(); ?>
                <?php } ?>
                    <br>Platforms: <?php echo $platformStr; ?>
            </p>
            <?php HTMLGen::ratings($app->getID(), true); ?>
        </div>
        <div id="detailsRightCol">
            <p>Price: $<?php echo $app->getPrice(); ?></p>
            <?php
            foreach($platforms as $pform){
                if($app->getStoreLink($pform) !== ""){ ?>
                    <p><a class="btn btn-primary" href="<?php echo $app->getStoreLink($pform); ?>" target="_blank" role="button"><?php echo $pform; ?> &raquo;</a></p>
                <?php }
            }
            ?>
        </div>
    </div>
    <div id="detailsLowerContent">
        <h3>App Description</h3>
        <p><?php echo $app->getDescription(); ?></p>

        <h3>Comments</h3>
        <div id="disqus_thread"></div>
        <script type="text/javascript">
            var disqus_shortname = 'apporchard1'; // required: replace example with your forum shortname

            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function() {
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    </div>
</div>
<?php } //end security check if