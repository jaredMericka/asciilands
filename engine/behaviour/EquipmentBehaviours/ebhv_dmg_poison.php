<?php

class ebhv_dmg_poison extends EquipmentBehaviour
{
	public $damage;
	public $duration;

	public $DMGDL;

	public function __construct($damage, $duration, $DMGDL = null)
	{
		global $DMGDL_names;

		$this->onStrike = true;

		$this->damage = $damage;
		$this->duration = $duration;

		$this->DMGDL = $DMGDL;
		if (!isset($this->DMGDL)) $this->DMGDL = percentageToBool(50) ? DMGDL_LIQUID : DMGDL_VAPOUR;

		$description = "Poisons opponent dealing {$this->damage} poison (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds. Movement increases the spread and causes absorbtion to become critical.";

		$this->goldValue = ($damage / $duration) * 0.16;

		parent::__construct($description, null, 1); // Oosenupt
	}

	public function onStrike(Attack $attack)
	{
		if (!$attack->isBaseAttack) return;

		global $DMG_colours;
		global $DMG_icons;
		global $DMGDL_names;

		$damage = new dbhv_takeDamagePerSecond(
			$attack->attacker,
			$this->DMGDL,
			[DMG_POISON => $this->damage / $this->duration],
			$this->duration,
			true);

		$status = new Status(
			'Poisoned',
			"Poisoned taking {$this->damage} poison (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds. Movement makes it worse.",
			$DMG_icons[DMG_POISON],
			$this->duration,
			false);

		$status->behaviours = [$damage];

		$attack->target->addStatus($status);

		update_combat("<<#fff>>{$attack->target->name}<> has been <<{$DMG_colours[DMG_POISON]}>>poisoned<>!");
	}
}