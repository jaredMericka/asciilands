<?php

$rootPath = '../';

if (isset($_GET['map'])) $map = $_GET['map']; else $map = null;

$mslChars = str_split('~|!@#$%^&*()-+=:<>?');
shuffle($mslChars);

$newLineChar = "\r\n";

if (is_file("{$rootPath}content/maps/{$map}.mtl"))
{
    $mtlString = file_get_contents("{$rootPath}content/maps/{$map}.mtl");
    $mtlString = str_replace($newLineChar, ']', $mtlString);

    $mtlRows = explode(']', $mtlString);

    $mslKey = array();
    $keyIndex = 0;
	foreach ($mtlRows as &$row)
	{
		$row = str_split($row);

		foreach($row as $char)
		{
			if ($char == ' ' || $char == ']') continue;

			if (array_key_exists(strtolower($char), $mslKey) === false)
			{
				$mslKey[strtolower($char)] = $mslChars[$keyIndex];
				$keyIndex ++;
				if ($keyIndex > count($mslChars)) DIE ('Too many different tile types! Tell Jared.');
			}
		}
	}

    foreach ($mtlRows as &$row)
    {
        foreach ($row as &$char)
		{
			if (isset($mslKey[strtolower($char)]))
			$char = $mslKey[strtolower($char)];
		}
    }

	if (is_file("{$rootPath}content/maps/{$map}.msl"))
	{
		$mslString = file_get_contents("{$rootPath}content/maps/{$map}.msl");
		$mslString = str_replace($newLineChar, ']', $mslString);

		$mslRows = explode(']', $mslString);

		$rowNum = 0;
		foreach ($mslRows as $row)
		{
			$row = str_split($row);

			$colNum = 0;
			foreach ($row as $char)
			{
				if (preg_match('/\w/', $char))
				{
					$mtlRows[$rowNum][$colNum] = $char;
				}
				$colNum ++;
			}
			$rowNum ++;
		}
	}

	foreach ($mtlRows as &$row)
	{
		$row = implode($row);
	}
	$mtlString = implode(']', $mtlRows);

    $mtlString = str_replace(']', $newLineChar, $mtlString);

    $noMap = false;
}
else
{
    $noMap = true;
}
?>


<html>
    <head>

    </head>
    <body>
		<div style="font-size:15pt; background-color:#fc0; text-align:center;">DO NOT USE</div>
        <?php if ($noMap) { ?>

        <form action="makeMSL.php" method="get">
            Map name:<input name="map" /> (e.g., <i>'testIsland'</i>)<br>
            Once the map is created, press F5 to re-roll.
        </form>

        <?php } else { ?>

        <textarea style="width:100%;height:100%"><?php echo $mtlString; ?></textarea>

            <?php } ?>
    </body>
</html>