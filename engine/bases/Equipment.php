<?php

abstract class Equipment extends Item
{
	public $EQP;	// Slot
	public $isEquipped		= false;

	public $DSs				= null;
	public $DSs_req			= null;

	public $durabilityMax = 100;

	// Generator variables:
	// Counts and mods and yadda yadda yadda
	public $DSs_mod			= 1;
	public $DSs_req_mod		= 1;

	public $DSs_count		= 1;
	public $DSs_req_count	= 1;

	public $durability_mod	= 1;

	// This is one of the base DSs (Strength, Agility, Magic, Intelligence, Social).
	public $DS_base			= null;
	// If you want to reduce the scope of the possibilities for the DS_base variable, define the choices in the DS_base_choices array.
	public $DS_base_choices	= null;

	// This is the name pulled out of the key of the shopping list used to assign the materials.
	// Used for name generation purposes.
	public $noun;

	abstract function getShoppingLists();
	abstract function getSpriteSet();
	abstract function getDescription();

	function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->level = $level;

		parent::__construct($name, $description, $spriteSet);
	}

	public static function constructFromMask (Mask $mask)
	{
		if (isset($mask->class))
		{
			if (is_array($mask->class))
			{
				$class = $mask->class[array_rand($mask->class)];
			}
			else
			{
				$class = $mask->class;
			}

			$equipment = new $class();

//			console_var_dump($equipment, '#aaa', CNS_ITEMS);

			if (!($equipment instanceof Equipment)) return null;

			foreach (get_object_vars($equipment) as $name => $val)
			{
				if (isset($mask->$name)) $equipment->$name = $mask->$name;
			}

			$equipment->finish();

			return $equipment;
		}
		else return null;
	}

	// There are three main object closure functions: fillGaps(), consolidate()
	// and applyQuirks().
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// fillGaps() is designed to make sure that every type to advantage associated
	// with a piece of equipment as been added to each instance whether it has been
	// explicitly added or not.
	//
	// consolidate() is designed to tie a piece of equipment together so that the
	// equipment and its properties "make sense". consolidate() needs to be called
	// after fillGaps() because typically, consolidate() reads or manipulates the
	// values set by the fillGaps() function. consolidate() should also check for
	// essential properties that are not set.
	//
	// The key difference between fillGaps() and consolidate() is that fillGaps()
	// needs only the absolute minimum amount of information to be loaded into the
	// equipment for it to be run but consolidate() needs all the "gaps to be filled"
	// so that it can draw all the details together.
	//
	// applyQuirks() is designed to allow automated "finishing touches" to an item
	// to keep things interesting (e.g., an item that does lightning damage might
	// use applyQuirks() to make it so that there's a 50% chance that this damage
	// will turn into a behaviour that applies that damage over time).
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// When overriding fillGaps(), a call to the parent function must be made at
	// the START of the overriding function.
	// When overriding consolidate(), a call to the parent function must be made
	// at the END of the overriding function. consolidate() must also accept an
	// array of strings as a parameter and return the same array of strings. The
	// strings should describe problems or warnings detected in the considication
	// process.
	// When overriding applyQuirks(), it's best to call it at the <b>END</b> of
	// the override to make sure that the individual quirks of the item itself
	// have the highest chance of being triggered.

	abstract protected function applyQuirks();

	/**
	 * fillGaps() should be written assuming that only the minimum developer-specified
	 * values have been set on the item and that only the gaps need to be filled.
	 * A call to the parent function <b>MUST</b> be placed at the <b>START<b/> of
	 * any overrides.
	 */
	protected function fillGaps()
	{
		global $DS_types;

		parent::fillGaps();

		if (!isset($this->DS_base) || !isset($DS_types[$this->DS_base]))
		{
			if (isset($this->DS_base_choices))
			{
				$this->DS_base = $this->DS_base_choices[array_rand($this->DS_base_choices)];
			}
			else
			{
				$this->DS_base = array_rand($DS_types);
			}
		}

		$this->assignMaterials();

		if (!isset($this->DSs))			$this->DSs = $this->generateDSs();
		if (!isset($this->DSs_req))		$this->DSs_req = $this->generateDSs_req();

		if (!isset($this->spriteSet))	$this->spriteSet = $this->getSpriteSet();
	}

	/**
	 * consolidate() should be written assuming that all the equipment's properties
	 * have been set and just need tidying up and linking together.
	 * A call to the parent function <b>MUST</b> be placed at the <b>END</b> of
	 * any overrides and must pass in the $problems array (and make use of the returned
	 * array when the parent function has finished executing).
	 *
	 * @param array $problems - an array of strings describing detected problems.
	 * @return array - the same array with any additional problem descriptions added to it.
	 */
	protected function consolidate($problems = [])
	{
		console_echo("Running equipment consolidator on {$this->name}");

		$this->problemCheck($problems);

		$this->applyMaterialMods();

		if (!isset($this->name))		$this->name = $this->getName();
		if (!isset($this->description))	$this->description = '';
		if (!isset($this->spriteSet))	$this->spriteSet = $this->getSpriteSet();

		$this->applyQuirks();

		$this->description .= $this->getDescriptionAnnex(); // If a description is set, this description will add do it, not replace it.

		return parent::consolidate($problems);
	}

	function assignMaterials()
	{
		console_echo("Assigning materials for {$this->name}");

		$shoppingLists = $this->getShoppingLists();
		shuffle_assoc($shoppingLists);

		if (isset($this->materials))
		{
			if ($this->buildMaterialList()) return;
		}
		// If we are here it means that the material list is either missing or
		// fucked so we need to get a working material list from the map and
		// merge with with whatever we have.

		$materialAnnex = isset($GLOBALS['mapMaterials']) ? $GLOBALS['mapMaterials'] : $GLOBALS['map']->materials;

		if (!is_array($this->materials)) $this->materials = [];

		$this->materials = array_merge($this->materials, $materialAnnex);

		if ($this->buildMaterialList()) return;

		trigger_error('Unable to find / assign appropriate materials to make a ' . get_class($this));
	}

	/**
	 *	This function exists solely to prevent code repetition in the function above.
	 *	Never call this outside of assignMaterials().
	 */
	private function buildMaterialList ()
	{
		$shoppingLists = $this->getShoppingLists();

		foreach ($shoppingLists as $listNoun => $shoppingList)
		{
			$listMaterials = getRandomObjectsByClassList($this->materials, $shoppingList);

			if ($listMaterials)
			{
				$this->noun = trim($listNoun, '1234567890');

				$this->materials = $listMaterials;
				return true;
			}
		}

		return false;
	}

	function getRequiredStats($statName)
	{
		$base = $this->level * $this->{"{$statName}_mod"};

		$workingMaterials = $this->getWorkingMaterials();
		$requiredStats = [];
		foreach ($workingMaterials as $material)
		{
			if (!is_array($material->$statName)) continue;
			foreach ($material->$statName as $stat => $value)
			{
				if (is_string($value)) $requiredStats[$stat] = $base;
			}
		}
		console_echo("{$this->name} - {$statName} required:", '#faa');
//		console_var_dump($requiredStats, '#fca');
		return $requiredStats;
	}

	/**
	 * Sets a semi-random group of attributes to the base level appropritate for
	 * the equiptment type and level. Attributes will be weighted loosely towards
	 * one of the three stat types. The base of the weighted stat type is returned.
	 *
	 * @return DS returns a base DS constant (strength, agility or magic).
	 */
	function generateDSs($number = null)
	{
//		global $DS_types;
		global $DS_types_core;
		global $DS_types_subs;
		global $DS_typed;
		global $DS_global;

		$DS_extra = $DS_typed + $DS_global;

		$base = (10 + ($this->level ^ 1.2)) * $this->DSs_mod;
		if (!isset($number)) $number = 4;

		foreach ($DS_types_core[$this->DS_base] as $DS)
		{
			$DSs[$DS] = $base;
		}

		for ($i = count($DSs) - getNuancedValue($number, 30); $i > 0; $i--)
		{
			unset($DSs[array_rand($DSs)]);
		}

		$base = ceil($base / 2);
		for ($i = mt_rand(1, $number); $i > 0; $i--)
		{
			$newDS = array_rand(mt_rand(0,1) ? $DS_extra : $DS_types_subs);
			if (!isset($DSs[$newDS])) $DSs[$newDS] = $base;
		}

		return $this->getRequiredStats('DSs') + $DSs;
	}

	function generateDSs_req()
	{
		$base = ($this->level ^ 1.2);
		$reqs = $this->getRequiredStats('DSs_req') + [$this->DS_base => getNuancedValue($base, 10) + 90];

		foreach ($reqs as &$req) $req *= $this->DSs_req_mod;
		return $reqs;
	}

	function getName()
	{
		return $this->noun;
	}

	function getDescriptionAnnex()
	{
		$description = ' ' . ucfirst($this->getDescription());

		$base = $this->level * $this->DSs_mod * 2;
		if ($this->DSs && max($this->DSs) >= $base)
		{
			global $DS_names;

			$description .= " A boon to {$DS_names[array_search(max($this->DSs), $this->DSs)]}.";
		}

		if (isset($this->DMGs_def))
		{
			$base = $this->level * $this->DMGs_def_mod * 2;
			if ($this->DMGs_def && max($this->DMGs_def) >= $base)
			{
				global $DMG_DMGDL_names;

				$description .= " Highly effective against {$DMG_DMGDL_names[array_search(max($this->DMGs_def), $this->DMGs_def)]} attacks.";
			}
		}

		if (isset($this->DMGs))
		{
			$base = $this->level * $this->DMGs_mod * 2;
			if ($this->DMGs && max($this->DMGs) >= $base)
			{
				global $DMG_names;

				$description .= " Delivers a potent {$DMG_names[array_search(max($this->DMGs), $this->DMGs)]} attack.";
			}
		}

		return $description;
	}

	function applyMaterialMods()
	{
		$arrays = ['DMGs', 'DMGs_def', 'DSs', 'DSs_req'];

		$workingMaterials = $this->getWorkingMaterials();

		foreach ($arrays as $statType)
		{
			if (!isset($this->$statType)) continue;
//			console_echo("Applying materials mods to {$this->name}'s {$statType}");

			if (isset($this->$statType) && !empty($this->$statType))
			{
				foreach ($this->$statType as $key => &$value)
				{
					console_echo("{$key} before = {$value}", '#aff');

					$base = $value;

					foreach ($workingMaterials as $material)
					{
						$array = $material->$statType;
						if (isset($array[$key])) $value += ($base * $array[$key]) - $base;
					}

					$value = floor($value);

					console_echo("{$key} after = {$value}", '#afe');
				}
			}

			$this->$statType = array_filter($this->$statType);
		}

		$durabilityMod = $this->durabilityMax;

		foreach ($workingMaterials as $material)
		{
			$durabilityMod *= ($material->durability - 1);
		}

		$this->durabilityMax += $durabilityMod;
	}

	function equip()
	{
		if ($this->isBroken)
		{
			$this->owner->say("{$this->name} is broken.");
			return;
		}
		$this->owner->equip($this);
	}

	function unequip()
	{
		$this->owner->unequip($this);
	}

	function useItem()
	{
		if ($this->isEquipped) $this->unequip();
		else $this->equip();
	}

	function breakItem()
	{
		if ($this->isEquipped) $this->unequip();

		parent::breakItem();
	}


	// Mandatory behaviour functions below this point

	function onAttack	(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	function onMiss		(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	function onStrike	(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	function onKill		(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }

	function onDefend	(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	function onDeflect	(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	function onTakeHit	(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	function onDeath	(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }

	function onEquip		()	{ $this->executeBehaviours(__FUNCTION__); }
	function onUnequip		()	{ $this->executeBehaviours(__FUNCTION__); }

	function onMapChange	()	{ $this->executeBehaviours(__FUNCTION__); }
}

abstract class EquipmentBehaviour extends ItemBehaviour
{
	public $onAttack			= null;
	public $onMiss				= null;
	public $onStrike			= null;
	public $onKill				= null;

	public $onDefend			= null;
	public $onDeflect			= null;
	public $onTakeHit			= null;
	public $onDeath				= null;

	public $onEquip				= null;
	public $onUnequip			= null;

	public $onMapChange			= null;

	public function onAttack	(Attack $attack) { }
	public function onMiss		(Attack $attack) { }
	public function onStrike	(Attack $attack) { }
	public function onKill		(Attack $attack) { }

	public function onDefend	(Attack $attack) { }
	public function onDeflect	(Attack $attack) { }
	public function onTakeHit	(Attack $attack) { }
	public function onDeath		(Attack $attack) { }

	public function onEquip		() { }
	public function onUnequip	() { }

	public function onMapChange	() { }
}
