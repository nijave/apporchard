<div>
	<?php
	$help = fopen("docs/HELP.md", "r") or die("Unable to open file!");
	// Output one line until end-of-file
	$welcome = fgets($help);
	echo "<h2>{$welcome}</h2>";
	while(!feof($help)) {
	   echo fgets($help) . "<br>";
	}
	fclose($help);
	?>
</div>