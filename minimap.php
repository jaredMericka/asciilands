<!DOCTYPE html>
<html>
	<head>
<?php

$rootPath = '';

require "{$rootPath}engine/core/include.php";
session_write_close();

ob_start();

?>

		<style>
			* { font-family:lucida console, monospace; font-size:13px; cursor:default; line-height:<?php echo CHAR_HEIGHT; ?>px; padding:0px; margin:0px; }
			html{ text-align:center; background-color:#000; color:#fff; }
			iframe { display:none; }
		</style>
		<title>
			Minimap (<?php echo $map->mapPath; ?>)
		</title>

		<?php if ($_GET['refresh']) { ?>
		<script>

//			var refreshTimer = setTimeout(refresh, 5000);

			function refresh ()
			{
				window.location.reload();
			}
		</script>
		<?php } else if ($player instanceof EditorPlayer2) { ?>

		<script>
			var refreshTimer = setInterval(refresh, 5000);

			function refresh ()
			{
				document.getElementById('map').innerHTML = document.getElementById('iframe').contentDocument.getElementById('map').innerHTML;
				document.getElementById('iframe').contentDocument.location.reload(true);
			}
		</script>

		<?php } ?>

	</head>
	<body>
		<div id="map">
<?php

if (!$map->allowMiniMap) EXIT('No map available.');

$top	= isset($map->minimapTop)		? $map->minimapTop		: null;
$bottom	= isset($map->minimapBottom)	? $map->minimapBottom	: null;
$left	= isset($map->minimapLeft)		? $map->minimapLeft		: null;
$right	= isset($map->minimapRight)		? $map->minimapRight	: null;

console_echo("Top: {$top}");
console_echo("Bottom: {$bottom}");
console_echo("Left: {$left}");
console_echo("Right: {$right}");

echo $map->getMiniMap($top, $left, $bottom, $right);


if ($player instanceof EditorPlayer2 && !isset($_GET['refresh'])) {
?>
		</div>
		<iframe id="iframe" src="minimap.php?refresh=true" />

<?php } ?>
	</body>
</html>

