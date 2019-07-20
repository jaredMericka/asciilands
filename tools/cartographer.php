<!DOCTYPE html>
<?php

$rootPath = '../';
$mapDirectory = "{$rootPath}content/maps/";

require "{$rootPath}engine/core/include.php";

$maps = [];

scanMaps($mapDirectory);

function scanMaps ($directory)
{
	global $maps;

	foreach (scandir($directory) as $file)
	{
		if ($file === '..' || $file === '.') continue;

		$path = "{$directory}/{$file}";

		if (is_dir($path))
		{
			scanMaps($path);
			continue;
		}

		if (substr($file, -4) === '.map')
		{
			$maps[] = substr($file, 0, -4);
		}
	}
}

function renderMapInfo ($mapName)
{
	global $MAP_paths;

	$instances = [];

	$levels = [];

	$mapConstant = constant('MAP_' . strtoupper($mapName));

//	$match = 'map_' . strtolower($mapName);
	$match = 'map_' . $mapConstant;
	$length = strlen($match);

	$midPoint_n_offset = null;
	$midPoint_w_offset = null;

	// Everything about this loop is fucking awful.
	foreach ($_SESSION as $key => $value)
	{
		if (substr($key, 0, $length) === $match && strlen(substr($key, strpos($key, '_', $length + 1))) === 9) // TERRIBLE
		{
			$instances[] = $key;

			$level = substr($key, $length + 1, strpos($key, '_', $length + 1) - ($length + 1));

			$levels[$level] = isset($levels[$level]) ? $levels[$level] + 1 : 1;

			if ($instances && !isset($midPoint_n_offset, $midPoint_w_offset))
			{
				$map = unserialize($value);

				if (isset($map->minimapTop, $map->minimapBottom, $map->minimapLeft, $map->minimapRight))
				{
					$midPoint_n_offset = round(($map->minimapBottom - $map->minimapTop) / 2) + $map->minimapTop;
					$midPoint_w_offset = round(($map->minimapRight - $map->minimapLeft) / 2) + $map->minimapLeft;
				}
				else
				{
					$tileHeight = (int) array_search(max($map->tiles), $map->tiles);
					$tileWidth = 0;

					foreach ($map->tiles as $row)
					{
						if (!$row) continue;
						$width = (int) array_search(max($row), $row);
						if ($width > $tileWidth) $tileWidth = $width;
					}

					$midPoint_n_offset = round($tileHeight / 2);
					$midPoint_w_offset = round($tileWidth / 2);
				}
			}
		}
	}

	if (!$midPoint_n_offset) $midPoint_n_offset = 10;
	if (!$midPoint_w_offset) $midPoint_w_offset = 10;

	$func = "notifyServer('console', 24, 'go {$midPoint_n_offset} {$midPoint_w_offset} {$mapName}'); document.location.reload(true);";

	echo '<div class="map">';
	echo "<h1 onclick=\"{$func}\">{$mapName}</h1>";
	echo "<h2>{$MAP_paths[$mapConstant]}</h2>";

	if ($instances) echo "<div class=\"imgContainer\"><img src=\"mapImage.php?map={$mapConstant}\" onload=\"registerClickFunction(this);\" mapName=\"{$mapName}\" map_n_offset=\"{$map->minimapTop}\" map_w_offset=\"{$map->minimapLeft}\" /></div>";

	echo '<p>Instances: ' . count($instances) . '</p>';

	echo '<p>';
	foreach ($instances as $instance) echo $instance . '<br>';
	echo '</p>';


//	echo "<div onclick=\"{$func}\" class=\"go\">GO</div>";

	echo '</div>';
}

?>
<html>
	<head>
		<title>Cartographer (Asciilands)</title>
		<style>
			body
			{
				background-color:#000;
				font-size:10pt;
			}

			h1
			{
				font-size:13pt;
				margin:0px;
				color:#500;
				cursor:pointer;
			}

			h1:hover
			{
				color:#f00;
				text-shadow:#f66 0px 0px 10px;
			}

			h2
			{
				font-size:9pt;
				color:#333;
				margin:3px 0px;
				font-weight:normal;
			}

			img
			{
				border:none;
				padding:0px;
				margin:0px;
			}

			.map
			{
				float:left;

				padding:10px;
				background-color:#aaa;
				margin:6px;
				/*width:800px;*/

				border-style:outset;
				border-color:#888;
				border-width:3px;
				border-radius:8px;
			}

			.imgContainer
			{
				display:inline-block;
				border-style:inset;
				border-color:#888;
				border-width:6px;
				/*cursor:crosshair;*/
			}

			.go
			{
				font-size:14pt;
				font-weight:bold;
				padding:4px;
				background-color:#4b4;
				color:#060;
				width:70px;
				text-align:center;

				border-style:outset;
				border-color:#060;
				border-width:3px;
				border-radius:8px;

				cursor:pointer;
			}

			.go:hover
			{
				background-color: #6c6;
			}

			.go:active
			{
				background-color: #292;
				border-style:inset;
			}

			#thumbnail
			{
				opacity:0.4;
				border-style:solid;
				border-color:#fff;
				border-width:1px;
				position:fixed;
				bottom:10px;
				left:10px;
				height:300px;
				width:300px;
				background-size:400%;
				background-repeat:no-repeat;
				background-position:center;
				background-color:#000;
			}

			#marker
			{
				height:4px;
				width:4px;
				border-width:1px;
				border-style:solid;
				/*border-color:#f0f #0f0;*/
				border-color:#fff #000;
				position:absolute;
				top:150px;
				left:150px;
				border-radius:1px;

			}

			#coOrds
			{
				display:none;
				color:#fff;
				background-color:rgba(0,0,0,0.4);
				padding:2px;
				margin:2px;

			}

		</style>
		<script>
			var thumbnailZoom = 5;

			var notifyWait = false;

			function notifyServer(key, type, content)
			{
				if (notifyWait) return 0;
				notifyWait = true;

				var rightClick = (rightClick !== undefined && rightClick ? 1 : 0);

				UIEventRequest = new XMLHttpRequest();

				UIEventRequest.open("POST","../ajax/notifyServer.php","true");
				UIEventRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				UIEventRequest.send('k='+key+'&c='+content+'&t='+type);

				UIEventRequest.onreadystatechange = function()
				{
					notifyWait = false;
				};
				return false;
			}

			function registerClickFunction(imgTag)
			{
				imgTag.onclick = img__onclick;
				imgTag.onmousemove = img__onmousemove;
				imgTag.onmouseover = img__onmouseover;
				imgTag.onmouseout = img__onmouseout;
			}

			function img__onclick (event)
			{
				var map_n_offset = this.getAttribute('map_n_offset');
				map_n_offset = map_n_offset ? parseInt(map_n_offset) : -10;
				var n_offset = event.offsetY + map_n_offset;


				var map_w_offset = this.getAttribute('map_w_offset');
				map_w_offset = map_w_offset ? parseInt(map_w_offset) : -10;
				var w_offset = event.offsetX + map_w_offset;

				notifyServer('console', 24, 'go ' + n_offset + ' ' + w_offset + ' ' + this.getAttribute('mapName'));
			}

			function img__onmousemove (event)
			{
				var map_n_offset = this.getAttribute('map_n_offset');
				map_n_offset = map_n_offset ? parseInt(map_n_offset) : -10;
				var n_offset = event.offsetY + map_n_offset;


				var map_w_offset = this.getAttribute('map_w_offset');
				map_w_offset = map_w_offset ? parseInt(map_w_offset) : -10;
				var w_offset = event.offsetX + map_w_offset;

				this.title = '' + n_offset + ':' + w_offset;

				var thumbnail = document.getElementById('thumbnail');

				var tnOffsetX = 0 - event.offsetX;
				var tnOffsetY = 0 - event.offsetY;

				tnOffsetX *= thumbnailZoom;
				tnOffsetY *= thumbnailZoom;

				tnOffsetX += thumbnail.offsetWidth / 2;
				tnOffsetY += thumbnail.offsetHeight / 2;

				thumbnail.style.backgroundPositionX = tnOffsetX + 'px';
				thumbnail.style.backgroundPositionY = tnOffsetY + 'px';

				document.getElementById('coOrds').innerHTML = n_offset + ':' + w_offset;
			}

			function img__onmouseover (event)
			{
				var thumbnail = document.getElementById('thumbnail');
				thumbnail.style.backgroundImage = 'url(' + this.src + ')';
				thumbnail.style.opacity = '1';
				document.getElementById('coOrds').style.display = 'inline-block';

				thumbnail.style.backgroundSize = (this.offsetWidth * thumbnailZoom) + 'px ' + (this.offsetHeight * thumbnailZoom) + 'px';


				return false;
			}

			function img__onmouseout (event)
			{
				var thumbnail = document.getElementById('thumbnail');
				thumbnail.style.backgroundImage = 'none';
				thumbnail.style.opacity = '0.4';
				document.getElementById('coOrds').style.display = 'none';

				return false;
			}

			function moveThumbnail (tn)
			{
				if (tn.style.top) // nasty
				{
					tn.style.bottom = '10px';
					tn.style.left = '10px';
					tn.style.top = '';
					tn.style.right = '';
				}
				else
				{
					tn.style.top = '10px';
					tn.style.right = '10px';
					tn.style.bottom = '';
					tn.style.left = 'auto';

				}
			}
		</script>
	</head>
	<body>

		<?php

		foreach ($maps as $map)
		{
			renderMapInfo($map);
		}

		?>

		<div id="thumbnail" onmouseover="moveThumbnail(this)"><span id="coOrds"></span><div id="marker"></div></div>
	</body>
</html>