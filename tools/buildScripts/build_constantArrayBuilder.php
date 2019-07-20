<?php

$constantArraysPath = "{$rootPath}engine/core/_constantArrays.php";

$constants = get_defined_constants();

$working_DS_names			= [];
$working_DS_defaults		= [];
$working_DS_descriptions	= [];

$working_DS_types			= [];
$working_DS_types_core		= [];
$working_DS_types_subs		= [];

$working_DS_typed			= [];
$working_DS_global			= [];

$working_DS_prefixes		= [];
$working_DS_suffixes		= [];

$working_DMG_prefixes		= [];
$working_DMG_suffixes		= [];

$working_DMG_names			= [];
$working_DMGDL_names		= [];
$working_DMG_DMGDL_names	= [];

$working_TEQ_names			= [];
$working_TEQT_names			= [];

$working_SKLS_values		= [];

$temp_DS_types				= [];

foreach ($constants as $constant => $value)
{
	$constantParts = explode('_', $constant, 2);

	if (!isset($constantParts[1])) continue;

	$constantType = $constantParts[0];
	$constantName = strtolower($constantParts[1]);

	switch ($constantType)
	{
		case 'DMGDL':
			echo "\n Allocating $constant";

			$working_DMGDL_names[$constant] = $constantName;

			break;

		case 'DMG':
			echo "\n Allocating $constant";

			$working_DMG_names[$constant] = $constantName;

			$working_DMG_prefixes[$value] = $DMG_prefixes[$value] ? $DMG_prefixes[$value] : 'PREFIX_MISSING';
			$working_DMG_suffixes[$value] = $DMG_suffixes[$value] ? $DMG_suffixes[$value] : 'SUFFIX_MISSING';

			break;

		case 'TEQ':
			echo "\n Allocating $constant";
			$working_TEQ_names[$constant] = isset($TEQ_names[$value]) ? $TEQ_names[$value] : $constantName;
			break;

		case 'TEQT':
			echo "\n Allocating $constant";
			$working_TEQT_names[$constant] = isset($TEQT_names[$value]) ? $TEQT_names[$value] : $constantName;
			break;

		case 'DS':
			echo "\n Allocating $constant";

			$working_DS_names[$constant] = isset($DS_names[$value]) ? $DS_names[$value] : $constantName;

			if ($value >= 0 && $value < 50)
			{
				$working_DS_global[$value] = $constant;
			}

			if ($value > 99 && $value < 1000)
			{
				$working_DS_typed[$value] = $constant;
			}

			if ($value > 0 && $value <= 500 && $value % 100 === 0)
			{
				$working_DS_types[$constant] = [];
				$temp_DS_types[$value] = $constant;
			}
			else
			{
				$baseType = $temp_DS_types[$value - ($value % 100)];
				if (isset($working_DS_types[$baseType]))
				{
					$working_DS_types[$baseType][$value] = $constant;

					if ($value % 100 < 50)
					{
						$working_DS_types_core[$baseType][$value] = $constant;
					}
					else
					{
						$working_DS_types_subs[$baseType][$value] = $constant;
					}
				}
			}

			$working_DS_descriptions[$constant] = isset($DS_descriptions[$value]) ? $DS_descriptions[$value] : 'DESCRIPTION_MISSING';
			$working_DS_prefixes[$constant] = isset($DS_prefixes[$value]) ? $DS_prefixes[$value] : 'PREFIX_MISSING';
			$working_DS_suffixes[$constant] = isset($DS_suffixes[$value]) ? $DS_suffixes[$value] : 'SUFFIX_MISSING';
			$working_DS_defaults[$constant] = isset($DS_defaults[$value]) ? $DS_defaults[$value] : 100;

			break;
		case 'SKLS':
			echo "\n Allocating $constant";

			$working_SKLS_values[$constant] = $value;

			break;
	}
}

$working_DMG_DMGDL_names	= $working_DMG_names + $working_DMGDL_names;

ob_start();

echo '<?php';

echo "\n\n// This is an auto-generated file. Don't mess with it.\n// If changes must be made, they should be made to tools/buildScripts/createArrayBuilder.php";

echo "\n\n\$DS_names = ";
exportArray($working_DS_names)	;
echo ';';

echo "\n\n\$DS_defaults = ";
exportArray($working_DS_defaults)	;
echo ';';

echo "\n\n\$DS_descriptions = ";
exportArray($working_DS_descriptions)	;
echo ';';

echo "\n\n\$DS_typed = ";
exportArray($working_DS_typed);
echo ';';

echo "\n\n\$DS_types = ";
exportArray($working_DS_types);
echo ';';

echo "\n\n\$DS_types_core = ";
exportArray($working_DS_types_core);
echo ';';

echo "\n\n\$DS_types_subs = ";
exportArray($working_DS_types_subs);
echo ';';

echo "\n\n\$DS_global = ";
exportArray($working_DS_global);
echo ';';

echo "\n\n\$DS_prefixes = ";
exportArray($working_DS_prefixes);
echo ';';

echo "\n\n\$DS_suffixes = ";
exportArray($working_DS_suffixes);
echo ';';

echo "\n\n\$DMGDL_names = ";
exportArray($working_DMGDL_names);
echo ';';

echo "\n\n\$DMG_names = ";
exportArray($working_DMG_names);
echo ';';

echo "\n\n\$DMG_DMGDL_names = ";
exportArray($working_DMG_DMGDL_names);
echo ';';

echo "\n\n\$DMG_prefixes = ";
exportArray($working_DMG_prefixes);
echo ';';

echo "\n\n\$DMG_suffixes = ";
exportArray($working_DMG_suffixes);
echo ';';

echo "\n\n\$TEQT_names = ";
exportArray($working_TEQT_names);
echo ';';

echo "\n\n\$TEQ_names = ";
exportArray($working_TEQ_names);
echo ';';

echo "\n\n\$SKLS_values = ";
exportArray($working_SKLS_values);
echo ';';


if (file_put_contents($constantArraysPath, ob_get_clean()))
{
	echo $echo;

	echo "\n\n<span style=\"color:#aaf\">Constant arrays saved.</span>\n\n";
}
else
{
	echo $echo;

	echo "\n\n<span style=\"color:#faa\">Write to file failed!</span>\n\n";
}

