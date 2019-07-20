<?php if (false) { ?> <style> <?php }

$rootPath = '../../';

header('Content-type: text/css');
header('Cache-Control: must-revalidate');
$offset = 72000 ;
$ExpStr = "Expires: " . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';
header($ExpStr);

require "{$rootPath}engine/core/include.php";

$view = $_SESSION['view'];

?>
html{ padding:0px; margin:0px;overflow:hidden;color:#ccc;}
/** { font-family:lucida console, monospace; font-size:13px; cursor:default; line-height:<?php echo CHAR_HEIGHT; ?>px; text-spacing:none;}*/
pre { font-family:inherit; font-size:inherit; }

body
{
    font-family:lucida console, monospace;
    font-size:13px;
    background-color:#000;
	padding:0px;
	margin:0px;
	cursor:default;
}

#backdrop
{
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	height:100%;
	width:100%;
}

#version { position:fixed; bottom:0px; right:0px; margin-right:<?php echo CHAR_WIDTH; ?>px; }

/*******************************************************

Cursor

*******************************************************/

#cursorSprite
{
	position:absolute;
	height:<?php echo CHAR_HEIGHT * 2; ?>px;
	width:<?php echo CHAR_WIDTH * 3; ?>px;
	pointer-events:none;
	z-index:999;
}
/*
#cursorSprite .sprite,
#cursorSprite .sprite_s
{
	background:none;
}*/

/*******************************************************

Wings

*******************************************************/

.wing
{
	background-color:#000;
	position:fixed;
	height:100%;
	width:<?php echo CHAR_WIDTH * (WING_WIDTH + 2); ?>px;
	/*overflow-x:hidden;*/
}

.wingEdge
{
	position:absolute;
	background-color:#555;
	top:<?php echo CHAR_HEIGHT; ?>px;
	height:100%;
	width:<?php echo CHAR_WIDTH; ?>px;
}

#leftWing>.wingEdge
{
	right:-<?php echo CHAR_WIDTH; ?>px;
}

#rightWing>.wingEdge
{
	left:-<?php echo CHAR_WIDTH; ?>px;
}

#leftWing { left:0px; }
#rightWing { right:0px; }

/*******************************************************

Tabs

*******************************************************/

.tabBar
{
	background-color:#000;
	width:100%;
	/*margin-bottom:<?php echo CHAR_HEIGHT; ?>px;*/
	border-bottom:<?php echo CHAR_HEIGHT; ?>px solid #555;
}

.tab
{
	color:#fff;
	text-align:left;
	padding-left:<?php echo CHAR_WIDTH; ?>px;
	display:inline-block;
	width:<?php echo CHAR_WIDTH * 12; ?>px;
	background-color: #333;
	color:#ccc;
}

.tab[selected='true']
{
	color:#fff;
	background-color: #555;
}

.tab[selected='false']:hover
{
	color:#fff;
	background-color:#777;
}

.notification
{
	margin-right:<?php echo CHAR_WIDTH; ?>px;
	width:<?php echo CHAR_WIDTH; ?>px;
	float:right;
	background-color:#f80;
	color:#000;
	display:none;
}

#leftWing .tabBar { text-align:left; }
#rightWing .tabBar { text-align:right; }
#leftWing .tabBar .tab { margin-left:<?php echo CHAR_WIDTH; ?>px; }
#rightWing .tabBar .tab { margin-right:<?php echo CHAR_WIDTH; ?>px; }

.tabBody
{
	position:absolute;
	top:<?php echo CHAR_HEIGHT * 2; ?>px;
	bottom:0px;
	overflow-y:scroll;
	/*width:<?php echo WING_WIDTH * CHAR_WIDTH; ?>px;*/
	width:100%;
}


/*******************************************************

Panels

*******************************************************/

.panel
{
	display:block;
    position:relative;
    /*width:<?php // echo CHAR_WIDTH * WING_WIDTH; ?>px;*/
    padding:0px <?php echo CHAR_WIDTH; ?>px;
}

.panel .button
{
	color:#000;
	background-color:#777;
	padding:0px <?php echo CHAR_WIDTH; ?>px;
}

.panel .button:hover { background-color:#999; }
.panel .button:active { background-color:#bbb; }
.panel .fade { color:#555; }

.panelHeader
{
	background-color:#336;
	color:#fff;
    position:relative;
    padding:0px <?php echo CHAR_WIDTH; ?>px;
}

.subPanel { background-color:#321; }
.subPanelHeader { color:#fff; background-color:#642; }
.subPanel .fade { color:#753; }

.subPanel, .subPanelHeader
{
	padding:0px <?php echo CHAR_WIDTH; ?>px;
	margin:0px -<?php echo CHAR_WIDTH; ?>px;
}

.indicator { color:#fff; }

#leftWing .panel { margin: 0px auto 0px 0px; }
#rightWing .panel { margin: 0px 0px 0px auto; }

.pi	// Panel Item
{
	position:relative;
	background-color:inherit;
}

.pi:hover
{
	color:#fff;
	background-color:#400;
}

.pi .bound
{
	background-color:#555;
	color:#000;
}

.subPanel .pi .bound
{
	background-color:#753;
	color:#321;
}

.pi:hover .bound
{
	background-color:#fff;
	color:#400;
}

.pi:active { background-color:#600; }
.pi:hover .fade { color:#a33; }


/*******************************************************

Stats

*******************************************************/

.statWorking { display:none; }
.statDiv:hover .statWorking { display:inline; }
.statDiv:hover { color:inherit; }

/*******************************************************

Big stuff

*******************************************************/

#overlay
{
	position:fixed;
	background-color:#000;
	height:100%;
	width:100%;
	opacity:0.0;
	pointer-events:none;
}

#mapContainer
{
	position:absolute;
	top:50%;
	margin-top:-<?php echo $view->height * CHAR_HEIGHT; ?>px;
	left:50%;
	margin-left:-<?php echo ceil($view->width * 1.5 * CHAR_WIDTH); ?>px;
	width:<?php echo $view->width * 3 * CHAR_WIDTH; ?>px;
	height:<?php echo $view->height * 2 * CHAR_HEIGHT; ?>px;
}

#<?php echo UPD_CONVERSATION; ?>
{
	background:#000;
	padding:0px <?php echo CHAR_WIDTH; ?>px;
	border-bottom:<?php echo CHAR_HEIGHT; ?>px solid #555;
	border-right:<?php echo CHAR_WIDTH; ?>px solid #555;
	border-left:<?php echo CHAR_WIDTH; ?>px solid #555;
	position:fixed;
	/*text-align:center;*/
	overflow:hidden;
	top:0px;
	left:<?php echo CHAR_WIDTH * (WING_WIDTH + 5); ?>px;
	right:<?php echo CHAR_WIDTH * (WING_WIDTH + 5); ?>px;
	min-height:<?php echo CHAR_HEIGHT * 3; ?>px;
}


#<?php echo UPD_CONVERSATION; ?>:hover { overflow:visible; }

#<?php echo UPD_CONVERSATION; ?>:hover>#commLast { display:none; }
#<?php echo UPD_CONVERSATION; ?>:hover>#commStream { display:block; }

#commLast { display:block; }
#commStream { display:none; }

#commStream>div { margin-bottom:<?php echo CHAR_HEIGHT; ?>px; }

#mouseTrap
{
	position:absolute;
	left:0px;
	right:0px;
	top:100%;
	bottom:-<?php echo CHAR_HEIGHT * 8;?>px;
}

#<?php echo UPD_BINDINGS; ?>
{
	background:#000;
	position:fixed;
	bottom:0px;
	left:<?php echo CHAR_WIDTH * WING_WIDTH; ?>px;
	right:<?php echo CHAR_WIDTH * WING_WIDTH; ?>px;
	height:<?php echo CHAR_HEIGHT * 3; ?>px;
	/*text-align:center;*/
	/*overflow:hidden;*/
	padding:0px <?php echo CHAR_WIDTH * 4; ?>px;
	margin:0px <?php echo CHAR_WIDTH * 3; ?>px;

	border-top:<?php echo CHAR_HEIGHT; ?>px solid #555;
}

#<?php echo UPD_STATUS; ?>
{
	position:fixed;
	bottom:<?php echo CHAR_HEIGHT * 13; ?>px;
	left:<?php echo CHAR_WIDTH * (WING_WIDTH + 4); ?>px;
}

#<?php echo UPD_STATUS; ?> .statusSprite
{
	margin-bottom:<?php echo CHAR_HEIGHT; ?>px;
}

/*******************************************************

HP & EP

*******************************************************/

#epContainer,
#hpContainer
{
	position:fixed;

	background-color:#333;

	border-style:solid;
	border-color:#555;

	border-width:<?php echo CHAR_HEIGHT; ?>px <?php echo CHAR_WIDTH; ?>px;

	bottom:<?php echo CHAR_HEIGHT; ?>px;
	width:<?php echo CHAR_WIDTH * 3; ?>px;
	height:<?php echo CHAR_HEIGHT * 10; ?>px;
}

#hpContainer
{
	border-right:<?php echo CHAR_WIDTH; ?>px solid #555;
	left:<?php echo CHAR_WIDTH * (WING_WIDTH + 2); ?>px;
}

#epContainer
{
	border-left:<?php echo CHAR_WIDTH; ?>px solid #555;
	right:<?php echo CHAR_WIDTH * (WING_WIDTH + 2); ?>px;
}

#hp,
#ep
{
	position:absolute;
	bottom:0px;
	left:0px;
	right:0px;
	/*height:<?php echo CHAR_HEIGHT * 8; ?>px;*/
}

#hp { background-color:#800; }
#ep { background-color:#a80; }

#hpLabel,
#epLabel
{
	position:fixed;
	bottom:0px;
	/*width:<?php // echo CHAR_WIDTH * 4; ?>px;*/
}

#hpLabel
{
	left:<?php echo CHAR_WIDTH * (WING_WIDTH + 3); ?>px;
}

#epLabel
{
	text-align:right;
	right:<?php echo CHAR_WIDTH * (WING_WIDTH + 3); ?>px;
}


/*******************************************************

Bindings

*******************************************************/

.binding_open,
.bindingEmpty,
.binding
{
	float:left;
	margin-top:-<?php echo CHAR_HEIGHT; ?>px;
	margin-left:<?php echo CHAR_WIDTH; ?>px;
	margin-right:<?php echo CHAR_WIDTH; ?>px;
	width:<?php echo CHAR_WIDTH * 3; ?>px;
	height:<?php echo CHAR_HEIGHT * 3; ?>px;
}

.emptyBindingSprite { color:#555; }
.openBindingSprite { color:#5f5; }

.binding_open .emptyBindingSprite { color:#888; }

.openBindingSprite { display:none; }

.binding_open:hover .sprite,
.binding_open:hover .sprite_s,
.binding_open:hover .emptyBindingSprite { display:none; }
.binding_open:hover .openBindingSprite { display:block; }

/*******************************************************

Miscellaneous

*******************************************************/

.commText
{
	margin-left:<?php echo CHAR_WIDTH * 4; ?>px;
}

.commText>.body
{
	padding:<?php echo CHAR_HEIGHT . 'px ' . CHAR_WIDTH . 'px'; ?>;
}

.commText>.pages { position:relative; }

.commText>.pages>*
{
	padding:0px <?php echo CHAR_WIDTH; ?>px;
}


#textBody
{
	padding:<?php echo CHAR_HEIGHT . 'px ' . CHAR_WIDTH . 'px'; ?>;
	margin:<?php echo CHAR_HEIGHT . 'px 0px'; ?>;
}

#textPageNumber{ margin-left:<?php echo CHAR_WIDTH; ?>px; }


.convLine
{
	margin-top:<?php echo CHAR_HEIGHT; ?>px;
}

.bar
{
	position:relative;
	height:<?php echo CHAR_HEIGHT; ?>px;
	background-color:#777;
	width:100%;
}

.bar div
{
	position:absolute;
	top:0px;
	bottom:0px;
	left:0px;
	right:auto;
}

.sprite_s,
.sprite
{
	height:<?php echo 2 * CHAR_HEIGHT ?>px;
	width:<?php echo 3 * CHAR_WIDTH ?>px;
	background-color:transparent;
}

.sprite_s:hover,
.sprite:hover
{
	background-color:#fff;
}

.fail
{
	color:#f88;
}



/*******************************************************

Alignment things

*******************************************************/
.ra
{
    display:inline-block;
    float:right;
}

.raa
{
    display:inline-block;
    position:absolute;
	right:0px;
}

.rah
{
	display:block;
	position:absolute;
	right:<?php echo CHAR_WIDTH; ?>px;
	top:<?php echo CHAR_HEIGHT; ?>px;
}

.la
{
    display:inline-block;
    float:left;
}

.cap
{
	text-transform:capitalize;
}


/*******************************************************

General use colours

*******************************************************/

.white		{ color:#fff; }

.red		{ color:#faa; }
.green		{ color:#afa; }
.blue		{ color:#aaf; }
.cyan		{ color:#aff; }
.yellow		{ color:#ffa; }
.magenta	{ color:#faf; }
.gray		{ color:#aaa; }

._red		{ color:#f55; }
._green		{ color:#5f5; }
._blue		{ color:#55f; }
._cyan		{ color:#5ff; }
._yellow	{ color:#ff5; }
._magenta	{ color:#f5f; }
._gray		{ color:#555; }

/*******************************************************

Scroll bar stuffs

*******************************************************/

::-webkit-scrollbar
{
    width: <?php echo CHAR_WIDTH ?>px;
    height: <?php echo CHAR_HEIGHT ?>px;
}

::-webkit-scrollbar-button:vertical:increment,
::-webkit-scrollbar-button:vertical:decrement
{
	height: <?php echo CHAR_HEIGHT ?>px;
    background-color:#666;
}

/* Track below and above */
::-webkit-scrollbar-track-piece
{
    /*background-color:#777;*/
}

/* The thumb itself */
::-webkit-scrollbar-thumb:vertical
{
	display:block;
    max-height: <?php echo CHAR_HEIGHT ?>px;
	background-color:#444;
}









<?php if (!DEV_MODE) exit(); ?>

/******************************************\
	DEV_MODE stuff
\******************************************/

#DEV_MODE
{
	position:absolute;
	bottom:10px;
	left:10px;
	background-color:#060;
	opacity:0.1;
	padding:5px;
	border-radius:6px;
}

#DEV_MODE:hover
{
	opacity:1;
}

<?php if ($player instanceof EditorPlayer) { // EDITOR LINE ?>

.editorScenery,
.editorTile
{
	display:inline-block;

	margin-top:<?php echo CHAR_HEIGHT; ?>px;
	margin-left:<?php echo CHAR_WIDTH; ?>px;
}

.editorTile:hover
{
/*	background-color:#bbb !important;
	color:#777 !important;*/
box-shadow:inset 0 0 10px #f00;
}

.editorTile:active
{
	background-color:#555 !important;
	color:#999 !important;
}

.editorScenery:hover
{
	background-color:#fff;
}

.editorButton
{
	display:inline-block;
	background-color:#500;
	color:#fff;
	padding:0px <?php echo CHAR_WIDTH; ?>px;
	margin-top:<?php echo CHAR_HEIGHT; ?>px;
}

.editorButton:hover
{
	background-color:#f00;
}

.editorButton:active
{
	background-color:#f99;
	color:#000;
}

.editorText
{
	padding:<?php echo CHAR_HEIGHT . 'px ' . CHAR_WIDTH . 'px'; ?> 0px;
}

.editorButtons
{
	text-align:right;
	position:fixed;
	bottom:<?php echo CHAR_HEIGHT; ?>px;
	right:<?php echo CHAR_WIDTH; ?>px;
}

<?php }

if ($player instanceof EditorPlayer2) { ?>



<?php }
