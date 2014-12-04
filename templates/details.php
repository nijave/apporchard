<div class="details">
<?php 
	if (isset($_GET['id'])) {
		$appId = $_GET['id'];
		$app = new Application($appId);
	}
	else{
		$app = new Application();
	}
	$platforms = $app->getCompatiblePlatforms(); //Array of this apps platforms
	$platformStr = "";	//string 
	//concatentates the platforms form the array into one string.
	for($i = 0; $i < sizeof($platforms); $i++){
		if($i === 0){
			$platformStr = $platforms[$i];
		}
		else{
			$platformStr = $platformStr . ", " . $platforms[$i];
		}
	}
?>
	<div id="detailsContent">
		<?php
		// figure out echoing of data
		echo 
		"<div id=\"detailsLeftCol\"> 
		<img src=\"{$app->getImageURL()}\" alt=\" {$app->getTitle()}\">
		</div>
		<div id=\"detailsCenter\">
			<h2>{$app->getTitle()}</h2>";
			if(strlen($app->getDeveloperLink()) > 0){
				echo "<p>Developer: <a href=\"{$app->getDeveloperLink()}\" target=\"_blank\">{$app->getDeveloper()}</a></p>";
			}
			else{
				echo "<p>Developer: {$app->getDeveloper()}</p>";
			}  
			echo 
			"<!-- This feature doens\'t exist yet
			<p>
				<img src=\"assets/img/star_full.png\" alt=\"Star\">
				<img src=\"assets/img/star_full.png\" alt=\"Star\">
				<img src=\"assets/img/star_full.png\" alt=\"Star\">
				<img src=\"assets/img/star_half.png\" alt=\"Half Star\">
				<img src=\"assets/img/star_none.png\" alt=\"Empty Star\">
			</p>-->
			<p>Platforms: {$platformStr}</p>
		</div>";
		?>
		<div id="detailsRightCol">
		<?php 
			echo 
			"<p>Price: \${$app->getPrice()}</p>";
			$emptyStr = "";
			foreach($platforms as $pform){
				if($emptyStr !== $app->getStoreLink($pform)){
					echo "<p><a class=\"btn btn-primary\" href=\"{$app->getStoreLink($pform)}\" target=\"_blank\" role=\"button\">{$pform} &raquo;</a></p>";
				}
			}
		?>
		</div>
	</div>
	<div id="detailsLowerContent">
	<?php 
	  echo 
	  "<h3>App Description</h3>
	  <p>{$app->getDescription()}</p>";
	  ?>
	  <div id="rate-form">
		<h3><br>Rate this App!</h3>
		<form action="/" method="post">
			<input type="radio" name="rating" value="5">5/5<br>
			<input type="radio" name="rating" value="5">4/5<br>
			<input type="radio" name="rating" value="5">3/5<br>
			<input type="radio" name="rating" value="5">2/5<br>
			<input type="radio" name="rating" value="5">1/5<br>
			<input type="submit" class="btn btn-default" name="action" value="Rate">
		</form>
	</div>
	  
	  <h2><br>Comments</h2>
	  <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
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