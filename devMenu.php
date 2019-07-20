<html>
	<head>
		<title>DevMenu</title>
		<style>
			body
			{
				background-color:#000;
				color:#ddd;
			}

			h1 { color:#faa; }
			a
			{
				display:inline-block;
				color:#aaf;
				font-size:13pt;
				width:400px;
			}
			a:visited { color:#afa; }
			a:hover { color:#f55; background-color: #333; }
		</style>
	</head>
	<body>
		<h1>Asciilands Dev Menu</h1>
		<pre>Note: Some stuff is missing because this thing is shit (editor, console etc.)

<?php

		listFiles('./');
		listFiles('./tools/');
		listFiles('./tools/stressTests/');

		?></pre>
	</body>
</html>

<?php

function listFiles($directory)
{
	echo "{$directory}\n\n";

	$files = dir($directory);
	while ($file = $files->read())
	{
//		if (pathinfo($file, PATHINFO_EXTENSION) !== '.php') continue;
		if (strpos($file, '.php') === false) continue;

		$name = pathinfo($file, PATHINFO_FILENAME);

		echo "\t<a href=\"{$directory}{$file}\">{$name}</a>\n";
	}

	echo "\n\n";
}