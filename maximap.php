<!DOCTYPE html>
<html>
	<head>
<?php

$rootPath = '';

require "{$rootPath}engine/core/include.php";
session_write_close();

?>

		<style>
			* { font-family:lucida console, monospace; font-size:13px; cursor:default; line-height:<?php echo CHAR_HEIGHT; ?>px; padding:0px; margin:0px; }
			html{white-space:nowrap; background-color:#000; color:#fff; }
			<?php echo $map->css; ?>
		</style>
		<title>
			Maximap (<?php echo $map->mapName; ?>)
		</title>
	</head>
	<body>
<?php

if (!$map->allowMiniMap) EXIT('No map available.');

echo $map->renderWholeMap();

?>

	</body>
</html>

