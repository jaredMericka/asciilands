<?php

$rootPath = '../';

$autoLoads = 0;


require "{$rootPath}engine/core/include.php";


$direction = (isset($_POST['d']) ? $_POST['d'] : null);

$view->forceUpdate = ($view->forceUpdate || $direction !== '-1');

if ($direction !== '-1')
{
	$offsets = directionToOffset($direction);

	$playerMoved = $player->move(
		$player->n_offset + $offsets[0],
		$player->w_offset + $offsets[1]
	);
}
else
{
	$playerMoved = false;
}

if ($player instanceof Player) // As opposed to EditorPlayer	//XXX
{								//XXX
	// Apply player regeneration to health and energy.
	$player->regenerate();
}								//XXX


$view->update();

addHeader(HEADER_VIEW_HEIGHT, $view->height);
addHeader(HEADER_VIEW_WIDTH, $view->width);

addHeader(HEADER_SPRITES, $view->spriteString);

if ($playerMoved !== false || $view->forceUpdate)
{
	$view->forceUpdate = false;
	addHeader(HEADER_TILES, $view->tileString);
}

addHeader(HEADER_NEXTFRAME, $view->refreshRate * 1000);

if (!($player instanceof EditorPlayer2Player)) //...as oposed to "EditorPlayer"	//XXX
{																//XXX
	// If the player has an opponenet, update the opponent.
	if (isset($player->opponent)) update_opponent();

	// Make sure status icons are up to date
	if ($player->nextStatusCheck <= $_SERVER['REQUEST_TIME_FLOAT'])
	{
		$player->nextStatusCheck = INF;
		update_statuses();
	}
}																//XXX

$player->lastUpdated = $_SERVER['REQUEST_TIME_FLOAT'];

$updateString = '';

if (isset($_SESSION['updates']))
{
//	$updateString = json_encode(array_reverse($_SESSION['updates'])); // Array reverse, strangely, makes the updates run on the JS side in the same order as they are called on the PHP side. I don't understand this but that's what happens.
	ksort($_SESSION['updates']);
	$updateString = json_encode($_SESSION['updates']);
	unset ($_SESSION['updates']);
}
echo $updateString;







$_SESSION[$map->id] = serialize($map);


//$time = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
//addHeader(HEADER_NEXTFRAME, ($view->refreshRate * 1000) - $time);





if (DEV_MODE)
{
	console_setFrameTime();
	if (count($_SESSION['console']) > 10) unset($_SESSION['console']);
}

console_echo("<<#777>>Files loaded: {$autoLoads}<>", null, CNS_SYSTEM);	//XXX

