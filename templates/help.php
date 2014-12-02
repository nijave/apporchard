<div>
	<?php
	$help = fopen("docs/HELP.md", "r") or die("Unable to open file!");
	// Output one line until end-of-file
	echo "<h2>{fgets($help)}</h2>";
	while(!feof($help)) {
	   echo fgets($help) . "<br>";
	}
	fclose($help);
	?>
</div>