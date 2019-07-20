<?php

class ebhv_dmg_fire extends EquipmentBehaviour
{
	public $damage;
	public $duration;

	public $DMGDL;

	public $chanceToPanic = 30;

	public function __construct($damage, $duration, $DMGDL = null)
	{
		global $DMGDL_names;

		$this->onStrike = true;

		$this->damage = $damage;
		$this->duration = $duration;

		$this->DMGDL = $DMGDL;
		if (!isset($this->DMGDL)) $this->DMGDL = percentageToBool(80) ? DMGDL_PLASMA : DMGDL_VAPOUR;

		$description = "Deals {$this->damage} fire (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds. Critical hits cause the enemy to panic.";

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
			'On fire',
			"On fire taking {$this->damage} fire damage (via {$DMGDL_names[$this->DMGDL]}) over {$this->duration} seconds and causing panic.",
			$DMG_icons[DMG_FIRE],
			$this->duration,
			false,
			null,
			null,
			[
				DMG_COLD => $this->damage * $this->duration
			]);

		$status->behaviours = [];

		if (percentageToBool($this->chanceToPanic))
		{
			$movement = new obhv_moveRandomly($attack->target->speed_fast);

			$status->behaviours[] = $movement;
		}


		$damage = new dbhv_takeDamagePerSecond(
			$attack->attacker,
			$this->DMGDL,
			[DMG_FIRE => $this->damage / $this->duration],
			$this->duration);

		$status->behaviours[] = $damage;

		$attack->target->addStatus($status);

		update_combat("<<#fff>>{$attack->target->name}<> is <<{$DMG_colours[DMG_FIRE]}>>on fire<>!");
	}
}