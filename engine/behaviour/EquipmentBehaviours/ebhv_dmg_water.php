<?php

class ebhv_dmg_water extends EquipmentBehaviour
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
		if (!isset($this->DMGDL)) $this->DMGDL = percentageToBool(90) ? DMGDL_LIQUID : DMGDL_VAPOUR;

		$description = "Soaks opponent causing {$this->damage} water (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds. Soaked opponents are more vulnerable to cold and electric damage.";

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
			[DMG_WATER => $this->damage / $this->duration],
			$this->duration);

		$status = new Status(
			'Soaked',
			"Soaked and taking {$this->damage} water (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds.",
			$DMG_icons[DMG_WATER],
			$this->duration,
			false,
			null,
			null,
			[
				DMG_COLD => 0 - ($this->damage * 5),
				DMG_ELECTRIC => 0 - ($this->damage * 5),
			]
		);

		$status->behaviours = [$damage];

		$attack->target->addStatus($status);

		update_combat("<<#fff>>{$attack->target->name}<> is <<{$DMG_colours[DMG_WATER]}>>soaked<>!");
	}
}