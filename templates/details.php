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
			  <h2>{$app->getTitle()}</h2>
			  <p>Developer: {$app->getDeveloper()}</p>
			  <p>
				<img src=\"assets/img/star_full.png\" alt=\"Star\">
				<img src=\"assets/img/star_full.png\" alt=\"Star\">
				<img src=\"assets/img/star_full.png\" alt=\"Star\">
				<img src=\"assets/img/star_half.png\" alt=\"Half Star\">
				<img src=\"assets/img/star_none.png\" alt=\"Empty Star\">
				(Num reviews)
			  </p>
			  <p>Platforms: {$platformStr}</p>
		</div>";
		?>
		<div id="detailsRightCol">
		<?php 
			echo 
			"<p>Price: \${$app->getPrice()}</p>";
			foreach($platforms as $pform){
				echo "<p><a class=\"btn btn-primary\" href=\"{$app->getStoreLink($pform)}\" target=\"_blank\" role=\"button\">{$pform} &raquo;</a></p>";
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
	  <div id="reviews">
		<h3>Customer Reviews</h3>
		<p>By Name on November 12, 2014</p>
		<p>Platform: PC</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	  </div>
	</div>
</div>