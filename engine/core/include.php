<?php

require "{$rootPath}engine/reqFiles/global.req";
//require "{$rootPath}engine/systems/access.php";

if (isset($_POST['m'])) include "{$rootPath}engine/reqFiles/_{$_POST['m']}.req";

if (DEV_MODE) require "{$rootPath}tools/editor_v2/EditorPlayer2.php"; // EDITOR LINE

session_start();

if (!isset($_SESSION['playerId'])
	||	!isset($_SESSION['mapId'])
	||	!isset($_SESSION['view']))
{
	if (!isset($runningFromIndex) || $runningFromIndex !== true)
	{
		echo (isset($_SESSION['playerId'])	? "Player ID: {$_SESSION['playerId']}\n": "Player ID missing\n");
		echo (isset($_SESSION['mapId'])		? "Map ID: {$_SESSION['mapId']}\n": "Map ID missing\n");
		echo (isset($_SESSION['view'])		? "There's a view here.\n": "No view.\n");

		console_echo('Missing map ID, player ID or View and this isn\'t the index.');		//XXX
		die('Refreshing might help.');
	}
}
else
{
	$map = unserialize($_SESSION[$_SESSION['mapId']]);
	$player = &$map->objectRegister[$_SESSION['playerId']];
	$view = $_SESSION['view'];
}

console_setWakeTime();

function __autoload($class)
{
	global $autoLoads;
	$autoLoads ++;

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
		'obhv'	=> 'engine/behaviour/objectBehaviours/',
		'dbhv'	=> 'engine/behaviour/dudeBehaviours/',
		'ibhv'	=> 'engine/behaviour/itemBehaviours/',
		'ebhv'	=> 'engine/behaviour/equipmentBehaviours/',

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

		require "{$GLOBALS['rootPath']}{$path}{$class}.php";
		return;
	}
	else	//XXX
	{		//XXX
		console_echo("Unable to locate object \"{$class}\"! Autoload case missing.");		//XXX
	}		//XXX
}
