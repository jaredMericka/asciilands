<?php if (false) { ?> <script> <?php }
header("content-type: application/javascript");

$rootPath = '../../';
require "{$rootPath}engine/core/include.php";

?>

var moveDirection = -1;
var moveRepeater;
var isIdle = true;
//var idleInterval = <?php // echo ($view->refreshRate *  1000); ?> ;
//var idleTimer;
var toIdle;
var directions = [
	<?php echo DIR_NORTH;?>,
	<?php echo DIR_SOUTH;?>,
	<?php echo DIR_WEST;?>,
	<?php echo DIR_EAST;?>
];
var pressedDIRs = [false, false, false, false];

var ctrlDown = false;
var altDown = false;

var map;
var mapHover = false;
var cursorSprite;

var allowIdle = true;

function initialise()
{
	map = document.getElementById('map');
	backdrop = document.getElementById('backdrop');
	cursorSprite = document.getElementById('cursorSprite');
	document.onmousemove = mouseMove;

	<?php if (!($player instanceof EditorPlayer || $player instanceof EditorPlayer2)) { ?>

	createTab('leftWing');
	createTab('rightWing');
	createPanelHeaders();

	<?php } ?> ;

	getNewView(-1);
	notifyServer('init',0,0);

	map.onclick = function (e) { mapClick(e, false); };
	map.oncontextmenu = function (e) { mapClick(e, true); };

	backdrop.onclick = function (e) { mapClick(e, false); };
	backdrop.oncontextmenu = function (e) { mapClick(e, true); };

	window.onblur = function ()
	{
		pressedDIRs = [false, false, false, false];
		clearInterval(moveRepeater);
		ctrlDown = false;
		altDown = false;
	};

	initPlayerPanel();
}

function mapClick(e, rightClick)
{
	var click;

//	if (rightClick)
//	{
//		if (altDown)
//		{
//			click = ctrlDown ? UIN_CTRL_ALT_RIGHT_CLICK : UIN_ALT_RIGHT_CLICK;
//		}
//		else
//		{
//			click = ctrlDown ? UIN_CTRL_RIGHT_CLICK : UIN_RIGHT_CLICK;
//		}
//	}
//	else
//	{
//		if (altDown)
//		{
//			click = ctrlDown ? UIN_CTRL_ALT_CLICK : UIN_ALT_CLICK;
//		}
//		else
//		{
//			click = ctrlDown ? UIN_CTRL_CLICK : UIN_CLICK;
//		}
//	}

	click = (rightClick ? 200 : 100) + (ctrlDown ? 10 : 0) + (altDown ? 1 : 0);

	notifyServer('MAP_KEY', click, mapHover);
}

function mouseMove(e)
{
	cursorSprite.style.top = (e.clientY - <?php echo CHAR_HEIGHT; ?>) + 'px';
	cursorSprite.style.left = (e.clientX - <?php echo CHAR_WIDTH * 1.5; ?>) + 'px';

	var leftOffset = e.clientX - map.offsetParent.offsetLeft;
	var topOffset = e.clientY - map.offsetParent.offsetTop;

	if (leftOffset < 0 ||
		topOffset < 0 ||
		leftOffset > map.offsetParent.offsetWidth ||
		topOffset > map.offsetParent.offsetHeight )
	{
		mapHover = false;
		document.getElementById('mapHover').innerHTML = 'Off';
		return;
	}

	var tileWidth = map.offsetWidth / viewWidth;
	var tileHeight = map.offsetHeight / viewHeight;

	mapHover = Math.floor(topOffset / tileHeight) + ',' + Math.floor(leftOffset / tileWidth);
	document.getElementById('mapHover').innerHTML = mapHover;
}

function initPlayerStatus()
{
	var psDiv = document.getElementById('<?php echo UPD_PLAYER_STATUS; ?>');

	psDiv.appendChild(document.createTextNode('Life:'));
	psDiv.appendChild(drawBar('hp', <?php echo $player->hp; ?>, 100, '#d22'));
}

function idle() { if(isIdle && !viewWait) getNewView(-1); }

document.onkeydown = function(e)
{
    //e = e || event; // "real browsers" || IE6/7.
	var direction = -1;
	var pd;

    switch (e.keyCode)
    {
        case 87: // W
        case 38: // up
            direction = <?php echo DIR_NORTH; ?>;
			pd = 0;
            break;
        case 83: // S
        case 40: // down
            direction = <?php echo DIR_SOUTH; ?>;
			pd = 1;
            break;
        case 65: // A
        case 37: // left
            direction = <?php echo DIR_WEST; ?>;
			pd = 2;
            break;
        case 68: // D
        case 39: // right
            direction = <?php echo DIR_EAST; ?>;
			pd = 3;
            break;

		case 17: // ctrl
			ctrlDown = true;
			break;
		case 18: // alt
			altDown = true;
			break;

		case 73: // I
			document.getElementById('Items').show();
			break;
		case 75: // K
			document.getElementById('Skills').show();
			break;
		case 78: // N
			document.getElementById('NPC').show();
			break;
//		case 79: // O
//			document.getElementById('Opponent').show();
//			break;
		case 80: // P
			document.getElementById('Player').show();
			break;
		case 84: // T
			document.getElementById('Stats').show();
			break;
		case 81: // Q
			document.getElementById('Quests').show();
			break;

		case 48: //0
		case 49: //1
		case 50: //2
		case 51: //3
		case 52: //4
		case 53: //5
		case 54: //6
		case 55: //7
		case 56: //8
		case 57: //9
			notifyServer('MAP_KEY', e.keyCode - 48, mapHover);
			break;

		case 96: //0
		case 97: //1
		case 98: //2
		case 99: //3
		case 100: //4
		case 101: //5
		case 102: //6
		case 103: //7
		case 104: //8
		case 105: //9
			notifyServer('MAP_KEY', e.keyCode - 96, mapHover);
			break;

		case 13: // Enter
		case 32: // Space
			if (!allowIdle) getNewView(-1);
			break;

		default:
			// This means that if idling is turned off, you can force a frame with the press of any key.

			return;
			break;

    }

	if (pressedDIRs[pd] === true) return true;

	if (direction !== -1)
	{
		e.preventDefault;
		pressedDIRs[pd] = true;
		move(direction);
	}
	return false;
};

document.onkeyup = function(e)
{
	var direction = -1;
	var pd;

	switch (e.keyCode)
    {
        case 87: // W
        case 38: // up
            direction = <?php echo DIR_NORTH; ?>;
			pd = 0;
            break;
        case 83: // S
        case 40: // down
            direction = <?php echo DIR_SOUTH; ?>;
			pd = 1;
            break;
        case 65: // A
        case 37: // left
            direction = <?php echo DIR_WEST; ?>;
			pd = 2;
            break;
        case 68: // D
        case 39: // right
            direction = <?php echo DIR_EAST; ?>;
			pd = 3;
            break;

		case 17: // ctrl
			ctrlDown = false;
			break;
		case 18: // alt
			altDown = false;
			break;

		case 77:
			openMinimap();
			break;

<?php if (DEV_MODE) { // BOTH DEV AND EDIT MODE ?>
        case 192: // ~
            openConsole();
            break;
		case 72: // H
			toggleWings();
			break;
        case 82: // R
<?php if ($player instanceof EditorPlayer || $player instanceof EditorPlayer2) { // EDITOR ONLY ?>
            window.location = "play.php?edit&reset=map";
<?php } else { ?>
            window.location = "play.php?reset=map";
<?php } ?>
            break;
		case 70: // F
            window.location = "play.php?reset=hard";
            break;
<?php if ($player instanceof EditorPlayer || $player instanceof EditorPlayer2) { // EDITOR ONLY ?>
		case 69: // e
			window.location = "play.php?edit=false";
			break;
		case 27: // esc
//			notifyServer('EDITOR_TILE', UIN_CLICK, 'NONE');
//			document.getElementById('CURRENT').innerHTML = '';
			break;
<?php } else { ?>	// DEV MODE ONLY, NOT EDIT MODE
		case 69: // e
			window.location = "play.php?edit";
			break;

	case 187: //=
		allowIdle = !allowIdle;
		break;

<?php }} ?>
    }

	if (direction !== -1)
	{
		pressedDIRs[pd] = false;
		if (direction !== moveDirection) return false;
		clearInterval(moveRepeater);

		var newDirection = getNewDirection();

		if (newDirection !== -1)
		{
//			if (allowIdle) move(newDirection);
			move(newDirection);
			return false;
		}

		moveDirection = -1;

<?php if (!($player instanceof EditorPlayer)) { ?>
		if (allowIdle) toIdle = setTimeout('isIdle=true;idle();',200);
<?php } ?>
	}

	<?php if ($player instanceof EditorPlayer) { ?>
		getNewView(-1);
	<?php } ?>
};

function move(direction)
{
    clearTimeout(toIdle);
    isIdle = false;
    if (direction === moveDirection) return false;
    moveDirection = direction;

    getNewView(direction);
	clearInterval(moveRepeater);
    moveRepeater = setInterval('getNewView(' + direction + ')',200);

}

function getNewDirection()
{
    for (index in pressedDIRs)
	{
		if (pressedDIRs[index] === true) return directions[index];
	}
    return -1;
}


function isFunction(funcName)
{
	return (typeof window[funcName]==='function');
}

function initPlayerPanel ()
{
	<?php if (!($player instanceof EditorPlayer2)) { ?>

	var playerPanel = document.getElementById('<?php echo UPD_PLAYER_INFO; ?>');
	playerPanel.setAnnex('<?php echo htmlentities($player->name, ENT_QUOTES); ?>');

	var levelDiv = document.createElement('div');
	levelDiv.appendChild(document.createTextNode('Level: '));

	var charLevel = document.createElement('span');
	charLevel.id = 'charLevel';
	charLevel.appendChild(document.createTextNode('<?php echo $player->level; ?>'));

	levelDiv.appendChild(charLevel);

	playerPanel.appendChild(levelDiv);
	playerPanel.appendChild(document.createElement('br'));

	var xpBar = drawBar('<?php echo UPD_XP; ?>', 0, 1, '#080');

	playerPanel.appendChild(document.createTextNode('Experience:'));
	playerPanel.appendChild(xpBar);

	<?php } ?>
}



<?php if (DEV_MODE) { ?>

function openConsole()
{
    window.open(
		'engine/systems/console.php?console=window',
		'Asciilands console',
		'height=700,width=500,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,status=no'
	);
}

function openMinimap()
{
	window.open(
		'minimap.php',
		'Asciilands minimap',
		'height=600,width=800,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,status=no'
	);
}

window.onerror = function(desc,page,line,chr)
{
	page = page.split('/');
	page = page[page.length - 1];

	string = page + ' - ' + line + ':<br>' + desc;
	console_echo(string,'#faa');
};

conWait = false;

function console_echo(string, colour)
{
	if (conWait) return 0;
	conWait = true;

	conRequest = new XMLHttpRequest();

	conRequest.open("POST","ajax/sendToConsole.php","true");
	conRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	conRequest.send('s='+string+'&c='+colour);

	conRequest.onreadystatechange = function() { conWait = false; };
}

document.oncontextmenu = function()
{
	return false;
	return ctrlDown;
};

var wingsOn = true;
function toggleWings()
{
	var wings = document.getElementsByClassName('wing');

	for (var i = 0; i <= 1; i++)
	{
		if (wingsOn)
		{
			wings[i].style.display = 'none';
		}
		else
		{
			wings[i].style.display = 'block';
		}
	}

	document.getElementById("<?php echo UPD_CONVERSATION; ?>").style.display	= wingsOn ? 'none' : 'block';
	document.getElementById("<?php echo UPD_STATUS; ?>").style.display			= wingsOn ? 'none' : 'block';
	document.getElementById("hpContainer").style.display						= wingsOn ? 'none' : 'block';
	document.getElementById("hpLabel").style.display							= wingsOn ? 'none' : 'block';
	document.getElementById("epContainer").style.display						= wingsOn ? 'none' : 'block';
	document.getElementById("epLabel").style.display							= wingsOn ? 'none' : 'block';
	document.getElementById("<?php echo UPD_BINDINGS; ?>").style.display		= wingsOn ? 'none' : 'block';

	wingsOn = !wingsOn;
}

<?php }