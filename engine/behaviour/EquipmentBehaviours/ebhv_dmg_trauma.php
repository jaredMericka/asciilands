<?php

class ebhv_dmg_trauma extends EquipmentBehaviour
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
		if (!isset($this->DMGDL)) $this->DMGDL = percentageToBool(40) ? DMGDL_CUT : DMGDL_POINT;

		$description = "Causes open wounds dealing {$this->damage} trauma (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds.";

		$this->goldValue = ($damage / $duration) * 0.16;

		parent::__construct($description, null, 1); // Oosenupt
	}

	public function onStrike(Attack $attack)
	{
		if (!$attack->isBaseAttack) return;

		global $DMG_colours;
		global $DMG_icons;
		global $DMGDL_names;

		$status = new Status(
			'Open wounds',
			"Wounded taking {$this->damage} trauma damage (via {$DMGDL_names[$this->DMGDL]}) over {$this->duration} seconds.",
			$DMG_icons[DMG_TRAUMA],
			$this->duration,
			false,
			null,
			null,
			[
				DMGDL_LIQUID => 0 - ($this->damage),
				DMG_INFECTION => 0 - ($this->damage),
				DMG_POISON => 0 - ($this->damage),
			]);

		$status->behaviours = [];

		$damage = new dbhv_takeDamagePerSecond(
			$attack->attacker,
			$this->DMGDL,
			[DMG_TRAUMA => $this->damage / $this->duration],
			$this->duration);

		$status->behaviours[] = $damage;

		$attack->target->addStatus($status);

		update_combat("<<#fff>>{$attack->target->name}<> is <<{$DMG_colours[DMG_TRAUMA]}>>bleeding<>!");
	}
}