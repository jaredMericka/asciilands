<?php

class Item
{
	use BehaviourCapability;

	public $name			= null;
	public $id;						// Used only as ID on client side to prevent updating entire inventory every time something changes
	public $description		= null;

	public $quantity		= 1;
	public $level			= null;
	public $materials		= null;

	public $durability;
	public $durabilityMax;
	public $isBroken		= false;


	public $INV; // Should contain an INV_# constant. This is for special inventory slots.

	public $sprite			= null;
	public $spriteSet		= null;

	public $goldValue		= 0; // This is NOT the price; this is the item's base value without the added value of behaviours or stats.

	public $ICATs = [];

	public $owner;
	public $SKLS;

	public $cantLose		= false; // Items cannot be pulled while this is true.

	public $finished		= false;

	function __construct($name, $description, $spriteSet, $behaviours = null)
	{
		$this->name = $name;

		$this->description = $description;

		if ($spriteSet instanceof Sprite) $spriteSet = [$spriteSet];
		$this->spriteSet = $spriteSet;

//		if (isset($behaviours)) $this->behaviours = $behaviours;
	}

	function __clone()
	{
		$this->owner	= null;
		$this->SKLS		= null;
//		$this->id = uniqid() . mt_rand(0, 99999);
		$this->id = id(5);
		$this->behaviour__clone();
	}

	function finish()
	{
		if ($this->finished) return true;

		$this->fillGaps();
		$this->consolidate();
		$this->finished = true;
		$this->registerBehaviour();

		$this->id = id(5);

		$this->durability = $this->durabilityMax;

		return true;
	}

	protected function fillGaps()
	{
		if (!isset($this->level))
		{
			$this->level = isset($GLOBALS['mapLevel']) ? $GLOBALS['mapLevel'] : $GLOBALS['map']->level;
		}
	}

	protected function consolidate($problems = [])
	{
		$showName = isset($this->name) ? $this->name : 'something'; //XXX
		console_echo("Running item consolidator on {$showName}", null, CNS_ITEMS);

		// First made sure we have all that we need. If not, assign problems.
		if (!isset($this->name))		$problems[] = 'name missing';
		if (!isset($this->description))	$problems[] = 'description missing';
		if (!isset($this->spriteSet))	$problems[] = 'sprite set missing';

		// Make sure we don't have any problems.
		$this->problemCheck($problems);

		// Do stuff and set stuff and all that
		$this->name = ucwords($this->name);
		$this->sprite = $this->spriteSet[SPRI_DEFAULT];

//		$this->registerBehaviour();

		// We got here so things must be ok. Consolidate and return true.
		return empty($problems);
	}

	function problemCheck($problems)
	{
		if (!empty($problems))
		{
			$problems = implode(',', $problems);
			trigger_error("Couldn't consolidate item \"{$this->name}\"; {$problems}!", E_USER_ERROR);
		}
	}

	/**
	 * If you're running calculations on an item based on the stats stored in its
	 * materials, use this list of materials. It only returns one of each material
	 * present in the item unless it only has one material in which case it returns
	 * two of said material.
	 *
	 * @return Array of Material objects
	 */
	function getWorkingMaterials()
	{
		$workingMaterials = [];

		foreach ($this->materials as $material)
			if (!in_array($material, $workingMaterials))
				$workingMaterials[] = $material;

		if (empty($workingMaterials))
		{
			console_echo("{$this->name} hasn't got any materials but it's trying to apply materials mods. Look into that.", '#faa', CNS_ITEMS);
			return [];
		}

		// If the item has only one material, double its effectiveness.
		if (count($workingMaterials) === 1) $workingMaterials[] = $workingMaterials[0];

		return $workingMaterials;
	}

	public function inspect()
	{
		update_itemInfo($this);

		//////// CONSOLE ONLY //////////

		console_echo("{$this->name}'s hidden stats:", '#ffa', CNS_ITEMS);

		if (isset($this->DSs[DS_LUCK])) console_echo("Luck : {$this->DSs[DS_LUCK]}", '#aaf');
		if (isset($this->materials))									//XXX
		{																//XXX
			console_echo("{$this->name} is made of:", '#aaf', CNS_ITEMS);			//XXX
			foreach ($this->materials as $role => $material)			//XXX
			{															//XXX
				console_echo("{$role} - {$material->name}", '#aaf', CNS_ITEMS);	//XXX
			}															//XXX
		}																//XXX
		console_echo('Gold value: '. $this->getPrice(CUR_GOLD), '#aaf', CNS_ITEMS);//XXX
	}

	public function useItem()
	{
		console_echo("Using {$this->name}", CNS_ITEMS);
		$this->onUse();
	}

	function delete($quantity = null)
	{
		$this->owner->inventory->pullItem($this, $quantity);
	}

	public function getPrice($CUR = null, $sa = null)
	{
		$goldValue = $this->goldValue;
//		$goldValue = isset($this->PROPs[PROP_VALUE]) ? $this->PROPs[PROP_VALUE] : 0;

		console_echo("Price of {$this->name} = {$goldValue}", '#fda', CNS_ITEMS);

		if (isset($this->DMGs))
		{
			$goldValue += array_sum($this->DMGs) * 0.1;
			console_echo('+ ' . (array_sum($this->DMGs) * 0.1) . ' (damage)', '#fda', CNS_ITEMS);
		}

		if (isset($this->DMGs_def))
		{
			$goldValue += array_sum($this->DMGs_def) * 0.1;
			console_echo('+ ' . (array_sum($this->DMGs_def) * 0.1) . ' (defence)', '#fda', CNS_ITEMS);
		}

		if (isset($this->DSs))
		{
			$goldValue += array_sum($this->DSs) * 0.02;
			console_echo('+ ' . (array_sum($this->DSs) * 0.02) . ' (attributes)', '#fda', CNS_ITEMS);
		}

		$bhvGold = 0; //XXX
		foreach ($this->behaviours as $behaviours)
		{
			foreach ($behaviours as $behaviour)
			{
				$goldValue += $behaviour->goldValue;
				console_echo(' - ' . $behaviour->goldValue . ' (' . get_class($behaviour) . ')', '#ffa', CNS_ITEMS);
				$bhvGold += $behaviour->goldValue;	//XXX
			}
		}
		console_echo("+ {$bhvGold} (behaviour)", '#fda', CNS_ITEMS);

		console_echo("Total: $goldValue\n", '#ffa', CNS_ITEMS);
		if ($this->owner === $GLOBALS['player']) $goldValue = $goldValue * PLAYER_SALE_MULTIPLYER;

		// Convert to the appropriate currency
		$CUR_value = $CUR ? convertCurrency($goldValue, CUR_GOLD, $CUR) : $goldValue;

		// Add the special rate if there is one
		if ($sa) $CUR_value = sa($CUR_value, $sa);

		// Round it up or down depending on how mean we want to be
		$CUR_value = ($this->owner instanceof Player) ? floor($CUR_value) : ceil($CUR_value);

		// Done
		return $CUR_value;
	}

	public function getAjaxObject($CUR = null, $sa_priceMod = null)
	{

		$i = new stdClass();

		$i->id = $this->id;

		if ($this->quantity < 1)
		{
			$i->delete = true;
			return $i;
		}

		$i->name		= $this->name;
		$i->quantity	= $this->quantity;
		$i->equipped	= ($this instanceof Equipment && $this->isEquipped);
		$i->INV			= $this->INV;
		$i->SKLS		= $this->SKLS;

		if (isset($this->level))
		{
			$i->level = $this->level;
		}

		if (isset($CUR))
		{
			$i->price = $this->getPrice($CUR, $sa_priceMod);
		}

		return $i;
	}

	public function equals(Item $item)
	{
		if (get_class($item) !== get_class($this)) return false;

		if ($this->name			!== $item->name			) return false;
		if ($this->description	!== $item->description	) return false;
		if ($this->level		!== $item->level		) return false;
//		if ($this->materials	!== $item->materials	) return false;

		return true;
	}

	/**
	 * Custom equals lets you determine whether or not two items are equal based
	 * only on the properties you pass in.
	 * If you want to compare on class, make sure "class" is all lower case.
	 *
	 * @param Item $item The item for comparison
	 * @param type $properties The properties that you want to check against
	 * @return boolean <b>TRUE</b> if all properties match, <b>FALSE</b> otherwise
	 */
	public function cEquals(Item $item, $properties)
	{
		console_echo("Running cEquals on {$item->name} and {$this->name}:", '#afa', CNS_ITEMS);
		foreach ($properties as $property)
		{
			if ($property === 'class')
			{
				if (get_class($item) !== get_class($this)) //return false;
				{
					console_echo('class doesn\'t match.', '#aaa', CNS_ITEMS);
					return false;
				}
			}
			else
			{
				if (!isset($this->$property, $item->$property))
				{
					console_echo("{$property} not present in both", '#aaa', CNS_ITEMS);
					return false; // Oosenupt - not sure about this.
				}
				if ($this->$property !== $item->$property)
				{
					console_echo("{$property} not equal in both", '#aaa', CNS_ITEMS);
					return false;
				}
			}
		}

		return true;
	}

	public function damageItem ()
	{
		if ($this->isBroken) return;

		$this->durability -= 1;

		$this->onDamage();

		if ($this->durability < 1) $this->breakItem();
	}

	public function breakItem ()
	{
		if ($this->isBroken) return;

		$this->durability = 0;
		$this->durabilityMax = round($this->durabilityMax * 0.6, 0, PHP_ROUND_HALF_UP);

		if ($this->durabilityMax > MIN_DURABILITY) $this->durabilityMax = MIN_DURABILITY;

		$this->onBreak();
	}

	public function onClick($UIN)
	{
		global $player;

		if ($this->owner === $player) // Handling click on inventory item
		{
			$quantity = 1;
			switch ($UIN)
			{
				case UIN_CLICK:
					$this->inspect();
					break;
				case UIN_CTRL_ALT_RIGHT_CLICK:
					$quantity = $this->quantity;
				case UIN_CTRL_RIGHT_CLICK:
					if (isset($player->engagement->inventory) && ($player->engagement->inventory->lootable))
					{
						$player->engagement->inventory->add($player->inventory->pullItem($this, $quantity));
					}
					break;
				case UIN_RIGHT_CLICK:
					$this->useItem();
					break;
			}
		}
		elseif ($player->engagement && $this->owner === $player->engagement)	// Handling click on engagement's item
		{
			$quantity = 1;
			switch ($UIN)
			{
				case UIN_CLICK:
					$this->inspect();
					break;
				case UIN_CTRL_RIGHT_CLICK;
					$quantity = $this->quantity;
					console_echo("Taking {$quantity} {$this->name}s", null, CNS_ITEMS);
				case UIN_RIGHT_CLICK:
					{
						$player->inventory->add($player->engagement->inventory->pullItem($this, $quantity));
					}
					break;
			}
		}
	}

	public function __debugInfo ()
	{
		return [
			'name' => $this->name,
			'description' => $this->description,
			'sprite' => $this->sprite,
			'level' => $this->level,
			'quantity' => $this->quantity,
			'durability' => "{$this->durability}/{$this->durabilityMax}",
		];
	}

	public function onTransform($TFI) { return $this; }

    public function onCollect()	{ $this->executeBehaviours(__FUNCTION__); }
	public function onDrop()	{ $this->executeBehaviours(__FUNCTION__); }
	public function onUse()		{ $this->executeBehaviours(__FUNCTION__); }
	public function onDamage()	{ $this->executeBehaviours(__FUNCTION__); }
	public function onBreak()	{ $this->executeBehaviours(__FUNCTION__); }
}

abstract class ItemBehaviour extends Behaviour
{
	public $goldValue			= 1;
	public $ICATs				= [];

	public $onCollect			= null;
	public $onDrop				= null;
	public $onUse				= null;
	public $onDamage			= null;
	public $onBreak				= null;

	public function onCollect	() { }
	public function onDrop		() { }
	public function onUse		() { }
	public function onDamage	() { }
	public function onBreak		() { }
}