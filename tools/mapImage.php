<?php

// Don't look at this code; it's fucking awful.

if (!isset($_GET['map'])) return;

$rootPath = '../';

require "{$rootPath}engine/core/include.php";

$match = 'map_' . strtolower($_GET['map']);
$length = strlen($match);

foreach ($_SESSION as $key => $value)
{
	if (substr($key, 0, $length) === $match && strlen(substr($key, strpos($key, '_', $length + 1))) === 9) // TERRIBLE
	{
		mapToImage($value);
	}
}

function mapToImage($value)
{
	$map = unserialize($value);

	$colourIndexes = [];

	$visibleObjectLayers =
	[
		LAYER_DOOR_CLOSED,
		LAYER_DOOR_OPEN,
		LAYER_PORTAL,
		LAYER_SIGN,
	];

	$n_offset_1 = $map->minimapTop;
	$n_offset_2 = $map->minimapBottom;
	$w_offset_1 = $map->minimapLeft;
	$w_offset_2 = $map->minimapRight;

	if (isset($n_offset_1, $w_offset_1, $n_offset_2, $w_offset_2))
	{
		$topEdge = min($n_offset_1, $n_offset_2);
		$bottomEdge = max($n_offset_1, $n_offset_2);
		$leftEdge = min($w_offset_1, $w_offset_2);
		$rightEdge = max($w_offset_1, $w_offset_2);
	}
	else
	{
		$topEdge = 0;
		$bottomEdge = getLargestIndex($map->tiles);
		$leftEdge = 0;
		$rightEdge = 0;

		foreach ($map->tiles as $row)
		{
			$rowWidth = getLargestIndex($row);
			if ($rowWidth > $rightEdge)
				$rightEdge = $rowWidth;
		}

		$margin = 10;

		$topEdge -= $margin;
		$bottomEdge += $margin;
		$leftEdge -= $margin;
		$rightEdge += $margin;
	}

	$image = imagecreatetruecolor($rightEdge - $leftEdge, $bottomEdge - $topEdge);

	$emptyColour = $map->emptyTile->bg;

	$showTiles = true;
	$showSprites = true;
	$showObjects = false;

	$pix = 0;

	for ($r = $topEdge; $r <= $bottomEdge; $r++)
	{
		for ($c = $leftEdge; $c <= $rightEdge; $c++)
		{
			if ($showTiles)
			{
				$pixelColour = (isset($map->tiles[$r][$c])) ? $map->tiles[$r][$c]->bg : $emptyColour;
			}

			if ($showSprites)
			{
				if (!$showTiles)
				{
					$pixelColour = $emptyColour;
				}

				$pixelColour = (isset($map->scenery[$r][$c]) && array_search(TPL_HIGHOBSTACLE, $map->scenery[$r][$c]->TPL_borders) !== false) ? $map->scenery[$r][$c]->sprite->getMainColour() : $pixelColour;
			}

			if ($showObjects)
			{
				foreach($visibleObjectLayers as $l)
				{
					$pixelColour = $map->objects[$r][$c][$l]->sprite->getMainColour();
				}
			}

			if (isset($colourIndexes[$pixelColour]))
			{
				$pixelColour = $colourIndexes[$pixelColour];
			}
			else
			{
				$rgbVals = colourStringToRGBvals($pixelColour);
				$colourIndexes[$pixelColour] = imagecolorallocate($image, $rgbVals['r'], $rgbVals['g'], $rgbVals['b']);
				$pixelColour = $colourIndexes[$pixelColour];
			}

			imagesetpixel($image, $c - $leftEdge, $r - $topEdge, $pixelColour);
			$pix++;
		}
	}

	header('Content-Type: image/png');
	imagepng($image);
}

function colourStringToRGBvals ($string)
{
	$string = trim($string, '#');

	$r = hexdec($string[0].$string[0]);
	$g = hexdec($string[1].$string[1]);
	$b = hexdec($string[2].$string[2]);

	return ['r' => $r, 'g' => $g, 'b' => $b];
}