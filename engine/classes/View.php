<?php

class View
{
	public $playerId;
	public $mapId;

	// No point setting these two, they get re-written.
	public $height;
	public $width;

	public $baseHeight;
	public $baseWidth;

	public $lightSources = [];

	public $sizeAlteration;

	public $topOffset;
    public $leftOffset;
    public $rightOffset;
    public $bottomOffset;

	public $actionTopOffset;
    public $actionLeftOffset;
    public $actionRightOffset;
    public $actionBottomOffset;

	public $refreshRate = 1;
//	public $refreshRateSet = false;

	public $defaultOverlayColour;
	public $defaultOverlayOpacity;

	public $overlayOpacity;
	public $overlayColour;

	public $tileString;
	public $spriteString;

	public $forceUpdate;

	public $clientSpriteRegister = [];
	public $clientSpriteJson = [];

	public $editorHeight = 45;
	public $editorWidth = 45;

	function __construct()
	{
		global $map;
		global $player;

		$this->playerId	= $player->id;
		$this->mapId	= $map->id;

		$this->baseHeight	= $map->viewHeight;
		$this->baseWidth	= $map->viewWidth;

		// Editor player doesn't get affected by lighting so gtfo.
		if ($player instanceof EditorPlayer2) return;

		$this->defaultOverlayColour		= $map->overlayColour;
		$this->defaultOverlayOpacity	= $map->overlayOpacity;

		$this->overlayColour = $this->defaultOverlayColour;
		$this->overlayOpacity = $this->defaultOverlayOpacity;

		update_overlay($this->overlayColour, $this->overlayOpacity);
	}

	function update()
    {
		global $player;
		global $map;

		$tileString		= null;
		$spriteString	= null;

        $this->height = $this->baseHeight + ($this->sizeAlteration * 2);
        $this->width = $this->baseWidth + ($this->sizeAlteration * 2);

		if (!($player instanceof EditorPlayer || $player instanceof EditorPlayer2)) // EDITOR LINE
		{								// EDITOR LINE
		// Not too big
		if ($this->height > MAX_VIEW_SIZE) $this->height = MAX_VIEW_SIZE;
		if ($this->width > MAX_VIEW_SIZE) $this->width = MAX_VIEW_SIZE;


		// Not too small
		if ($this->height < MIN_VIEW_SIZE) $this->height = MIN_VIEW_SIZE;
		if ($this->width < MIN_VIEW_SIZE) $this->width = MIN_VIEW_SIZE;

		// Make sure it's odd so we can have a center point for the player
        if ($this->height % 2 != 1) $this->height ++;
        if ($this->width % 2 != 1) $this->width ++;

		}											// EDITOR LINE
		else										// EDITOR LINE
		{											// EDITOR LINE
			$this->height = $this->editorHeight;	// EDITOR LINE
			$this->width = $this->editorWidth;		// EDITOR LINE
		}											// EDITOR LINE

		// Set out area for view
        $this->topOffset = $player->n_offset - floor($this->height / 2);
        $this->leftOffset = $player->w_offset - floor($this->width / 2);

        $this->rightOffset = $player->w_offset + floor($this->width / 2);
        $this->bottomOffset = $player->n_offset + floor($this->height / 2);

		// Set out area for idle actions to be triggered
		$this->actionTopOffset = $player->n_offset - ACTION_AREA_RADIUS;
        $this->actionLeftOffset = $player->w_offset - ACTION_AREA_RADIUS;

        $this->actionRightOffset = $player->w_offset + ACTION_AREA_RADIUS;
        $this->actionBottomOffset = $player->n_offset + ACTION_AREA_RADIUS;

		// Run those idle actions
		if (!($player instanceof EditorPlayer || $player instanceof EditorPlayer2)) // EDITOR LINE //XXX
			$map->runIdleActions();

        for($renderedTileRow = $this->topOffset; $renderedTileRow <= $this->bottomOffset; $renderedTileRow++)
        {
            for($renderedTileColumn = $this->leftOffset; $renderedTileColumn <= $this->rightOffset; $renderedTileColumn ++)
            {
				// Get the tile
				if (isset($map->tiles[$renderedTileRow][$renderedTileColumn]))
				{
					$tileString .= $map->tiles[$renderedTileRow][$renderedTileColumn]->key;
				}
				else
				{
					$tileString .= $map->emptyTile->key;
				}

				$nextSpriteKey = null;
				$effectLayer = 0;

				if (isset($map->effects[$renderedTileRow][$renderedTileColumn]))
				{
					$nextSpriteKey = $map->effects[$renderedTileRow][$renderedTileColumn]->sprite->key;
					$effectLayer = $map->effects[$renderedTileRow][$renderedTileColumn]->layer;
					if (--$map->effects[$renderedTileRow][$renderedTileColumn]->frames <= 0)
					{
						$map->effects[$renderedTileRow][$renderedTileColumn]->delete();
					}
				}

				// Get the sprite (if appropriate)
				if (isset($map->objects[$renderedTileRow][$renderedTileColumn]))
                {
					ksort($map->objects[$renderedTileRow][$renderedTileColumn]);
					$object = end($map->objects[$renderedTileRow][$renderedTileColumn]);

					if ($object instanceof AsObject || $object instanceof ObjectConstituent)
					{
						if (!$object->invisible && $object->layer >= $effectLayer) $nextSpriteKey = $object->sprite->key;
					}
					else
					{
//						console_var_dump($object);
						unset($map->objects[$renderedTileRow][$renderedTileColumn]);
					}
                } // No object on this tile. Do we have some scenery?

				if (!$nextSpriteKey && isset($map->scenery[$renderedTileRow][$renderedTileColumn]))
				{
					$nextSpriteKey = $map->scenery[$renderedTileRow][$renderedTileColumn]->sprite->key;
				} // No scenery either. Oh well, just the tile then.

				$spriteString .= $nextSpriteKey ? $nextSpriteKey : '-';
            }
        }

		$this->tileString		= $tileString;
		$this->spriteString		= $spriteString;

		return true;
    }

	public function setOverlay($colour = null, $opacity = 0)
	{
		$this->defaultOverlayColourAlteration = $colour;

		if (!$this->defaultOverlayOpacityAlteration) $this->defaultOverlayOpacityAlteration = 0;
		$this->defaultOverlayOpacityAlteration += $opacity;

		$colour = $this->defaultOverlayColourAlteration !== null ? $this->defaultOverlayColourAlteration : $this->defaultOverlayColour;
		$opacity = $opacity >= 0 ? $opacity : $this->defaultOverlayOpacity + $opacity;
		update_overlay($colour, $opacity);
	}

	public function resetOverlay($updateClient = true)
	{
		$this->defaultOverlayColourAlteration = null;
		$this->defaultOverlayOpacityAlteration = null;

		if ($updateClient) update_overlay($this->colour, $this->opacity);
	}

//	public function setRefreshRate($refreshRate)
//    {
//        if ($this->refreshRate == $refreshRate && $this->refreshRateSet) return;
//        $this->refreshRate = $refreshRate;
//		$this->refreshRateSet = true;
//        $refreshRate = $refreshRate * 1000;
//		update(UPD_REFRESHRATE, $refreshRate);
//    }

	public function addLightSource(lightSource $lightSource)
	{
		$this->lightSources[] = $lightSource;
		//console_var_dump($this->lightSources, '#afa');		//XXX
		$this->refreshLighting();
	}

	public function removeLightSource(lightSource $lightSource)
	{
		unset($this->lightSources[array_search($lightSource, $this->lightSources)]);
		//console_var_dump($this->lightSources, '#faf');		//XXX
		$this->refreshLighting();
	}

	public function refreshLighting()
	{
		$colour = $this->defaultOverlayColour;
		$distance = 0;
		$opacity = $this->defaultOverlayOpacity;

		foreach ($this->lightSources as $lightSource)
		{
			$calcutedOpacity = ($lightSource->absoluteOpacity ? $lightSource->opacity : $this->defaultOverlayOpacity - $lightSource->opacity);
			if ($calcutedOpacity <= $opacity)
			{
				$opacity = $calcutedOpacity;
				$colour = $lightSource->colour;
			}
			$distance += $lightSource->distance;
		}

		console_echo('Setting light to ' . console_swatch($colour) . " with opacity {$opacity}.");		//XXX

		$this->sizeAlteration = $distance;

		$this->overlayColour = $colour;
		$this->overlayOpacity = $opacity;

		update_overlay($colour, $opacity);
	}

	/**
	 *	This function keeps track of which sprites have been sent to the client
	 * and will send a sprite to the client then give it a key. If an identical
	 * sprite has already been sent, it will instead return that keyed version
	 * of that sprite for use in sprited objects.	 *
	 *
	 * @param Sprite $sprite - The sprite you wish to send to the client.
	 * @return Sprite - This sprite has a key and is ready to use.
	 */
	function addClientSprite(Sprite $sprite)
	{
		// First up, try to short-circut the whole function.
		if (isset($sprite->key)											&&
			isset($this->clientSpriteRegister[$sprite->key])			&&
			$this->clientSpriteRegister[$sprite->key]->equals($sprite)	)
			return $sprite;

		// See if we already have it.
		$checked = 0;
		foreach ($this->clientSpriteRegister as $clientSprite)
		{
			$checked++;
			if ($clientSprite->equals($sprite))
			{
				console_echo('Already had that sprite made.', '#aff', CNS_SPRITE);	//XXX
				console_echo("Found after <<#fff>>{$checked}<> checks.", '#aaf', CNS_SPRITE);
				console_echo($clientSprite->key . console_sprite($clientSprite) . ' == ' . $sprite->key . console_sprite($sprite), null, CNS_SPRITE);
				return $clientSprite;
			}
			//else console_echo($clientSprite->key . console_sprite($clientSprite) . ' != ' . $sprite->key . console_sprite($sprite));
		}

		// Sprite is not on the client side, get it a key from the map.
		global $map;
		$spriteKeyIndexChars = $map->spriteKeyIndexChars;
		$charsCount = count($spriteKeyIndexChars);

		$totalSprites = count($map->spriteKey) + count($this->clientSpriteRegister) + 1;

		console_echo("Total sprites = {$totalSprites}", '#faf', CNS_SPRITE);

		$key = $spriteKeyIndexChars[floor($totalSprites / $charsCount)] . $spriteKeyIndexChars[$totalSprites % $charsCount];

		$sprite->key = $key;

		console_echo("Made a new sprite with the key \"{$key}\".", '#faf', CNS_SPRITE);	//XXX
//		try
//		{
//			console_echo(console_sprite($sprite), null, CNS_SPRITE);
//		}
//		catch (Exception $ex)
//		{
			console_var_dump($sprite, '#faa', CNS_SPRITE);
//		}


		$this->clientSpriteRegister[$key] = $sprite;
//		$this->clientSpriteJson[$key] = $sprite->jsonObject;
		$this->clientSpriteJson[$key] = $sprite->getJsonObject();
		update_sprite($key, $sprite, false);
		return $sprite;
	}

	/**
	 *
	 * @global type $player
	 * @param type $n_offset
	 * @param type $w_offset
	 * @return type Array - 0 => n_offset, 1 => w_offset
	 */
	public function viewPosToMapPos ($n_offset, $w_offset = 0)
	{
		global $player;

		if (is_string($n_offset))
		{
			$coOrds = explode(',', $n_offset);
			$n_offset = $coOrds[0];
			$w_offset = $coOrds[1];
		}

		$n_offset = $n_offset + $player->n_offset - floor($this->width / 2);
		$w_offset = $w_offset + $player->w_offset - floor($this->height / 2);

		return [$n_offset, $w_offset];
	}
}

class lightSource
{
	public $distance;
	public $colour;
	public $opacity;
	public $absoluteOpacity;

	public function __construct($distance, $colour = null, $opacity = null, $absoluteOpacity = true)
	{
		$this->distance = $distance;
		$this->colour = $colour;
		$this->opacity = $opacity;
		$this->absoluteOpacity = $absoluteOpacity;
	}
}

