<?php

$materialsPath = "{$rootPath}content/materials";
$materialFiles = scandir($materialsPath);

$fileString = "<?php\n";

foreach ($materialFiles as $materialFile)
{
	if (substr($materialFile, 0, 7) === 'common_')
	{
		include "{$materialsPath}/{$materialFile}";
		$fileString .= "\ninclude \"{\$rootPath}/content/materials/{$materialFile}\";";
	}
}

$fileString .= "\n\n\$commonMaterials = [\n";

$vars = get_defined_vars();
foreach ($vars as $name => $value)
{
	if ($value instanceof Material)
	{
		echo "Adding {$name}\n";
		$fileString .= "\t\${$name},\n";
	}
}

$fileString .= '];';

if (file_put_contents("{$rootPath}engine/core/_commonMaterialArray.php", $fileString))
{
	echo "\n\n<span style=\"color:#afa\">Array built and saved successfully!</span>\n\n";
}
else
{
	echo "\n\n<span style=\"color:#faa\">Array built but the save is a shambles</span>\n\n";
}