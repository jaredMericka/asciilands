<?php
header("content-type: application/javascript");

$rootPath = '../../';
require "{$rootPath}engine/core/include.php";

//echo $map->js;

echo 'var tileKey = ' . json_encode($map->tileKeyJson, JSON_PRETTY_PRINT) . ";\n";
echo 'var spriteKey = ' . json_encode(array_merge($map->spriteKeyJson, $view->clientSpriteJson), JSON_PRETTY_PRINT) . ';';

