<?php

class skl_EDITOR_placeAsset extends a_skl_EDITOR
{
	public $asset;

	const CLEAR_TILE = 0;
	const CLEAR_SCENERY = 1;

	public function __construct($asset)
	{
		$this->asset = $asset;


		if ($asset === self::CLEAR_TILE)
		{
			$sprite = new Sprite([
				new SpriteElement(null, '#f00', '\\'),
				new SpriteElement(null, '#f00', 'V'),
				new SpriteElement(null, '#f00', '/'),
				new SpriteElement(null, '#f00', '/'),
				new SpriteElement(null, '#f00', '&#x039b;'),
				new SpriteElement(null, '#fff', 'T'),
			]);
			$type = 'clear tile';
		}
		elseif ($asset === self::CLEAR_SCENERY)
		{
			$sprite = new Sprite([
				new SpriteElement(null, '#f00', '\\'),
				new SpriteElement(null, '#f00', 'V'),
				new SpriteElement(null, '#f00', '/'),
				new SpriteElement(null, '#f00', '/'),
				new SpriteElement(null, '#f00', '&#x039b;'),
				new SpriteElement(null, '#fff', 'S'),
			]);
			$type = 'clear scenery';
		}
		elseif ($asset instanceof Tile)
		{
			$sprite = tileToSprite($asset);
			$type = 'tile';
		}
		else
		{
			$sprite = $asset->sprite;
			$type = 'scenery';
		}

		$key = isset($asset->key) ? $asset->key : '';

		parent::__construct("Place {$type} {$key}", $sprite);

		$this->key = id(4);
	}

	public function getDescription()
	{
		return 'Places the associated asset on the map.';
	}

	public function onUse($n_offset, $w_offset)
	{
		if (!isset($n_offset, $w_offset))
		{
			$n_offset = $this->owner->n_offset;
			$w_offset = $this->owner->w_offset;
		}

		global $map;
		global $view;
		global $player;

		$view->forceUpdate = true;

		if (isset($map->objects[$n_offset][$w_offset][LAYER_EDITOR_MARK]))
		{
			$player->selection->applyAsset($this->asset);
			return true;
		}

		if (is_numeric($this->asset))
		{
			if ($this->asset == self::CLEAR_TILE)
			{
				if ($this->owner->fillMode)
				{
					tileFillArea($n_offset, $w_offset, $map->emptyTile);
					update_thoughts('Tile area cleared!');
					$this->owner->endFillMode();
				}
				else
				{
					unset($map->tiles[$n_offset][$w_offset]);
				}
			}
			elseif	($this->asset == self::CLEAR_SCENERY)	unset($map->scenery[$n_offset][$w_offset]);
		}
		elseif	($this->asset instanceof Tile)
		{
			if ($this->owner->fillMode)
			{
				$this->owner->endFillMode();
				tileFillArea($n_offset, $w_offset, $this->asset);
				update_thoughts('Tile area filled!');

			}
			else
			{
				$map->tiles[$n_offset][$w_offset] = $this->asset;
			}
		}
		else
		{
			$map->scenery[$n_offset][$w_offset] = $this->asset;
		}
		return true;
	}
}

function tileFillArea($n_offset, $w_offset, Tile $newTile)
{
	global $map;

	$oldTile = isset($map->tiles[$n_offset][$w_offset]) ? $map->tiles[$n_offset][$w_offset] : null;

	$map->tiles[$n_offset][$w_offset] = EDITOR_FILL_MARKER;

	$iterations = 0;
	$abort = false;

	for(;;)
	{
		$changeMade = false;

		foreach($map->tiles as $n_offset => &$tileRow)
		{
			foreach ($tileRow as $w_offset => &$tile)
			{
				if ($tile == EDITOR_FILL_MARKER)
				{
					$changeMade = assessTileForFill($n_offset + 1, $w_offset, $oldTile) || $changeMade;
					$changeMade = assessTileForFill($n_offset - 1, $w_offset, $oldTile) || $changeMade;
					$changeMade = assessTileForFill($n_offset, $w_offset + 1, $oldTile) || $changeMade;
					$changeMade = assessTileForFill($n_offset, $w_offset - 1, $oldTile) || $changeMade;
				}
			}
		}
		if (++$iterations > 200)
		{
			$abort = true;
			break;
		}
		if (!$changeMade) break;
	}

	if ($abort)
	{
		update_thoughts("Aborting tile fill. Area too large or outside the map boundries. Filling works best if you try to fill from the top-most, left-most point.", '#fda');

		foreach($map->tiles as &$tileRow)
		{
			foreach ($tileRow as &$tile)
			{
				if ($tile == EDITOR_FILL_MARKER)
				{
					if ($oldTile === null) { unset($tile); }
					else { $tile = $oldTile; }
				}
			}
		}
	}

	foreach($map->tiles as &$tileRow)
	{
		foreach ($tileRow as &$tile)
		{
			if ($tile == EDITOR_FILL_MARKER)
			{
				$tile = $newTile;
			}
		}
	}

	$GLOBALS['view']->forceUpdate = true;
}

function assessTileForFill($n_offset, $w_offset, $oldTile)
{
	global $map;

	if
	(
		($oldTile === null && !isset($map->tiles[$n_offset][$w_offset])) ||
		(isset($map->tiles[$n_offset][$w_offset]) && $map->tiles[$n_offset][$w_offset] == $oldTile)
	)
	{
		$map->tiles[$n_offset][$w_offset] = EDITOR_FILL_MARKER;
		return true;
	}
	return false;
}