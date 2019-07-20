<?php

class skl_EDITOR_saveMap extends a_skl_EDITOR
{
	public function __construct()
	{
		$sprite = new Sprite([
			0 => new SpriteElement('#449', '#f77', '&#x2590;'),
//			1 => new SpriteElement('#ccc', '#666', '&#x2261;'),
			1 => new SpriteElement('#ccc', '#666', '&#x039e;'),
			2 => new SpriteElement('#449', '#ccc', '&#x258c;'),
			3 => new SpriteElement('#449', null, ' '),
			4 => new SpriteElement('#449', '#999', '&#x2584;'),
			5 => new SpriteElement('#449', null, ' '),
		]);

		parent::__construct('Save', $sprite);
	}

	public function getDescription()
	{
		return 'Save the map in its current form';
	}

	public function onUse($n_offset, $w_offset)
	{
		global $map;
		global $rootPath;

		update('saveStatus', 'Saving...');

		foreach ($map->tiles as $row) { array_filter($row, 'tileFilter'); }
		array_filter($map->tiles);

		foreach ($map->scenery as $row) { array_filter($row); }
		array_filter ($map->scenery);

		$tileRows = getLargestIndex($map->tiles);
		$sceneryRows = getLargestIndex($map->scenery);

		$mtl = '';
		$msl = '';

		for ($i = 0; $i <= $tileRows; $i++)
		{
			if (isset($map->tiles[$i]))
			{
				$tileRow = $map->tiles[$i];

				$columns = getLargestIndex($tileRow);
				for ($k = $columns; $k > 0; $k--)
				{
					if (!isset($tileRow[$k]) || $tileRow[$k] == $map->emptyTile)
					{
						unset($tileRow[$k]);
						$columns--;
					}
					else break;
				}

				for ($j = 0; $j <= $columns; $j++)
				{
					$mtl .= (isset($tileRow[$j]) && $tileRow[$j] != $map->emptyTile ? $tileRow[$j]->key : ' ');
				}
			}
			if ($i < $tileRows) $mtl .= LINE_BREAK;
		}

		$emptyRows = [];
		foreach ($map->scenery as $index => $row)
		{
			if (count($row) == 0 || $row == [])
			{
				$emptyRows[] = $index;
			}
			else
			{
				$count = count($row);
				console_echo ("$index: $count");
			}
		}

		foreach ($emptyRows as $index)
		{
			unset($map->scenery[$index]);
			console_echo("Deleteting Scenery row {$index}");
		}

		for ($i = 0; $i <= $sceneryRows; $i++)
		{
			if (isset($map->scenery[$i]))
			{
				$sceneryRow = $map->scenery[$i];

				$columns = getLargestIndex($sceneryRow);
				for ($j = 0; $j <= $columns; $j++)
				{
					$msl .= (isset($sceneryRow[$j]) ? $sceneryRow[$j]->key : ' ');
				}
			}
			if ($i < $sceneryRows) $msl .= LINE_BREAK;
		}

		$success = file_put_contents("{$rootPath}content/maps/{$map->mapPath}.mtl", $mtl) !== FALSE;
		$success = file_put_contents("{$rootPath}content/maps/{$map->mapPath}.msl", $msl) !== FALSE AND $success;

		if ($success)
		{
			update_thoughts('Save successful!');
			console_echo("{$map->mapPath} saved successfully!", '#afa');
		}
		else
		{
			update_thoughts('Map save failed!');
			console_echo("Error saving map \"{$map->mapPath}\"!", '#faa');
		}
	}
}

function tileFilter($tile)
{
	global $map;
	return $tile instanceof Tile && $tile !== $map->emptyTile;
}