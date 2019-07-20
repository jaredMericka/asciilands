<?php

class EditorPlayer2 extends Player
{
	public $selection;

	public $copiedTiles;
	public $copiedScenery;

	public $tileClipboardCache;
	public $sceneryClipboardCache;

	public $fillMode = false;

	function __construct($map, $n_offset, $w_offset)
	{
//		global $view;

		parent::__construct('Editor', $map, $n_offset, $w_offset, '#fff', '#fff', '#', '#', GND_MALE);

		$sprite = new Sprite([
			0 => new SpriteElement(null, '#000', '&#x2518;'),
			1 => new SpriteElement(null, '#fff', '&#x253c;'),
			2 => new SpriteElement(null, '#000', '&#x2514;'),
			3 => new SpriteElement(null, '#000', '&#x2510;'),
			4 => new SpriteElement(null, '#fff', '&#x252c;'),
			5 => new SpriteElement(null, '#000', '&#x250c;'),
		]);

//		$sprite = $view->addClientSprite($sprite);

		$this->sprite->key = 'player';
		$this->sprite = $sprite;
		$this->spriteSet[SPRI_DEFAULT] = $this->sprite;
		$this->spriteSet[SPRI_GEAR] = $this->sprite;

		$this->layer = LAYER_EDITOR_PLAYER;
	}

	function updateEquipmentSprite() { }


	public function onMapChange()
	{
		global $view;

		parent::onMapChange();

		initialiseEditorPlayer();
		update_skills_EDITOR();
		$view->forceUpdate = true;
	}

	function move($n_offset, $w_offset, $MAP = null)
    {
		global $map;

		$n_offset = max($n_offset, 0);
		$w_offset = max($w_offset, 0);

		if ($MAP && $this->MAP !== $MAP)
		{
			$map->destroyObject($this);

			$this->MAP = $MAP;
			$this->n_offset = $n_offset;
			$this->w_offset = $w_offset;

			Map::mountPlayerMap();
		}

		$map->moveObject($this, $n_offset, $w_offset, true); // Move with a forced movement.

		console_update_location();

        return true;
    }

	public function copyAssets ($layer)
	{
		if (empty($this->selection->selectionMarkers)) return false;

		global $map;

		$mapLayer = &$map->$layer;
		$storageValue = $layer === 'tiles' ? 'copiedTiles' : 'copiedScenery';

		$copiedAssets = [];

		$offset_n = 0;
		$offset_w = 0;

		for ($n = $this->selection->top; $n <= $this->selection->bottom; $n ++)
		{
			$row = [];
			for ($w = $this->selection->left; $w <= $this->selection->right; $w ++)
			{
				if (isset($mapLayer[$n][$w])) $row[$offset_w] = $mapLayer[$n][$w];
				$offset_w ++;
			}

			$copiedAssets[$offset_n] = $row;

			$offset_w = 0;
			$offset_n++;
		}

		$this->$storageValue = $copiedAssets;

		update_clipboard_EDITOR($this->selection->top, $this->selection->bottom, $this->selection->left, $this->selection->right, $layer === 'tiles');

		update_thoughts(ucfirst("{$layer} copied successfully!"));
	}

	public function pasteAssets ($pasteTiles = true)
	{


		console_var_dump($this->copiedTiles);

		if ($pasteTiles)	{ if (empty($this->copiedTiles))	return false; }
		else				{ if (empty($this->copiedScenery))	return false; }

		global $map;
		global $view;

		$layer = $pasteTiles ? 'tiles' : 'scenery';

		console_echo("Pasting {$layer}");

		$mapLayer = &$map->$layer;

		$source = $pasteTiles ? $this->copiedTiles : $this->copiedScenery;

		foreach ($source as $n => $row)
		{
			foreach ($row as $w => $asset)
			{
				$mapLayer[$this->n_offset + $n][$this->w_offset + $w] = $asset;
			}
		}

		$view->forceUpdate = true;
	}

	public function endFillMode()
	{
		$this->sprite->change($this->spriteSet[SPRI_DEFAULT]);
		$this->fillMode = false;
	}
}

function initialiseEditorPlayer()
{
	global $map;
	global $view;
	global $player;

	$player->skills = [];

	foreach ($map->tileKey as $key => $tile)
	{
		$worked = $player->addSkill(new skl_EDITOR_placeAsset($tile));
//		console_echo("Tile: {$key} - " . ($worked ? 'worked' : 'failed'));
	}
	$player->addSkill(new skl_EDITOR_placeAsset(skl_EDITOR_placeAsset::CLEAR_TILE));

	foreach ($map->sceneryKey as $key => $scenery)
	{
		$worked = $player->addSkill(new skl_EDITOR_placeAsset($scenery));
//		console_echo("Scenery: {$key} - " . ($worked ? 'worked' : 'failed'));
	}
	$player->addSkill(new skl_EDITOR_placeAsset(skl_EDITOR_placeAsset::CLEAR_SCENERY));

	$player->addSkill(new skl_EDITOR_teleport());
	$player->addSkill(new skl_EDITOR_select());
	$player->addSkill(new skl_EDITOR_saveMap());
	$player->addSkill(new skl_EDITOR_copyAssets());
	$player->addSkill(new skl_EDITOR_copyAssets(false));
	$player->addSkill(new skl_EDITOR_fill());

	$binding1 = new SkillBinding($player, 'skl_EDITOR_teleport', SKLS_CLICK);
	$binding1->bind();

	$binding2 = new SkillBinding($player, 'skl_EDITOR_select', SKLS_RIGHT_CLICK);
	$binding2->bind();

	$player->fillMode = false;
}

function update_skills_EDITOR ()
{
	global $player;
	global $view;

	$update = [];

	foreach ($player->skills as $key => $skill)
	{
		$skillUpdate = new stdClass();

		$skillUpdate->sprite	= $view->addClientSprite($skill->sprite)->key;
		$skillUpdate->key		= $key;
		$skillUpdate->type		= get_class($skill);

		if ($skill instanceof skl_EDITOR_placeAsset)
		{
			if ($skill->asset instanceof Scenery)
			{
				$skillUpdate->class = '';
				$TPL_borders = $skill->asset->TPL_borders;

				if (isset($TPL_borders[DIR_NORTH])) $skillUpdate->class .= " n{$TPL_borders[DIR_NORTH]}";
				if (isset($TPL_borders[DIR_SOUTH])) $skillUpdate->class .= " s{$TPL_borders[DIR_SOUTH]}";
				if (isset($TPL_borders[DIR_EAST])) $skillUpdate->class .= " e{$TPL_borders[DIR_EAST]}";
				if (isset($TPL_borders[DIR_WEST])) $skillUpdate->class .= " w{$TPL_borders[DIR_WEST]}";
			}
			elseif ($skill->asset instanceof Tile)
			{
				$skillUpdate->class = " b{$skill->asset->TPL}";
			}
		}

		$update[] = $skillUpdate;
	}

	update(UPD_EDITOR_SKILLS, $update);
}



class editorSelection
{
	public $pointA;
	public $pointB;

	public $selectionSprite;

	const N_OFFSET = 0;
	const W_OFFSET = 1;

	public $top;
	public $bottom;
	public $left;
	public $right;

	public $selectionMarkers;

	public function __construct(Sprite $sprite)
	{
		$this->selectionSprite = $sprite;
	}

	public function drawSelection ()
	{
		global $map;

		if (!isset($this->pointA, $this->pointB)) return;
		if (!empty($this->selectionMarkers)) $this->clearSelection();

		if (!isset($this->top, $this->bottom, $this->left, $this->right))
		{
			$this->top		= min($this->pointA[self::N_OFFSET], $this->pointB[self::N_OFFSET]);
			$this->bottom	= max($this->pointA[self::N_OFFSET], $this->pointB[self::N_OFFSET]);
			$this->left		= min($this->pointA[self::W_OFFSET], $this->pointB[self::W_OFFSET]);
			$this->right	= max($this->pointA[self::W_OFFSET], $this->pointB[self::W_OFFSET]);
		}

		for ($n = $this->top; $n <= $this->bottom; $n++)
		{
			for ($w = $this->left; $w <= $this->right; $w++)
			{
				$mark = new editorMark($this->selectionSprite, $n, $w);
				$this->selectionMarkers[] = clone $mark;
				$map->addObjects($mark);
			}
		}
	}

	public function clearSelection ()
	{
		if (empty($this->selectionMarkers)) return;

		foreach ($this->selectionMarkers as $marker)
		{
			$marker->destroy();
		}

		$this->selectionMarkers = [];
	}

	public function applyAsset($asset)
	{
		if (empty($this->selectionMarkers)) return;

		global $map;

		if (is_numeric($asset))
		{
			$layer = $asset ? 'scenery' : 'tiles';
			$asset = null;
		}
		else
		{
			$layer = $asset instanceof Tile ? 'tiles' : 'scenery';
		}

		$layer = &$map->$layer;

		foreach ($this->selectionMarkers as $marker)
		{
			$layer[$marker->n_offset][$marker->w_offset] = $asset;
			if (!$layer[$marker->n_offset][$marker->w_offset]) unset($layer[$marker->n_offset][$marker->w_offset]);
		}
	}
}

class editorMark extends AsObject
{
	public function __construct($sprite, $n_offset, $w_offset)
	{
		$this->n_offset = $n_offset;
		$this->w_offset = $w_offset;
		parent::__construct('Editor Mark', [$sprite], LAYER_EDITOR_MARK);
	}

	public function onIdle()
	{
		global $player;
		if (!($player instanceof EditorPlayer2)) $this->destroy();
		parent::onIdle();
	}
}

function update_clipboard_EDITOR ($top, $bottom, $left, $right, $tiles = true)
{
	global $player;
	global $map;

	console_echo('Updating clipboard for ' . ($tiles ? 'tiles.' : 'scenery.'));
	$update = new stdClass();

	$update->tiles = $tiles;
	$update->body = $map->getMiniMap($top, $left, $bottom, $right, $tiles, !$tiles, false);


	$storageValue = $tiles ? 'tileClipboardCache' : 'sceneryClipboardCache';
	$player->$storageValue = $update->body;

	console_echo($update->body);

	update(UPD_EDITOR_CLIPBOARD, $update);
}

//function editorToPlayer(EditorPlayer2 $editorPlayer)
//{
//	global $map;
//
//	$player = new Player(
//		'ExEd',
//		$editorPlayer->map,
//		$editorPlayer->n_offset,
//		$editorPlayer->w_offset,
//		'#fff', '#000',
//		'e', '&Pi;');
//
//	$player->id = $_SESSION['playerId'];
//
//	$map->addObject($player);
//
//	return $player;
//}
//
//function playerToEditor ()
//{
//	global $map;
//	global $player;
//
//	$editorPlayer = new EditorPlayer2(
//		$player->map,
//		$player->n_offset,
//		$player->w_offset);
//
//	$editorPlayer->id = $_SESSION['playerId'];
//
//	$player = $editorPlayer;
//
//	Map::mountPlayerMap();
//
//}