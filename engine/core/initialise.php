<?php

/* Close the gap to uncomment the block.

/* /

const START_MAP		= MAP_LOWERGRUBFIELDS;
//const START_N		= 130;
//const START_W		= 88;

const START_N		= 218;
const START_W		= 144;

//const START_N		= 209;
//const START_W		= 208;

/* /

const START_MAP		= MAP_EFFENDONGROVE;
const START_N		= 80;
const START_W		= 40;

//const START_N		= 24;
//const START_W		= 62;

/* /

const START_MAP		= MAP_DEBUGMENAGERIE;
const START_N		= 57;
const START_W		= 64;

/* /

const START_MAP		= MAP_DEVZOO;
const START_N		= 8;
const START_W		= 3;

/* /

const START_MAP		= MAP_GRUBTOWN;
const START_N		= 60;
const START_W		= 80;

/* /

const START_MAP		= MAP_BASILSHOUSE;
const START_N		= 5;
const START_W		= 7;

/*/

const START_MAP		= MAP_YIRRINSHOUSE;
const START_N		= 8;
const START_W		= 8;

/* /

const START_MAP		= MAP_OLDGRUBTOWN;
const START_N		= 172;
const START_W		= 202;

//*/

console_echo('<br>========== Refreshing client ==========', '#afa');		//XXX
if (empty($_SESSION)) console_echo('Session data missing! Creating new default data.', '#fda');		//XXX

$player		= null;
$map		= null;
$view		= null;

$editMode	= editorCheck();


if (!isset($_SESSION['playerId']) || !isset($_SESSION['mapId']))
{
	if ($editMode)
	{
		$player = new EditorPlayer2(START_MAP, START_N, START_W);
	}
	else
	{
		$player = new Player('Mopoke', START_MAP, START_N, START_W, '#e97', '#77f', 'o', '&Omega;');
	}

	$_SESSION['playerId'] = $player->id;

	Map::mountPlayerMap();

	$_SESSION['mapId'] = $map->id;
	$_SESSION['view'] = $view;


}
else
{
	$map = unserialize($_SESSION[$_SESSION['mapId']]);

	console_var_dump(array_keys($map->playerRegister));

	$player = &$map->objectRegister[$_SESSION['playerId']];

	$prime = false;

	if ($editMode && !($player instanceof EditorPlayer2))
	{
		$prime = true;
		console_echo('In editor mode with a normal player!', '#faa');

		$map->destroyObject($player);
		$player = new EditorPlayer2($player->MAP, $player->n_offset, $player->w_offset);
	}
	elseif (!$editMode && $player instanceof EditorPlayer2)
	{
		$prime = true;

		console_echo('In normal mode with a editor player!', '#faa');

		$map->destroyObject($player);
		$player = new Player('Mopoke', $player->MAP, $player->n_offset, $player->w_offset, '#e97', '#77f', 'o', '&Omega;');
	}

	$view = $_SESSION['view'];

	if ($prime)
	{
		$_SESSION['playerId'] = $player->id;

		foreach ($player->spriteSet as &$sprite)
		{
			$sprite = $view->addClientSprite($sprite);
		}

		$player->sprite = $view->addClientSprite($sprite);

		$map->addObjects($player);
		$view->update();

		$_SESSION['mapId'] = $map->id;
	}


}

if ($player instanceof EditorPlayer2) initialiseEditorPlayer();

//$view = new View();
$_SESSION['view'] = $view;

$_SESSION[$map->id] = serialize($map);

console_class_list($_SESSION);


handleReset();

$initTime = round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 4);
console_setFrameTime();
console_update_location();

console_echo("========== Initialise complete in {$initTime} seconds ==========<br/>", '#afa');		//XXX

function handleReset()
{
	if (!isset($_GET['reset'])) return;

	global $map;
	global $player;
	global $view;

	console_var_dump($player);

	switch (strtolower($_GET['reset']))
	{

		case 'hard':
			$sess_console	= $_SESSION['console'];

			while (!session_destroy()) usleep(mt_rand(10000, 200000));

			session_start();

			$_SESSION['console'] = $sess_console;

			console_echo('//////////// HARD RESET ////////////', '#faa');		//XXX

			break;

		case 'map':
			$map = null;
			$sess_console	= $_SESSION['console'];

			while (!session_destroy()) usleep(mt_rand(10000, 200000));

			session_start();

			$_SESSION['console'] = $sess_console;

			Map::mountPlayerMap();

			$_SESSION['playerId'] = $player->id;
//			$_SESSION['view'] = $view;

			console_echo('//////////// RESET MAP /////////////', '#faa');		//XXX
			console_class_list($_SESSION);

			break;
	}
	console_var_dump($player);

	console_setFrameTime();
	header('Location: play.php' . ($player instanceof EditorPlayer2 ? '?edit' : ''));

	session_write_close();

	EXIT();
}

function editorCheck()
{
	console_echo('### RUNNING EDITOR CHECK ###', '#ffa');

	global $rootPath;
	global $player;

	$editMode = false;

	if (isset($_GET['edit']))
	{
		switch (strtolower($_GET['edit']))
		{
			case 'false':
			case 'none':
			case 'exit':
				console_echo('Exiting edit mode.', '#faf');		//XXX
				$editMode = false;
				break;

			case '':
			case null:
//				if (isset($_GET['new']))
//				{
//					$newMapName = $_GET['new'];
//					if (!file_exists("{$rootPath}content/maps/{$newMapName}.map"))
//					{
//						$newMapTemplate = file_get_contents("{$rootPath}tools/editor_v2/_newMapTemplate.map");
//
//						file_put_contents("{$rootPath}content/maps/{$newMapName}.map", $newMapTemplate);
//						file_put_contents("{$rootPath}content/maps/{$newMapName}.mtl", ' ');
//						file_put_contents("{$rootPath}content/maps/{$newMapName}.msl", ' ');
//
//						$player->map = $newMapName;
//						$player->n_offset = 10;
//						$player->w_offset = 10;
//
//						Map::mountPlayerMap();
//					}
//					else
//					{
//						console_echo('Can\'t make a new map with the same name as an existing map!');
//					}
//				}
				console_echo('Entering edit mode at current location.', '#fff');		//XXX
				$editMode = true;
				break;
		}
	}

	if ($editMode)	console_echo('EditMode = <<#afa>>TRUE<>');
	else			console_echo('EditMode = <<#faa>>FALSE<>');

	return $editMode;
}