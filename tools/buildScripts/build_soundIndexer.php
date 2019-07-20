<?php

$soundIndexPath = "{$rootPath}engine/core/_soundIndex.php";

$files = dir("{$rootPath}content/sounds/");

echo "<span style=\"color:#fda\">Gathering sound files...</span>";

$constants = [];
$fileNames = [];

while($file = $files->read())
{
//	if ($file === '.' || $file === '..') continue;
	if (strpos(strtolower($file), '.wav') === false) continue;

	$name = explode('.', $file)[0];
	$constant = strtoupper("SND_{$name}");

	$constants[] = $constant;
	$fileNames[$constant] = $file;

	echo "\n\t{$file} <span style=\"color:#fff\">-></span> <span style=\"color:#afa\">{$name}</span> <span style=\"color:#fff\">-></span> <span style=\"color:#ffa\">{$constant}</span> <span style=\"color:#fff\">-></span> <span style=\"color:#faf\">"
	. array_search($constant, $constants) . '</span>';
}

ob_start();

echo "<?php\n";

foreach ($constants as $index => $constant)
{
	echo "\nconst {$constant} = {$index};";
}

echo "\n\n\$SND_files = ";
exportArray($fileNames);
echo ';';

if (file_put_contents($soundIndexPath, ob_get_clean()))
{
	echo "\n\n<span style=\"color:#aaf\">Sound index saved.</span>\n\n";
}
else
{
	echo "\n\n<span style=\"color:#faa\">Write to file failed!</span>\n\n";
}