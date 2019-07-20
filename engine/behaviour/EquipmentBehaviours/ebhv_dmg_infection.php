<?php

class ebhv_dmg_infection extends EquipmentBehaviour
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
		if (!isset($this->DMGDL)) $this->DMGDL = percentageToBool(20) ? DMGDL_LIQUID : DMGDL_VAPOUR;

		$description = "Infects opponent causing {$this->damage} infection (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds. Infected opponents may infect adjacent allies.";

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
			[DMG_INFECTION => $this->damage / $this->duration],
			$this->duration);

		$status = new Status(
			'Infected',
			"Infected and taking {$this->damage} infection (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds.",
			$DMG_icons[DMG_INFECTION],
			$this->duration,
			false,
			null,	// ddue stats
			null,	// damage
			null	// defence
		);

		$status->behaviours = [$damage];

		$attack->target->addStatus($status);

		update_combat("<<#fff>>{$attack->target->name}<> is <<{$DMG_colours[DMG_INFECTION]}>>infected<>!");
	}
}