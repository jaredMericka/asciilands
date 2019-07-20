<?php

class ebhv_chanceToDamageNearbyDudes extends EquipmentBehaviour
{
	public $radius;
	public $chance;
	public $duration;

	public $DMGDL;
	public $DMGs;

	function __construct($DMGDL, $DMGs, $radius, $duration, $cooldown, $chance, $TRG)
	{
		global $DMGDL_names;
		global $DMG_names;
		global $TRG_readable;

		$this->$TRG = true;

		$this->DMGDL	= $DMGDL;
		$this->DMGs		= $DMGs;
		$this->radius	= $radius;
		$this->chance	= $chance;
		$this->duration	= $duration;

		$this->goldValue = (array_sum($DMGs) / ($duration / 2)) * $radius * (0.1 * $chance) * (3/$cooldown) * 0.2;

		$description = "{$chance}% chance to deal ";

		$multipleDamages = false;
		foreach ($DMGs as $DMG => $value)
		{
			if ($multipleDamages) $description .= ', ';
			$description .= "{$value} {$DMG_names[$DMG]}";
			$multipleDamages = true;
		}
		$description .= " via {$DMGDL_names[$DMGDL]} over {$duration} seconds in a {$radius} pace radius {$TRG_readable[$TRG]}";

		parent::__construct($description, null, $cooldown);
	}

	function onAttack	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onMiss		(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onStrike	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onKill		(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }

	function onDefend	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onDeflect	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onTakeHit	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onDeath	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }

	function apply()
	{
		global $map;
		global $DMG_effects;
		global $DMG_names;

		$objectsInRange = $map->getObjectsInArea(
			$this->owner->owner->n_offset - $this->radius,
			$this->owner->owner->w_offset - $this->radius,
			$this->owner->owner->n_offset + $this->radius,
			$this->owner->owner->w_offset + $this->radius
			);

		console_echo('Objects in range: ' . count($objectsInRange), '#f0f');

		foreach ($objectsInRange as $object)
		{
			console_echo($object->name);

			if ($object instanceof Dude)
			{
				$object->addBehaviour(new dbhv_takeDamageOverTime($this->owner->owner, $this->DMGDL, $this->DMGs, $this->duration));
			}
		}

		$highestDMG = array_search(max($this->DMGs), $this->DMGs);

		update_combat("<<#fff>>{$this->owner->owner->name}<> hits everyone within <<#aaf>>{$this->radius} paces<> for <<#faa>>" . array_sum($this->DMGs) . " {$DMG_names[$highestDMG]}<> damage over <<#aaf>>{$this->duration} seconds<>.");

		$effectSprite = $DMG_effects[$highestDMG];
		$effectArray = [];
		for ($n = $this->owner->owner->n_offset - $this->radius; $n <= $this->owner->owner->n_offset + $this->radius; $n++)
		{
			for ($w = $this->owner->owner->w_offset - $this->radius; $w <= $this->owner->owner->w_offset + $this->radius; $w++)
			{
				$effectArray[] = new Effect($effectSprite, $n, $w, 5);
			}
		}
		$map->addEffects($effectArray);

	}
}