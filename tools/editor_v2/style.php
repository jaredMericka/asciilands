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

.editorTool,
.editorAsset
{
	float:left;
	margin:5px;
	border-style:solid;
	border-width:2px;
	border-color:#000;
}
.clipboard:hover,
.editorTool:hover,
.editorAsset:hover
{
	background-color:#fff;
	box-shadow:0 0 15px #fff;
}

.editorAsset.scenery
{
	border-color:#f00;
}

.editorTool
{
	border-color:#000;
}

.editorAsset.north { border-top-color:#0f0; }
.editorAsset.south { border-bottom-color:#0f0; }
.editorAsset.east { border-right-color:#0f0; }
.editorAsset.west { border-left-color:#0f0; }

.editorSubHeader
{
	margin:10px;
	clear:both;
}

.clipboard
{
	display:inline-block;
	margin-left:<?php echo CHAR_WIDTH; ?>px;
	border-style:solid;
	border-width:2px;
	border-color:#fff;
}

<?php

$TPL_colours = [
	TPL_OPENGROUND => '#0f0',
	TPL_LOWOBSTACLE => '#00f',
	TPL_VERTICAL => '#ff0',
	TPL_HIGHOBSTACLE => '#f0f',
	TPL_WALL => '#f00',
];

foreach ($TPL_colours as $TPL => $colour)
{
	echo ".n{$TPL} { border-top-color:{$colour}; }";
	echo ".s{$TPL} { border-bottom-color:{$colour}; }";
	echo ".e{$TPL} { border-right-color:{$colour}; }";
	echo ".w{$TPL} { border-left-color:{$colour}; }";
	echo ".b{$TPL} { border-color:{$colour}; }";
}