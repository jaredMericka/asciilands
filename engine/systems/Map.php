<?php

class Map
{
    public $mapName;
	public $level;
	public $id;
	public $MAP;

    public $mapPath;

	public $players;
	public $playerRegister = [];

	public $objectRegister = [];

	// The following two variables are set while the .mtl file is being processed.
	public $height;
	public $width;

    public $css;
    public $js;

	public $tileKeyJson;
	public $spriteKeyJson;

	// Tile stuff
	public $tiles;     // array of tiles
    public $tileKey;
    public $emptyTile;

	// Scenery stuff
    public $sceneryKey;
    public $scenery;	// array of scenery

	// Materials
	public $materials;

	// AsObject stuff
    public $objects;	// array of objects rebuilt with each frame

	// Effects
	public $effects;	// array of effects

    public $spriteKey			= [];
	public $spriteKeyIndexChars	= [];

	public $requirements = [];

	//\\//\\//\\//\\//\\//\\//\\//\\
	// Map properties
	//\\//\\//\\//\\//\\//\\//\\//\\

	public $minimapTop;
	public $minimapBottom;
	public $minimapLeft;
	public $minimapRight;

    public $viewHeight;
    public $viewWidth;

	public $overlayColour = '#000';
	public $overlayOpacity = 0;

	public $allowMiniMap	= true;

	public $isDark			= false;
	public $isIndoor		= false;
	public $isUnderground	= false;

	public $localCurrency;
    public $territory;
    public $continent;
    public $district;
    public $landOwner;
    public $landLord;
    public $landKing;
    public $landEmperor;

	static function mountPlayerMap ($force = false)
	{
		console_echo("=== MOUNTING PLAYER MAP ===", '#aaf');

		global $map;
		global $player;
		global $view;
		global $MAP_paths;

		// Do we even need a new map?
//		if (!$force && isset($map) && $map->mapPath === $player->map && $map->level === $player->level)
		if (!$force && isset($map) && $map->MAP === $player->MAP && $map->level === $player->level)
		{
			console_echo('Looks like you\'re in the right place; mounting of player map aborted.', '#fff');
			return;
		}

		// Alright, we're committed to changing now; put the current one into storage.
		if (isset($map))
		{
			console_echo("Putting old map into cache (id: <<#fff>>{$map->id}<>)", '#aaf');
			$_SESSION[$map->id] = serialize($map);
		}
		$map = null;

		$match = "map_{$player->MAP}_{$player->level}_";
		$length = strlen($match);

		foreach ($_SESSION as $key => $value)
		{
			if (substr($key, 0, $length) === $match)
			{
				console_echo("<<#afa>>{$key}<> matches the desired <<#ffa>>{$match}<>; let's take it.", '#fff');

				$map = unserialize($value);
			}
			else { console_echo("<<#fda>>{$key}<> does not match the desired <<#ffa>>{$match}<>.", '#fff'); }
		}

		if (!$map)
		{
			$map = new Map($player->MAP, $player->level);
		}

//		$map->playerRegister[$player->id] = &$player;
		$map->addObjects($player);

		$view = new View();
		$view->update();

		$player->sprite = $view->addClientSprite($player->sprite);
		$player->spriteSet[SPRI_DEFAULT] = $view->addClientSprite($player->sprite);

		$_SESSION[$map->id] = serialize($map);
		$_SESSION['view'] = $view;
		$_SESSION['mapId'] = $map->id;


		$player->onMapChange();
		update_jvsKeys();
	}

    function __construct ($MAP, $level = null)
    {
		global $rootPath;
		global $player;
		global $MAP_paths;

		console_echo("Constructing a map with MAP: <<#fff>>{$MAP}<>", '#afa');

		$GLOBALS['mapLevel']		= &$this->level;
		$GLOBALS['mapMaterials']	= &$this->materials;

		if (isset($MAP))
		{
			$this->MAP = $MAP;
			$mapPath = $MAP_paths[$MAP];
			$mapNameParts = explode('/', $mapPath);
			$this->mapName = end($mapNameParts);
			$this->mapPath = $mapPath;
			$this->level = isset($level) ? $level : 1;
		}
		else
		{
			if (isset($player))
			{
				$this->MAP = $player->MAP;
				$this->mapPath = $MAP_paths[$this->map];
				$this->level = $player->level;
			}
			else
			{
				console_echo('Player must be initialised before map can be created.');		//XXX
				DIE();
			}
		}

//		$this->id = 'map_' . strtolower($this->mapPath) . "_{$this->level}_" . id();
		$this->id = "map_{$this->MAP}_{$this->level}_" . id();

		console_echo("Opening {$mapPath} (level {$this->level})", '#fff');

		// Load the .map file.
		console_echo('<br>+-----+ Objects +-----+<br>', '#fff');		//XXX

		require("{$rootPath}engine/core/_commonMaterialArray.php");
		require("{$rootPath}content/maps/{$this->mapPath}.map");

		console_echo('<br>+-----+ Map properties +-----+<br>', '#fff');		//XXX
		$this->checkRequiredVars();

		if (!isset($this->tileKey))		DIE('Map cretion failed! TileKey missing or invalid.');
		if (!isset($this->sceneryKey))	DIE('Map cretion failed! SceneryKey missing or invalid.');
		if (!isset($this->objects))		DIE('Map cretion failed! Objects array missing or invalid.');

        if (empty($this->viewHeight)) $this->viewHeight = MAX_VIEW_SIZE;
        if (empty($this->viewWidth)) $this->viewWidth = MAX_VIEW_SIZE;

		console_echo('<br>+-----+ Processing map keys +-----+<br>', '#fff');		//XXX

		$this->spriteKeyIndexChars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'); // The keys get all fucked up if you put numbers in here.
//		$keyChars = count($this->spriteKeyIndexChars) - 1;
		shuffle($this->spriteKeyIndexChars); //This will make the javascript more difficult to understand. Usually this is a bad thing but in this case it'll just keep our secrets secret.

        $this->processTileKey($this->tileKey);
        $this->processSceneryKey($this->sceneryKey);

		$this->processObjects($this->objects);

//		$this->buildSpriteKey();

		if (!$this->tiles)		$this->buildTileArrayFromFile();
		if (!$this->scenery)	$this->buildSceneryArrayFromFile();

        $this->buildJsonObjects();
        $this->buildCSS();


		console_echo('<br>+-----+ Map initialise complete +-----+<br>', '#fff');		//XXX
    }

	public function checkRequiredVars()
	{
		$requredVars = [
			'tileKey',
			'sceneryKey',
			'objects',
		];

		foreach($requredVars as $var)
		{
			if (!isset($this->$var))
			{
				console_echo("Required map variable \"{$var}\" is not set.", '#faa');		//XXX
				DIE();
			}
		}

		$output = false;
		foreach(get_object_vars($this) as $var => $val)
		{
			if ($var == 'viewHeight') $output = true;
			if ($output)
			{
				if (is_bool($val))
					$val = ($val ? 'true' : 'false');
				if (!is_null($this->$var))		//XXX
				{		//XXX
					console_echo("<span style=\"color:#afa;\">{$var}</span> is set to <span style=\"color:#aff;\">{$val}</span>",'#fff');		//XXX
				}		//XXX
				else		//XXX
				{		//XXX
					console_echo("<span style=\"color:#fda;\">{$var}</span> is not set.",'#fff');		//XXX
				}		//XXX

			}
		}
	}

	public function setProperties($settingsArray)
	{
		foreach($settingsArray as $setting => $value)
		{
			$this->$setting = $value;
			console_echo("\"$setting\" overridden to \"$value\"", '#fff');		//XXX
		}
	}

	public function processTileKey($tileKey)
	{
		$newTileKey = array();
//		$tileIndexChars = str_split('abcdefghijklmnopqrstuvwxyz');
		$tileIndexChars = str_split('aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ');
		$tileIndex = 0;
//		$spareKey = null;
		foreach ($tileKey as $tile)
		{
			if ($tileIndex >= count($tileIndexChars))
			{
				console_warning('Too many tiles! Maximum: ' . count($tileIndexChars));
				break;
			}

			if ($tile instanceof Tile)
			{

			$newTileKey[$tileIndexChars[$tileIndex]] = $tile;

			}
			// Having the tileIndex incremeenter outside the tile-checking if means
			// that nulls can be put into the tile key to remove unused tiles but
			// maintain tile order and position. Thsoe blank spaces can then be
			// used later for something else.
			$tileIndex ++;
		}

	    foreach ($newTileKey as $key => $tile)
		{
			$tile->key = $key;
		}

		$this->emptyTile = $newTileKey[$tileIndexChars[0]];

		$this->tileKey = $newTileKey;
	}

	public function processSceneryKey($sceneryKey)
	{
		$newSceneryKey = [];
		$sceneryIndexChars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
		$sceneryIndex = 0;
		foreach ($sceneryKey as $scenery)
		{
			if ($sceneryIndex >= count($sceneryIndexChars))
			{
				$count = count($sceneryIndexChars);
				console_warning('Too many scenery sprites! Maximum: ' . ($count * $count));
				break;
			}

			if ($scenery instanceof Scenery) $newSceneryKey[$sceneryIndexChars[$sceneryIndex]] = $scenery;
			$sceneryIndex++;

		}

		foreach ($newSceneryKey as $key => &$scenery)
		{
			$scenery->key = $key;
			$scenery->sprite = $this->prepareSprite($scenery->sprite);
		}

		$this->sceneryKey = $newSceneryKey;
	}

	/**
	 * Assigns a single letter key to each sprite used in the map and embeds the
	 * key in the sprite object. We should never have to worry about what sprite
	 * gets what key as everything that uses or references these keys are all
	 * auto-generated.
	 *
	 * @param $spriteKey An array of all the sprites used. This should be passed in from the .map file.
	 */
	public function buildSpriteKey()
	{
		$newSpriteKey = array();
		$this->spriteKeyIndexChars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'); // The keys get all fucked up if you put numbers in here.
		$keyChars = count($this->spriteKeyIndexChars) - 1;
		shuffle($this->spriteKeyIndexChars); //This will make the javascript more difficult to understand. Usually this is a bad thing but in this case it'll just keep our secrets secret.

		$index1 = 0;
		$index2 = 0;

		foreach($this->spriteKey as $key => $sprite)
		{
			$key = $this->spriteKeyIndexChars[$index1] . $this->spriteKeyIndexChars[$index2];

			console_echo("{$key} <<#fff>>=><> " . console_sprite($sprite), '#afa');

			$newSpriteKey[$key] = $sprite;
			$index2 ++;

			if ($index2 > $keyChars)
			{
				$index2 = 0;
				$index1 ++;
			}
		}

		$totalSprites = $keyChars * $keyChars;			//XXX
		console_echo(count($this->spriteKey) . " of {$totalSprites} sprites used in {$this->mapPath}.",'#faf');		//XXX

		foreach ($newSpriteKey as $key => $sprite)
		{
			if (!($sprite instanceof Sprite))
			{
				unset($newSpriteKey[$key]);
				console_echo('Non-sprite passed into the sprite key.', '#faa');		//XXX
				continue;
			}
			$sprite->key = $key;
		}

		$this->spriteKey = $newSpriteKey;
	}

	public function processObjects($objects)
	{
		$newObjectKey = [];
        $count = 0;
		foreach ($objects as $coOrds => $object)
        {
			list($n_offset, $w_offset) = explode(':', $coOrds);

			if (!is_numeric($n_offset) || !is_numeric($w_offset))
			{
				console_echo("<<#fff>>{$object->name}<> has dodgy coordinates and is being discarded.", '#faa');
				continue;
			}

			$object->n_offset = $n_offset;
			$object->w_offset = $w_offset;

			$newObjectKey[$object->n_offset][$object->w_offset][$object->layer] = $object;

			$object->sprite = $this->prepareSprite($object->sprite);
			foreach ($object->spriteSet as $key => $sprite)
			{
				$object->spriteSet[$key] = $this->prepareSprite($sprite);
			}

			if ($object->constituents)
			{
				foreach ($object->constituents as $c_n_offset => &$constituents)
				{
					foreach ($constituents as $c_w_offset => &$constituent)
					{
						$constituent->owner = $object;
						$constituent->sprite = $this->prepareSprite($constituent->sprite);
						console_echo('constituent sprite: ' . console_sprite($constituent->sprite));
						foreach ($constituent->spriteSet as $key => $sprite)
						{
							$constituent->spriteSet[$key] = $this->prepareSprite($sprite);
							console_echo('constituent set sprite: ' . console_sprite($sprite));
						}

						$newObjectKey[$n_offset + $c_n_offset][ $w_offset + $c_w_offset][$object->layer] = $constituent;
					}
				}
//				$object->constituentPlace();
			}

			foreach ($object->behaviours as $behaviours)
			{
				foreach ($behaviours as &$behaviour)
				{
					foreach ($behaviour->spriteSet as $key => $sprite)
					{
						$behaviour->spriteSet[$key] = $this->prepareSprite($sprite);
					}
				}
			}

			$this->objectRegister[$object->id] = &$object;

            $count ++;
        }
		$this->objects = $newObjectKey;
        console_echo("Objects processed:{$count}.", '#fff');		//XXX
	}

	public function prepareSprite($sprite)
	{
//		if (!in_array($sprite, $this->spriteKey)) $this->spriteKey[] = $sprite;
//		else
//		{
//			$existingDup = $this->spriteKey[array_search($sprite, $this->spriteKey)];
//			$sprite = $existingDup;
//		}

		foreach ($this->spriteKey as $key => $existingSprite)
		{
			if ($sprite->equals($existingSprite))
			{
				return $existingSprite;
			}
		}

		$spriteKeyIndexChars = $this->spriteKeyIndexChars;
		$charsCount = count($spriteKeyIndexChars);
		$totalSprites = count($this->spriteKey) + 1; // Plus one for this sprite

		$key = $this->spriteKeyIndexChars[floor($totalSprites / $charsCount)] . $spriteKeyIndexChars[$totalSprites % $charsCount];

		$sprite->key = $key;

		$this->spriteKey[$key] = $sprite;

		return $sprite;
	}

	public function buildTileArrayFromFile()
	{
		console_echo("Loading map tile layer from {$this->mapPath}.mtl", '#fff');		//XXX

		$height = 0;
		$width = 0;

        $tileFileString = file_get_contents("{$GLOBALS['rootPath']}content/maps/{$this->mapPath}.mtl");
        $tileRowStringArray = explode(LINE_BREAK, $tileFileString);

        $count = 0;
        foreach($tileRowStringArray as $tileRowString)
        {
            $tileRow = array();
            for($index = 0; $index < strlen($tileRowString); $index++)
            {
                if ($tileRowString[$index] != ' ')
                {
                    if (isset($this->tileKey[$tileRowString[$index]]))
                    {
                        $tileRow[$index] = $this->tileKey[$tileRowString[$index]];
						if ($index > $width) $width = $index;
                        $count ++;
                    }
                    else
                        $tileRow[$index] = $this->emptyTile;
                }
            }
            $this->tiles[] = $tileRow;
			$height ++;
        }

		$this->height = $height;
		$this->width = $width;
		console_echo("Map height: {$height}", '#fff');
		console_echo("Map width: {$width}", '#fff');
        console_echo("Tiles processed:{$count}.", '#fff');		//XXX
	}

	public function buildSceneryArrayFromFile()
	{
		console_echo("Loading map scenery layer from {$this->mapPath}.msl", '#fff');		//XXX
        $sceneryFileString = file_get_contents("{$GLOBALS['rootPath']}content/maps/{$this->mapPath}.msl");
        $sceneryRowStringArray = explode(LINE_BREAK, $sceneryFileString);

        $count = 0;
        foreach($sceneryRowStringArray as $sceneryRowString)
        {
            $sceneryRow = array();
            for($index = 0; $index < strlen($sceneryRowString); $index++)
            {
                if ($sceneryRowString[$index] != ' ')
                {
                    if (isset($this->sceneryKey[$sceneryRowString[$index]]))
                    {
                        $sceneryRow[$index] = $this->sceneryKey[$sceneryRowString[$index]];
                        $count ++;
                    }
                    else
                        $sceneryRow[$index] = null;
                }
            }
            $this->scenery[] = $sceneryRow;
        }
        console_echo("Scenery processed:{$count}.", '#fff');		//XXX
	}

	// This was a nice idea but I don't think it'll be any use where we're going.
	public function getMaterialsByType()
	{
		$acceptedTypes = func_get_args();
		$returnMaterials = [];

		foreach ($this->materials as $material)
		{
			foreach($acceptedTypes as $type)
			{
				if (is_a($material, $type))
				{
					$returnMaterials[] = $material;
					break;
				}
			}
		}
		return $returnMaterials;
	}

    public function buildCSS()
    {
        foreach($this->tileKey as $tile)
        {
            $this->css .= $tile->getCSS();
        }
    }

    public function buildJsonObjects()
    {
		$jsonTiles = [];
		foreach($this->tileKey as $tile)
        {
            $jsonTiles[$tile->key] = $tile->jsonObject;
        }
		$this->tileKeyJson = $jsonTiles;


//		$spr_notFound = new Sprite([
//			new SpriteElement('#0f0', '#f0f', '&#x2580;'),
//			new SpriteElement('#0f0', '#f0f', '&#x2584;'),
//			new SpriteElement('#0f0', '#f0f', '&#x2580;'),
//			new SpriteElement('#0f0', '#f0f', '&#x2580;'),
//			new SpriteElement('#0f0', '#f0f', '&#x2584;'),
//			new SpriteElement('#0f0', '#f0f', '&#x2580;'),
//		]);

		$spr_notFound = new Sprite([
			new SpriteElement('#000', '#f00', 'E'),
			new SpriteElement('#000', '#f00', 'R'),
			new SpriteElement('#000', '#f00', 'R'),
			new SpriteElement('#000', '#f00', 'O'),
			new SpriteElement('#000', '#f00', 'R'),
			new SpriteElement('#000', '#f00', '!'),
		]);

		$jsonSprites = [];
        foreach($this->spriteKey as $sprite)
        {
//            $jsonSprites[$sprite->key] = $sprite->jsonObject;
            $jsonSprites[$sprite->key] = $sprite->getJsonObject();
        }

//		$jsonSprites['player'] = $GLOBALS['player']->sprite->getJsonObject();
		$jsonSprites['notFound'] = $spr_notFound->getJsonObject();

		$this->spriteKeyJson = $jsonSprites;
    }

    function getTileByLocation($n_offset, $w_offset)
    {
        if (isset($this->tiles[$n_offset][$w_offset])) return $this->tiles[$n_offset][$w_offset];
        else return $this->emptyTile;
    }

	/**
	 * Collides an object with anything in a specified location. The object
	 * attempting to enter will collide with the tile, then the sceneery then
	 * the mapObject. IF any of these things prevent the sending object from
	 * entering the tile, <b>false</b> will be returned and entry denied.
	 * <br/>Collision events will be run at each of the three testing stages.
	 *
	 * @param AsObject $instigator The object attempting the enter the tile.
	 * @param integer $n_offset The north offset of the tile being collided with.
	 * @param integer $w_offset The west offset of the tile being collided with.
	 * @return boolean <b>true</b> if the sender is allowed to enter the tile, <b>false</b> if the sender is not allowed to enter the tile.
	 */
    function collideByLocation($instigator, $n_offset, $w_offset)
    {
		global $player;
		global $DIR_opposites;

		$DIR = 0;

		if ($instigator instanceof ObjectConstituent)
		{
			$instigator = $instigator->owner;
		}
		elseif ($instigator->n_offset === $n_offset && $instigator->w_offset === $w_offset)
		{
			console_echo("{$instigator->name} doesn't even want to move!");
			return false;
		}

		if ($instigator->n_offset !== $n_offset)
		{
			$DIR = $n_offset < $instigator->n_offset ? DIR_NORTH : DIR_SOUTH;
		}
		else
		{
			$DIR = $w_offset < $instigator->w_offset ? DIR_WEST : DIR_EAST;
		}

//		$dirnames = [DIR_NORTH => 'north', DIR_SOUTH => 'south', DIR_EAST => 'east', DIR_WEST => 'west'];//XXX
//		console_echo("$instigator->name <<#aaa>>-=><> {$dirnames[$DIR]}", ($instigator === $player ? '#aaa':null));
//        $canMove = $this->getTileByLocation($n_offset, $w_offset)->collide();

		// Can we move into the tile?
        $canMove = in_array($this->getTileByLocation($n_offset, $w_offset)->TPL, $instigator->TPL_passables);

        if // Can we enter the scenery in that direction? Does it even care?
		(
			isset($this->scenery[$n_offset][$w_offset])
			&& isset($this->scenery[$n_offset][$w_offset]->TPL_borders[$DIR_opposites[$DIR]])
		)
        {
            $canMove = in_array($this->scenery[$n_offset][$w_offset]->TPL_borders[$DIR_opposites[$DIR]], $instigator->TPL_passables);
			if (!$canMove) return false;
        }

		if // Can we exit the current scenery in that direction? Does it even care?
		(
			isset($this->scenery[$instigator->n_offset][$instigator->w_offset])
			&& isset($this->scenery[$instigator->n_offset][$instigator->w_offset]->TPL_borders[$DIR])
		)
		{
//			$canMove = $canMove && $this->scenery[$instigator->n_offset][$instigator->w_offset]->canExit($DIR);
			$canMove = $canMove && in_array($this->scenery[$instigator->n_offset][$instigator->w_offset]->TPL_borders[$DIR], $instigator->TPL_passables);
			if (!$canMove) return false;
		}


        if (!empty($this->objects[$n_offset][$w_offset]))
        {
			$object = end($this->objects[$n_offset][$w_offset]);
			if ($object instanceof ObjectConstituent) $object = $object->owner;

			// Weirdly, this only applies when an object's constituent's new location
			// is being tested and is colliding with itself.
			if ($object !== $instigator)
				$canMove = $instigator->collide($object, $DIR) && $canMove;
        }
		if (!$instigator instanceof Player && $n_offset == $player->n_offset && $w_offset == $player->w_offset)
		{
			$canMove = $instigator->collide($player, $DIR) && $canMove;
		}

		if (!($instigator instanceof Player) && !isset($this->objects[$instigator->n_offset][$instigator->w_offset][$instigator->layer])) return false;

        return $canMove;
    }

	public function runIdleActions()
	{
		global $view;
		global $player;

		$fastestObject = 1;

		foreach ($player->behaviours as $behaviour)
		{
			$behaviour = end($behaviour);

			if ($behaviour->can('onIdle'))
			{
				$behaviour->onIdle();
				$behaviour->triggercooldown();
			}

			if ($behaviour->onIdle === true && $behaviour->cooldown <= $fastestObject && $behaviour->cooldown >= MIN_COOLDOWN)
			{
				$fastestObject = $behaviour->cooldown;
			}
		}

		for($renderedTileRow = $view->actionTopOffset; $renderedTileRow <= $view->actionBottomOffset; $renderedTileRow++)
		{
			for($renderedTileColumn = $view->actionLeftOffset; $renderedTileColumn <= $view->actionRightOffset; $renderedTileColumn ++)
            {
				if (isset($this->objects[$renderedTileRow][$renderedTileColumn]))
				{
					foreach ($this->objects[$renderedTileRow][$renderedTileColumn] as $object)
					{
						if ($object instanceof ObjectConstituent) continue;

						if ($object instanceof Dude)
						{
							if ($object->nextStatusCheck <= $_SERVER['REQUEST_TIME_FLOAT'])
							{
								$object->checkStatuses();
							}
						}

						foreach ($object->behaviours as $behaviour)
						{

							$behaviour = end($behaviour);

//							if (!($behaviour instanceof Behaviour))
//							{
//								console_echo($object->name, '#0ff');
//								foreach($object->behaviours as $bhv)
//								{
//									foreach ($bhv as $b)
//									console_echo('## ' . get_class($b), '#f00');
//								}
//
//								continue;
//							}

							if ($behaviour->can(TRG_IDLE))
							{
								$behaviour->onIdle();
								$behaviour->triggercooldown();
							}

							if ($behaviour->onIdle === true && $behaviour->cooldown <= $fastestObject && $behaviour->cooldown >= MIN_COOLDOWN)
							{
								$fastestObject = $behaviour->cooldown;
							}
						}
						if ($object instanceof Dude) $object->regenerate();
						$object->lastUpdated = $_SERVER['REQUEST_TIME_FLOAT'];
					}
				}
			}
		}

//        $view->setRefreshRate($fastestObject >= 0.2 ? $fastestObject : 0.2);
        $view->refreshRate = ($fastestObject >= 0.2 ? $fastestObject : 0.2);
	}

	function addEffects()
	{
		foreach(func_get_args() as $arg)
		{
			if (is_array($arg))
			{
				foreach($arg as $effect)
				{
					$this->effects[$effect->n_offset][$effect->w_offset] = $effect;
				}
			}
			else
			{
				$this->effects[$arg->n_offset][$arg->w_offset] = $arg;
			}
		}
	}

	function addObjects()
	{
		foreach(func_get_args() as $arg)
		{
			if (is_array($arg))
			{
				foreach($arg as $object)
				{
					$this->objects[$object->n_offset][$object->w_offset][$object->layer] = $object;
					$this->objectRegister[$object->id] = &$object;
				}
			}
			else
			{
				$this->objects[$arg->n_offset][$arg->w_offset][$arg->layer] = $arg;
				$this->objectRegister[$arg->id] = &$arg;
			}
		}
	}

	/**
	 * Moves an object to a new location on the map (if allowed).
	 * @param AsObject $object The object to be moved
	 * @param type $n_offset The new n_offset for the object
	 * @param type $w_offset The new w_offset for the object
	 * @param type $force Allows the caller to over-ride usual collision rules (cannot over-ride the object's "stationary" boolean)
	 * @return boolean <b>TRUE</b> if the object moves, <b>FALSE</b> if it can't move
	 */
	function moveObject(AsObject $object, $n_offset, $w_offset, $force = false)
    {
		if ($object->stationary) return false;

		if (!$force)
		{
			if (!$this->collideByLocation($object, $n_offset, $w_offset)) return false;
			if ($object->constituents && !$object->constituentCollide($n_offset, $w_offset)) return false;
		}

		if ($object->constituents) $object->constituentClear();

		$n_offset_old = $object->n_offset;
		$w_offset_old = $object->w_offset;

		$object->onMove($n_offset, $w_offset);

		$object->n_offset = $n_offset;
		$object->w_offset = $w_offset;

		$this->objects[$n_offset][$w_offset][$object->layer] = $object;
		unset($this->objects[$n_offset_old][$w_offset_old][$object->layer]);

		if ($object->constituents) $object->constituentPlace();

		return true;
    }

	function resortObjectLayers($n_offset, $w_offset)
	{
		$objects = &$this->objects[$n_offset][$w_offset];
		if (is_array($objects))
		{
			foreach($objects as $layer => $object)
			{
				if($object->layer != $layer)
				{
					$objects[$object->layer] = $object;
					unset($objects[$layer]);
				}
			}
		}
		ksort($objects);
	}

//    function destroyObject($n_offset, $w_offset, $layer)
//    {
//		unset($this->objectRegister[$this->objects[$n_offset][$w_offset][$layer]->id]);
//        unset($this->objects[$n_offset][$w_offset][$layer]);
//    }

    function destroyObject(AsObject $object)
    {

		unset($this->objectRegister[$object->id]);
        unset($this->objects[$object->n_offset][$object->w_offset][$object->layer]);
    }

	function replaceObject($n_offset, $w_offset, $layer, AsObject $newObject)
	{
//		unset($this->objects[$n_offset][$w_offset][$layer]);
//		$this->objects[$n_offset][$w_offset][$layer]->destroy();
		$this->destroyObject($this->objects[$n_offset][$w_offset][$layer]);

		$newObject->n_offset = $n_offset;
		$newObject->w_offset = $w_offset;

		$this->addObjects($newObject);

//		$this->objects[$n_offset][$w_offset][$newObject->layer] = $newObject;
//		$this->objects[$n_offset][$w_offset][$newObject->layer]->n_offset = $n_offset;
//		$this->objects[$n_offset][$w_offset][$newObject->layer]->w_offset = $w_offset;
	}

	public function getSpriteIndex(Sprite $sprite)
	{
		return array_search($sprite, $this->spriteKey);
	}

	public function getObjectsInArea($n_offset_1, $w_offset_1, $n_offset_2, $w_offset_2)
	{
		$top = min($n_offset_1, $n_offset_2);
		$bottom = max($n_offset_1, $n_offset_2);
		$left = min($w_offset_1, $w_offset_2);
		$right = max($w_offset_1, $w_offset_2);

		$objects = [];

		for ($r = $top; $r <= $bottom; $r ++)
		{
			for ($c = $left; $c <= $right; $c++)
			{
				if (isset($this->objects[$r][$c]))
				{
					foreach ($this->objects[$r][$c] as &$object)
					{
						$objects[] = $object;
					}
				}
			}
		}

		return $objects;
	}

	public function getMiniMap($n_offset_1 = null, $w_offset_1 = null, $n_offset_2 = null, $w_offset_2 = null, $showTiles = true, $showSprites = true, $showObjects = true)
	{
		$visibleObjectLayers =
		[
			LAYER_DOOR_CLOSED,
			LAYER_DOOR_OPEN,
			LAYER_PORTAL,
			LAYER_SIGN,
		];

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
			$bottomEdge = getLargestIndex($this->tiles);
			$leftEdge = 0;
			$rightEdge = 0;

			foreach ($this->tiles as $row)
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

		$emptyColour = $showTiles ? $this->emptyTile->bg : '#000';
		$lastTop = null;
		$lastBottom = null;

		$mapString = '';

		for ($r = $topEdge; $r <= $bottomEdge; $r+=2)
		{
			for ($c = $leftEdge; $c <= $rightEdge; $c++)
			{
				if ($showTiles)
				{
					$top = (isset($this->tiles[$r][$c])) ? $this->tiles[$r][$c]->bg : $emptyColour;
					$bottom = (isset($this->tiles[$r+1][$c])) ? $this->tiles[$r+1][$c]->bg : $emptyColour;
				}
		//*
				if ($showSprites)
				{
					if (!$showTiles)
					{
						$top = $emptyColour;
						$bottom = $emptyColour;
					}

					$top = (isset($this->scenery[$r][$c]) && $this->scenery[$r][$c]->minimap) ? $this->scenery[$r][$c]->sprite->getMainColour() : $top;
					$bottom = (isset($this->scenery[$r+1][$c]) && $this->scenery[$r+1][$c]->minimap) ? $this->scenery[$r+1][$c]->sprite->getMainColour() : $bottom;
				}

				if ($showObjects)
				{
					foreach($visibleObjectLayers as $l)
					{
						if (isset($this->objects[$r][$c][$l]) && !empty($this->objects[$r][$c][$l]->sprite->frames[0]))
						{
							$top = $this->objects[$r][$c][$l]->sprite->getMainColour();
						}
						if (isset($this->objects[$r+1][$c][$l]) && !empty($this->objects[$r+1][$c][$l]->sprite->frames[0]))
						{
							$bottom = $this->objects[$r+1][$c][$l]->sprite->getMainColour();
						}
//						$top = (isset($this->objects[$r][$c][$l]))		? $this->objects[$r][$c][$l]->sprite->getMainColour()	: $top;
//						$bottom = (isset($this->objects[$r+1][$c][$l]))	? $this->objects[$r+1][$c][$l]->sprite->getMainColour()	: $bottom;
					}
				}

		/*/
				$top = (isset($map->scenery[$r][$c]) ) ? $map->scenery[$r][$c]->sprite->getMainColour() : $top;
				$bottom = (isset($map->scenery[$r+1][$c]) ) ? $map->scenery[$r+1][$c]->sprite->getMainColour() : $bottom;
		//*/

				if ($r +1 > $bottomEdge) $bottom = '#000';

				if ($top !== $lastTop || $bottom !== $lastBottom)
				{
					$lastTop = $top;
					$lastBottom = $bottom;

					$mapString .= (isset($lastTop) ? '</span>' : '') . "<span style=\"background-color:{$top};" . ($top === $bottom ? '' : "color:{$bottom};") . '">';
				}

				$mapString .= ($top === $bottom ? '&nbsp;' : '&#x2584');
			}
			$mapString .= '<br>';
		}

		return $mapString;
	}

	public function renderWholeMap()
	{
		$visibleObjectLayers =
		[
			LAYER_DOOR_CLOSED,
			LAYER_DOOR_OPEN,
			LAYER_PORTAL,
			LAYER_SIGN,
			LAYER_CHEST,
			LAYER_PUSHBLOCK,
		];

		$topEdge = 0;
		$bottomEdge = getLargestIndex($this->tiles);
		$leftEdge = 0;
		$rightEdge = 0;

		foreach ($this->tiles as $row)
		{
			$rowWidth = getLargestIndex($row);
			if ($rowWidth > $rightEdge)
				$rightEdge = $rowWidth;
		}

		$margin = 3;

		$topEdge -= $margin;
		$bottomEdge += $margin;
		$leftEdge -= $margin;
		$rightEdge += $margin;

		$mapString = '<span>';

		$lastTile = null;

		for ($r = $topEdge; $r <= $bottomEdge; $r++)
		{
			for ($rt = 0; $rt <= 1; $rt ++)
			{
				for ($c = $leftEdge; $c <= $rightEdge; $c++)
				{
					$sprite = null;
					$tile = (isset($this->tiles[$r][$c])) ? $this->tiles[$r][$c] : $this->emptyTile;

					foreach ($visibleObjectLayers as $layer)
					{
						if (isset($this->objects[$r][$c][$layer]))
						{
							$sprite = $this->objects[$r][$c][$layer]->sprite;
							break;
						}
					}
					if (!$sprite) $sprite = (isset($this->scenery[$r][$c])) ? $this->scenery[$r][$c]->sprite : null;

					if ($tile !== $lastTile)
					{
						$lastTile = $tile;
						$mapString .= "</span><span class=\"{$tile->key}\">";
					}

					$offset = $rt === 0 ? 0 : 3;


					for ($e = $offset; $e <= 2 + $offset; $e++)  // ELEMENT
					{
						if (isset($sprite) && isset($sprite->frames[0][$e]))
						{
							$mapString .= $sprite->frames[0][$e];
						}
						else
						{
							if ($tile->static)
							{
								$mapString .= $tile->chars[$e];
							}
							else
							{
								$te = mt_rand(0, ($tile->scatterDilution));
								$mapString .= isset($tile->chars[$te]) ? $tile->chars[$te] : $tile->chars[0];
							}
						}
					}
				}

				$mapString .= '<br>';
			}
		}

		$mapString .= '</span>';

		return $mapString;
	}
}

class PlayerRegistration
{
	public $n_offset;
	public $w_offset;

	public function __construct()
	{
		global $player;

		$this->n_offset = &$player->n_offset;
		$this->w_offset = &$player->w_offset;
	}
}