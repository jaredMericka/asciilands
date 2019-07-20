<?php

abstract class AsObject

{
	use behaviourCapability;
	use spriteManipulation;

	public $name;
	public $id;		// Uh-oh, this is getting weird now.

	// Sprite stuff is below in the SpriteManipulation trait.

	public $n_offset;
	public $w_offset;

	public $layer;
	public $TPL_passables = [TPL_OPENGROUND];

	public $constituents;

	public $lastUpdated;

	public $engagement	= null;
	public $enagedAt	= null; // Time of engagement

	public $permitEntryDefault = false;
	public $permitEntry;

	public $stationary	= false;
	public $invisible	= false;

	public $finished	= true;

	// SRZs is a list of variable names that need to be re-zipped at the end of the frame.
	public $SRZs = [];

	function __construct($name, $spriteSet, $layer)
	{
		$this->name	= ucwords($name);
		$this->id = id(8);

		if (!$spriteSet)
		{
			$spriteSet = [new Sprite([])];
			$this->invisible = true;
		}

		if ($spriteSet instanceof Sprite) $spriteSet = [$spriteSet];

		$this->spriteSet = $spriteSet;
		if (!isset($this->spriteSet[SPRI_DEFAULT])) $this->spriteSet[SPRI_DEFAULT] = reset($spriteSet);

		$this->sprite = $this->spriteSet[SPRI_DEFAULT];

		$this->layer = $layer;

		$this->lastUpdated = $_SERVER['REQUEST_TIME_FLOAT'];

//		$this->registerBehaviour();

		if ($this->constituents)
		foreach ($this->constituents as &$constituents)
		{
			foreach ($constituents as &$constituent)
			{
				$constituent->owner = $this;
			}
		}

        $type = get_class($this);		//XXX
        console_echo("Placing <<#afa>>{$type}<> -> <<#aff>>{$this->name}<>",'#fff');		//XXX
//		foreach ($this->behaviours as $behaviour)				//XXX
//		{														//XXX
////			console_echo(" - {$behaviour->description}");		//XXX
//		}														//XXX
	}

	public function __toString()
	{
		return $this->name;
	}


	function __clone()
	{
		$this->id = id();

		if (isset($this->inventory))
		{
			$this->inventory = new Inventory($this);
		}

		if (isset($this->constituents))
		{
			$this->constituent__clone();
		}

		$this->behaviour__clone();
	}

	public function __get($name)
	{
		if (isset($this->$name))
		{
			return $this->$name;
		}
		else
		{
			$SRZ = "SRZ_{$name}";

			if (isset($this->$SRZ))
			{
				$this->$name = unserialize($this->$SRZ);
				$this->SRZs[] = $name;
			}
		}
	}

//	public function __sleep()
//	{
//		foreach ($this->SRZs as $var)
//		{
//			console_echo("Putting {$this->name}'s {$name} variable to sleep.", '#afa', CNS_SRZ);
//			$SRZ = "SRZ_{$name}";
//			$this->$SRZ = serialize($this->$var);
//		}
//
//		$this->SRZs = [];
//
//		return array_keys(get_object_vars($this));
//	}

	public function collide(AsObject $target, $DIR)
	{
		$target->permitEntry = $target->permitEntryDefault;
		$this->onCollision($target, $DIR);
		$target->onReaction($this, $DIR);

		console_echo ("{$this->name} has collided with {$target->name}.");		//XXX

		// Objects will be assumed solid unless otherwise noted. Denting access
		// by default is always going to be the safer option.
		$permitEntry = $target->permitEntry;
		return $permitEntry;
	}

	/**
	 * Call destroy on an object if you just want it gone.
	 * No behaviours, no nothing.
	 */
	public function destroy()
	{
		global $map;

		$map->destroyObject($this);
	}

	/**
	 * An object-centric shortcut to the map's object movement method.
	 * @param type $n_offset The new north offset for the object
	 * @param type $w_offset The new west offset for the object
	 * @param type $force Allows the movement to over-ride normal object collision rules (does not over-ride stationary boolean)
	 * @return type <b>TRUE</b> if moved, <b>FALSE</b> if not.
	 */
	public function move($n_offset, $w_offset, $force = false)
	{
		if ($this->stationary) return;

		global $map;

		return $map->moveObject($this, $n_offset, $w_offset, $force);
	}

	function moveInDirection($DIR)
	{
		if (isset($this->spriteSet[$DIR]))
		{
			$this->setSPRI($DIR);
		}

		$offsets = directionToOffset($DIR);
		return $this->move(
			$this->n_offset + $offsets[0],
			$this->w_offset + $offsets[1]
		);
	}

	public function changeTo($newObject)
	{
		global $map;

		$newObject->n_offset = $this->n_offset;
		$newObject->w_offset = $this->w_offset;

		if ($this->constituents)
		{
			foreach ($this->constituents as $constituents)
			{
				foreach ($constituents as $constituent)
				{
					$constituent->owner = $newObject;
				}
			}
		}

		$newObject->constituents = $this->constituents;

		$map->replaceObject($this->n_offset, $this->w_offset, $this->layer, $newObject);
	}

	public function pause($seconds)
	{
		$this->nextIdleAction += $seconds;
	}

	public function changeLayer($layer)
	{
		global $map;

		if ($this->constituents) $this->constituentClear();

		$this->layer = $layer;

		if ($this->constituents) $this->constituentPlace();

		$map->resortObjectLayers($this->n_offset, $this->w_offset);
	}

//	public function engage(Player $player)
	public function engage()
	{
		global $player;
		// See if this is ready to engage with a player or if it is otherwise occupied
		if ($this->engagement !== null && $this->engagement !== $player)
		{
			update_thoughts("{$this->name} is not available right now.");
			console_echo($this->engagement->name);
			return;
		}

		if ($player->engagement !== null)
		{
			$player->engagement->disengage();
		}

		console_echo("{$player->name} has engaged {$this->name}.", '#aaa');		//XXX
		// Establish engagement at both ends
		$player->engagement = $this;
		$this->engagement = $player;
		$this->enagedAt = $_SERVER['REQUEST_TIME_FLOAT'];
		// Run engagement behaviours after enagagement has been established
		$this->onEngage($player);
	}

	public function disengage()
	{
		global $player;

		if (!$this->engagement)
		{
			console_echo("{$this->name} is attempting to disengage from literally nothing.");		//XXX
			return;
		}

		if ($this->enagedAt == $_SERVER['REQUEST_TIME_FLOAT'])
		{
			console_echo('Discarding disengagement in same turn as engagement.');		//XXX
			return;
		}
		console_echo("{$this->engagement->name} has disengaged {$this->name}.", '#aaa'); //XXX
		// Run behaviours before terminating engagement
		$this->onDisengage($player);
		// Terminate engagement at both ends
		$this->engagement->engagement = null;
		$this->engagement = null;

		console_echo('On disengage complete', '#faa');
	}

	public function isAdjacentTo(AsObject $object)
	{
		$n_disparity = $object->n_offset - $this->n_offset;
		$w_disparity = $object->w_offset - $this->w_offset;

		$n_disparity = ($n_disparity < 0 ? 0 - $n_disparity : $n_disparity);
		$w_disparity = ($w_disparity < 0 ? 0 - $w_disparity : $w_disparity);

		console_echo("{$this->name} is " . (($n_disparity + $w_disparity <= 1) ? '' : 'not ') . "adjacent to {$object->name}", '#acc');		//XXX

		return ($n_disparity + $w_disparity <= 1);
	}

	public function distanceFrom(AsObject $object)
	{
		if (empty($object)) return INF;

		$n_disparity = $object->n_offset - $this->n_offset;
		$w_disparity = $object->w_offset - $this->w_offset;

		$n_disparity = ($n_disparity < 0 ? 0 - $n_disparity : $n_disparity);
		$w_disparity = ($w_disparity < 0 ? 0 - $w_disparity : $w_disparity);

		console_echo("{$this->name} is " . ($n_disparity + $w_disparity) . " paces from {$object->name}", '#aca');		//XXX

		return $n_disparity + $w_disparity;
	}

	public function equals(AsObject $object)
	{
		return
		$object->name == $this->name &&
		$object->sprite == $this->sprite &&
		get_class($object) == get_class($this);
	}

	public function cEquals(AsObject $object, $properties)
	{
		console_echo("Running cEquals on {$object->name} and {$this->name}:", '#afa');
		foreach ($properties as $property)
		{
			if ($property === 'class')
			{
				if (get_class($object) !== get_class($this)) //return false;
				{
					console_echo('class doesn\'t match.', '#aaa');
					return false;
				}
			}
			else
			{
				if (!isset($this->$property, $object->$property))
				{
					console_echo("{$property} not present in both", '#aaa');
					return false; // Oosenupt - not sure about this.
				}
				if ($this->$property !== $object->$property)
				{
					console_echo("{$property} not equal in both", '#aaa');
					return false;
				}
			}
		}
		console_echo('Objects are cEqual!', '#afa');
		return true;
	}

	public function constituentPlace()
	{
		global $map;

		foreach ($this->constituents as $rel_n_offset => $constituents)
		{
			foreach ($constituents as $rel_w_offset => $constituent)
			{
				$map->objects[$this->n_offset + $rel_n_offset][$this->w_offset + $rel_w_offset][$this->layer] = $constituent;
			}
		}
	}

	public function constituentClear()
	{
		global $map;

		foreach ($this->constituents as $rel_n_offset => $constituents)
		{
			foreach ($constituents as $rel_w_offset => $constituent)
			{
				unset($map->objects[$this->n_offset + $rel_n_offset][$this->w_offset + $rel_w_offset][$this->layer]);
			}
		}
	}

	public function constituentCollide($n_offset, $w_offset)
	{
		global $map;

		foreach ($this->constituents as $rel_n_offset => $constituents)
		{
			foreach ($constituents as $rel_w_offset => $constituent)
			{
				if (!$map->collideByLocation($constituent, $n_offset + $rel_n_offset, $w_offset + $rel_w_offset))
				{
					return false;
				}
			}
		}
		return true;
	}

	public function constituent__clone()
	{
		$newConstituents = [];
		foreach ($this->constituents as $rel_n_offset => $constituents)
		{
			$newConstituents[$rel_n_offset] = [];
			foreach ($constituents as $rel_w_offset => $constituent)
			{
				$newConstituents[$rel_n_offset][$rel_w_offset] = clone $constituent;
				$newConstituents[$rel_n_offset][$rel_w_offset]->owner = $this;
			}
		}
		$this->constituents = $newConstituents;
	}

	public function __debugInfo()
	{
		return [
			'name' => $this->name,
			'sprite' => $this->sprite,
			'spriteSet' => $this->spriteSet,
			'n_offset' => $this->n_offset,
			'w_offset' => $this->w_offset,
			'constituents' => $this->constituents,
			'behaviours' => $this->behaviours,
		];
	}

	function onIdle()								{ $this->executeBehaviours(__FUNCTION__); }
	function onCollision(AsObject $receiver, $DIR)	{ $this->executeBehaviours(__FUNCTION__, $receiver,		$DIR); }
	function onReaction(AsObject $instigator, $DIR)	{ $this->executeBehaviours(__FUNCTION__, $instigator,	$DIR); }

	function onEngage(Player $player)				{ $this->executeBehaviours(__FUNCTION__, $player); }
	function onDisengage(Player $player)			{ $this->executeBehaviours(__FUNCTION__, $player); }

	function onMove($new_n_offset, $new_w_offset)	{ $this->executeBehaviours(__FUNCTION__, $new_n_offset, $new_w_offset); }

	function onLoseItem(Item $item)					{ $this->executeBehaviours(__FUNCTION__, $item); }
	function onGainItem(Item $item)					{ $this->executeBehaviours(__FUNCTION__, $item); }
}

abstract class ObjectBehaviour extends Behaviour
{
	public $onIdle		= null;
	public $onCollision	= null;
	public $onReaction	= null;
	public $onEngage	= null;
	public $onDisengage	= null;
	public $onMove		= null;

	public function onIdle		() { }
	public function onCollision	(AsObject $receiver,		$DIR)	{ }
	public function onReaction	(AsObject $instigator,	$DIR)	{ }

	public function onEngage	(Player $player) { }
	public function onDisengage	(Player $player) { }

	public function onMove	($new_n_offset, $new_w_offset) { }

	public function onLoseItem (Item $item) { }
	public function onGainItem (Item $item) { }
}

class ObjectConstituent
{
	use spriteManipulation;

	public $owner;

	public function __construct ($spriteSet)
	{
		if (!$spriteSet)
		{
			$spriteSet = [new Sprite([])];
			$this->invisible = true;
		}

		$this->spriteSet = $spriteSet;
		$this->sprite = isset($spriteSet[SPRI_DEFAULT]) ? $spriteSet[SPRI_DEFAULT] : reset($spriteSet);
	}

	public function __get($name)
	{
		if (isset($this->owner->$name))
		{
			return $this->owner->$name;
		}
	}

	public function __debugInfo()
	{
		return [
			'sprite' => $this->sprite,
			'spriteSet' => $this->spriteSet,
		];
	}
}

trait spriteManipulation
{
	public $sprite;
	public $spriteSet = [];
	public $currentSPRI = SPRI_DEFAULT;

	public $effects = [];
	public $spriteSet_effects = [];

	public $spriteSetAnnex = [];
	public $invisible = false;


	public function setSPRI($SPRI)
	{
		if ($SPRI === $this->currentSPRI)
		{
			console_echo("Cancelling sprite change by index (\"{$SPRI}\") on {$this->name} because it was the current SPRI.", null, CNS_SPRITE);
			return;
		}

		if (isset($this->constituents) && $this->constituents)
		{
			foreach ($this->constituents as $constituents)
			{
				foreach ($constituents as $constituent)
				{
					console_echo('Changing sprite of a constituent', '#fff', CNS_SPRITE);
					$constituent->setSPRI($SPRI);
				}
			}
		}

		if (isset($this->spriteSet[$SPRI]))
		{
			if ($this->effects)
			{
				console_echo('Effects detected.', null, CNS_SPRITE);
				if (isset($this->spriteSet_effects[$SPRI]))
				{
					console_echo('Effect augmented sprite found in effect sprite array.', null, CNS_SPRITE);
					$newSprite = $this->spriteSet_effects[$SPRI];
				}
				else
				{
					console_echo('Creating new effect augmented sprite.', null, CNS_SPRITE);
					$newSprite = $this->applyEffectsToSprite($this->spriteSet[$SPRI]);
					console_echo(console_sprite($newSprite));
					$this->spriteSet_effects[$SPRI] = $newSprite;
				}
			}
			else
			{
				$newSprite = $this->spriteSet[$SPRI];
			}

			unset($this->sprite);
			$this->sprite = $newSprite;
			$this->currentSPRI = $SPRI;
			console_echo('Changed sprite to ' . console_sprite($this->sprite), null, CNS_SPRITE);
		}
		else console_echo('Failed to find sprite in sprite index. Doing nothing.', null, CNS_SPRITE);
	}


	public function addSpriteEffect ($effect)
	{
		if ($effect instanceof Sprite)	//XXX
		{		//XXX
			console_echo("Adding effect sprite to <<#fff>>{$this->name}<>" . console_sprite($effect), '#aaa', CNS_SPRITE);
		}		//XXX
		else	//XXX
		{		//XXX
			console_echo("Adding effect colour to <<#fff>>{$this->name}<>" . console_swatch($effect), '#aaa', CNS_SPRITE);
		}		//XXX

		$this->deleteSpriteEffect($effect);
		array_unshift($this->effects, $effect);

		$this->spriteSet_effects = [];

		$SPRI = $this->currentSPRI;
		$this->currentSPRI = null;
		$this->setSPRI($SPRI);
	}

	public function removeSpriteEffect ($effect)
	{
		if ($effect instanceof Sprite)	//XXX
		{		//XXX
			console_echo("Removing effect sprite to <<#fff>>{$this->name}<>" . console_sprite($effect), '#aaa', CNS_SPRITE);
		}		//XXX
		else	//XXX
		{		//XXX
			console_echo("Removing effect colour to <<#fff>>{$this->name}<>" . console_swatch($effect), '#aaa', CNS_SPRITE);
		}		//XXX

		$this->deleteSpriteEffect($effect);

		$this->spriteSet_effects = [];

		$SPRI = $this->currentSPRI;
		$this->currentSPRI = null;
		$this->setSPRI($SPRI);
	}

	/***
	 * Not to be confused with removeEffect()!
	 *
	 * This function is only for deleting whereas removeEffect will also do other
	 * sprite management stuff. Note that deleteSprite is also called from inside
	 * addEffect() to make sure added effects are always on top and without
	 * duplicates.
	 */
	protected function deleteSpriteEffect($effect)
	{
		if ($effect instanceof Sprite)
		{
			foreach ($this->effects as $key => $exEffect)
			{
				if ($exEffect instanceof Sprite && $effect->equals($exEffect))
				{
					unset($this->effects[$key]);
					return;
				}
			}
		}
		else
		{
			foreach ($this->effects as $key => $exEffect)
			{
				if ($effect === $exEffect)
				{
					unset($this->effects[$key]);
					return;
				}
			}
		}
	}

	public function applyEffectsToSprite(Sprite $sprite)
	{
		global $view;

		$painted = false;
		$augmented = false;

		foreach ($this->effects as $effect)
		{
			if ($effect instanceof Sprite)
			{
				if (!$augmented)
				{
					$sprite = $sprite->augment($effect);
					$augmented = true;
					continue;
				}
			}
			elseif (is_string($effect) && !$painted)
			{
				$sprite = paintSprite($sprite, $effect);
				$painted = true;
				continue;
			}
			break;
		}

//		return $sprite;
		return $view->addClientSprite($sprite); // Oosenupt?
	}
}