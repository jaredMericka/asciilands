<html>
	<head>
		<title>Req File Builder</title>
		<style>
			*
			{
                padding:0px;
                margin:0px;
				font-family:lucida console, monospace;
				font-size:13px;
			}

			body
			{
				background-color:#333;
				color:#888;
			}

			.consoleSprite
			{
				display:inline-block;
			}

			a
			{
				text-decoration:none;
				color:#575;
				background-color:#222;
			}

			.currentMode
			{
				color:#0f0;
			}
		</style>
	</head>
	<body onclick="document.location.reload(true);">
		<pre><span style="color:#fff;">BUILDING...</span>

<?php

$rootPath = '../';

$mapDirectory	= "{$rootPath}content/maps";
$coreDirectory	= "{$rootPath}engine/core";

///////////////////////////////////
// PART 0: Map Indexer
///////////////////////////////////


///////////////////////////////////
// PART I: Global .req file
///////////////////////////////////

$requiredPaths = [
	"{$rootPath}engine/core/constants.php",
	"{$rootPath}engine/core/constantArrays.php",
	"{$rootPath}engine/core/_constantArrays.php",
	"{$rootPath}engine/core/_soundIndex.php",
	"{$rootPath}engine/core/_mapIndex.php",
	"{$rootPath}engine/core/config.cfg",
	"{$rootPath}engine/systems/console.php",		//XXX
	"{$rootPath}engine/core/converters.php",
	"{$rootPath}engine/systems/updaters.php",
	"{$rootPath}engine/classes/Sprite.php",
	"{$rootPath}engine/core/coreSprites.spr",
	"{$rootPath}engine/classes/Scenery.php",
	"{$rootPath}engine/classes/Tile.php",
	"{$rootPath}engine/systems/Inventory.php",
	"{$rootPath}engine/systems/Boon.php",
	"{$rootPath}engine/behaviour/Behaviour.php",
	"{$rootPath}engine/systems/Mask.php",
	"{$rootPath}engine/systems/Binding.php",
	"{$rootPath}engine/bases/AsObject.php",
	"{$rootPath}engine/bases/Effect.php",
	"{$rootPath}engine/bases/Skill.php",
	"{$rootPath}engine/classes/Status.php",
	"{$rootPath}engine/bases/Dude.php",
	"{$rootPath}engine/bases/Enemy.php",
	"{$rootPath}engine/bases/NPC.php",
	"{$rootPath}engine/bases/Passive.php",
	"{$rootPath}engine/classes/Quest.php",
	"{$rootPath}engine/bases/Task.php",
	"{$rootPath}engine/classes/Player.php",
	"{$rootPath}engine/systems/Map.php",
	"{$rootPath}engine/classes/View.php",
	"{$rootPath}engine/bases/Item.php",
	"{$rootPath}engine/bases/Material.php",
	"{$rootPath}engine/systems/currencies.php",
	"{$rootPath}engine/bases/Equipment.php",
	"{$rootPath}engine/classes/Attack.php",
	"{$rootPath}engine/bases/NPCInteraction.php",
	"{$rootPath}engine/bases/AssetSet.php",
];

foreach ($requiredPaths as $path)
{
	require $path;
}

session_start();
if (isset($_SESSION['playerId']))
{
	$backup = serialize($_SESSION);
	session_destroy();
	session_start();
	$_SESSION['backup'] = $backup;
	$_SESSION['echo'] = "<span style=\"color:#fff;\">Session backed up and deleted.</span>\n\n";
	exit('<script type="text/javascript">window.location.reload();</script>');
}

if (!isset($_SESSION['echo'])) $_SESSION['echo'] = "<span style=\"color:#fff;\">No session data found. Let's get this done.</span>\n\n";

$_SESSION['debug'] = (isset($_GET['debug']) ? true : false);

if (!isset($_SESSION['globalDone']))
{
	$_SESSION['echo'] .= "<span style=\"color:#fff;\">Making global req file...</span>\n\n";
	$reqFileString = makeReqFile($requiredPaths);

	if (file_put_contents("{$rootPath}engine/reqFiles/global.req", $reqFileString))
	{
		$_SESSION['echo'] .= "<span style=\"color:#afa;\">Success!</span>\n\n";
	}
	else
	{
		$_SESSION['echo'] .= "<span style=\"color:#faa;\">Failure :(</span>\n\n";
	}

	$_SESSION['globalDone'] = true;
}

///////////////////////////////////
// MAP INDEXER
///////////////////////////////////

$mapIndexPath = "{$rootPath}engine/core/_mapIndex.php";

$indexString = "<?php\n\n";
$mapNumber = 1;
$pathArray = [];
$nameArray = [];

getMapConstants("{$rootPath}content/maps");

ob_start();

echo '$MAP_paths = ';
exportArray($pathArray);
echo ";\n\n";

echo '$MAP_names = ';
exportArray($nameArray);
echo ";\n\n";

$indexString .= "\n" . ob_get_clean();

if (file_put_contents($mapIndexPath, $indexString))
{
	echo "\n\n<span style=\"color:#aaf\">Map index saved.</span>\n\n";
}
else
{
	echo "\n\n<span style=\"color:#faa\">Write to file failed!</span>\n\n";
}


function getMapConstants ($directory)
{
	global $indexString;
	global $mapNumber;
	global $pathArray;
	global $nameArray;
	global $MAP_names;

	foreach (scandir($directory) as $file)
	{
		if ($file === '..' || $file === '.') continue;

		$path = "{$directory}/{$file}";

		if (is_dir($path))
		{
			getMapConstants($path);
			continue;
		}

		if (substr($file, -4) === '.map')
		{
			$name = substr($file, 0, -4);
			$constName = 'MAP_' . strtoupper($name);
			$path = substr($path, 0, -4);
			$path = explode('/maps/', $path)[1];

//			$indexString .= "const {$constName} = '{$path}';\n";
			$indexString .= "const {$constName} = {$mapNumber};\n";
			$pathArray[$constName] = $path;
			$nameArray[$constName] = isset($MAP_names[$mapNumber]) ? $MAP_names[$mapNumber] : $name;


			$mapNumber ++;
		}
	}
}

///////////////////////////////////
// PART II: Map .req files
///////////////////////////////////

$requiredPaths = [];

$player = new Player('TRAWLER', 'none', 0, 0, '#fff', '#000', '?', '!');

if (!isset($_SESSION['RFB']))
{
	foreach ($MAP_paths as $MAP => $path)
	{
		$_SESSION['RFB'][$MAP] = false;
	}
}

$noneLeft = true;
foreach ($_SESSION['RFB'] as $MAP => $isDone)
{
	if ($MAP === '_newMapTemplate') continue;
	if (!$isDone)
	{
		$_SESSION['echo'] .= "<span style=\"color:#faa;\">{$MAP_names[$MAP]}</span>\n";
		$noneLeft = false;
		$map = new Map($MAP);
		$reqFileString = makeReqFile($requiredPaths);
		if(file_put_contents("{$rootPath}engine/reqFiles/_{$MAP}.req", $reqFileString))
		{
			$_SESSION['echo'] .= "<span style=\"color:#afa;\">Success!</span>\n\n";
		}
		else
		{
			$_SESSION['echo'] .= "<span style=\"color:#faa;\">Failure :(</span>\n\n";
		}
		$_SESSION['RFB'][$MAP] = true;
		break;
	}
}

if ($noneLeft)
{
	if ($_SESSION['debug'])
	{
		$_SESSION['echo'] .= "<span style=\"color:#fda;\">Debug code has been preserved.</span>\n\n";
	}
	else
	{
		$_SESSION['echo'] .= "<span style=\"color:#fda;\">Debug code has been removed.</span>\n\n";
	}
	$_SESSION['echo'] .= "<span style=\"color:#fff;\">All maps have been req-tified!</span>\n\n";
	$_SESSION['echo'] .= "<span style=\"color:#faf;\">Running build scripts.</span>\n\n";

	$buildScripts = dir("{$rootPath}tools/buildScripts");
	while($buildScript = $buildScripts->read())
	{
		if ($buildScript === '.' || $buildScript === '..') continue;

		$_SESSION['echo'] .= "<span style=\"color:#faa;\">Running build script: {$buildScript}</span>\n\n";

		ob_start();

		require "{$rootPath}tools/buildScripts/{$buildScript}";

		$_SESSION['echo'] .= ob_get_clean();
	}

	$_SESSION['echo'] .= "\n<span style=\"color:#faf;\">All build scripts completed.</span>\n\n";

	$_SESSION['echo'] .= "<span style=\"color:#fff;\">Retrieving session backup...</span>\n\n";
	$backup = $_SESSION['backup'];
	$echo = $_SESSION['echo'];
	session_destroy();
	session_start();
	$_SESSION = unserialize($backup);


	$echo .= "\n<span style=\"color:#fff;\">DONE!</span>";

	//================================================
	//
	// THIS IS THE START OF THE DEBUG OUTPUT
	//
	//================================================

	$debugMode = isset($_GET['debug']);

	echo 'Rebuild in mode:<br>';
	echo "\t" . '<a ' . ($debugMode ? 'class="currentMode"' : '') . ' href="?debug">[ DEBUG ]</a> ';
	echo '<a ' . ($debugMode ? '' : 'class="currentMode"') . ' href="?">[ PRODUCTION ]</a><br><br>';

	echo $echo;
}


function __autoload($class)
{
	global $requiredPaths;

	$path = null;

	if (substr($class, 0, 2) === 'a_')
	{
		$abstract = true;
		$class = substr($class, 2);
	}
	else
	{
		$abstract = false;
	}

	$paths = [
		'obhv'	=> 'engine/behaviour/ObjectBehaviours/',
		'dbhv'	=> 'engine/behaviour/DudeBehaviours/',
		'ibhv'	=> 'engine/behaviour/ItemBehaviours/',
		'ebhv'	=> 'engine/behaviour/EquipmentBehaviours/',

		'obj'	=> 'engine/bases/objects/',
		'dude'	=> 'engine/bases/dudes/',
		'nme'	=> 'engine/bases/enemies/',
		'NPC'	=> 'engine/bases/NPCs/',

		'itm'	=> 'engine/bases/items/',
		'nvitm'	=> 'engine/bases/items/nonVariableItems/',
		'eqp'	=> 'engine/bases/equipment/',

		'tsk'	=> 'engine/bases/tasks/',
		'qres'	=> 'engine/bases/questResults/',

		'skl'	=> 'engine/bases/skills/',
		'psv'	=> 'engine/bases/passives/',

		'npci'	=> 'engine/bases/npcInteractions/',

		'set'	=> 'content/assetSets/',
	];

	$path = $paths[substr($class, 0, strpos($class, '_'))];

	if ($path)
	{
		if ($abstract)
		{
			$class = "a_{$class}";
		}

		$requiredPaths[] = "{$GLOBALS['rootPath']}{$path}{$class}.php";
		require_once "{$GLOBALS['rootPath']}{$path}{$class}.php";
		return;
	}
	else
	{
		//$_SESSION['echo'] .= "Unable to locate object \"{$class}\"! Autoload case missing.";
	}
}


function makeReqFile($pathsArray)
{
	$reqFileLines = [ '<?php', '', '// CAUTION!', '// NEVER EDIT A REQ FILE!', '' ];
	foreach($pathsArray as $path)
	{
		$fileString = file_get_contents($path);

		if (stripos($fileString, 'oosenupt') !== false)
		{
			$_SESSION['echo'] .= "<span style=\"color:#aff;\">Oosenupt</span>.\n";
		}

		$fileLines = explode("\r\n", $fileString);

		if (count($fileLines) === 1)
		{
			$fileLines = explode("\n", $fileLines[0]);
			if (count($fileLines) > 1)
				$_SESSION['echo'] .= "<span style=\"color:#afa;\">Unix EOLs detected!</span>.\n";
		}

		if (count($fileLines) === 1)
		{
			$fileLines = explode("\r", $fileLines[0]);
			if (count($fileLines) > 1)
				$_SESSION['echo'] .= "<span style=\"color:#ffa;\">Unix EOLs detected!</span>.\n";
		}

		$lineCount = count($fileLines);
		$_SESSION['echo'] .= "<div style=\"width:700px;\">Adding <span style=\"color:#aaf;\">{$path}</span> <span style=\"float:right;display:inline-block;\">{$lineCount}</span></div>";

		array_shift($fileLines);
		$reqFileLines = array_merge($reqFileLines, $fileLines);
	}


	$omissions = 0;
	if (!$_SESSION['debug'])
	{
		foreach ($reqFileLines as $index => $lineText)
		{
			if (strpos($lineText, '//XXX') !== false || strpos($lineText, 'console_echo(') !== false)
			{
				unset($reqFileLines[$index]);
				$omissions++;
			}
		}
	}

	$_SESSION['echo'] .= "<span style=\"color:#fff;\">Req file built (with {$omissions} lines omitted).</span>\n\n";
	return implode(LINE_BREAK, $reqFileLines);
}


function exportArray($array, $indent = 0)
{
	global $constants;

	indent($indent);
	echo "[\n";

	$indent ++;

	foreach ($array as $key => $value)
	{
		indent($indent);

		if (is_string($value) && !isset($constants[$value])) $value = "'{$value}'";

		echo "{$key} => ";

		if (is_array($value))
		{
			exportArray($value, 1);
			echo ",\n";
		}
		else
		{
			echo "{$value},\n";
		}
	}

	$indent--;
	indent($indent);
	echo ']';
}

function indent($n)
{
	for($i = 0; $i < $n; $i++)
	{
		echo "\t";
	}
}

?>

		</pre>
	</body>

	<?php if (!$noneLeft) { ?>
	<script type="text/javascript">window.location.reload();</script>
	<?php } ?>
</html>