<?php

class ebhv_dmg_cold extends EquipmentBehaviour
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
		if (!isset($this->DMGDL)) $this->DMGDL = percentageToBool(30) ? DMGDL_LIQUID : DMGDL_VAPOUR;

		$description = "Deals {$this->damage} cold (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds. Critical hits freeze the enemy in place.";

		$this->goldValue = ($damage / $duration) * 0.16;

		parent::__construct($description, null, 1); // Oosenupt
	}

	public function onStrike(Attack $attack)
	{
		if (!$attack->isBaseAttack) return;

		global $DMGDL_names;
		global $DMG_colours;
		global $DMG_icons;

		$status = new Status(
			'Frozen',
			"Frozen and taking {$this->damage} cold damage (via {$DMGDL_names[$this->DMGDL]}) over {$this->duration} seconds.",
			$DMG_icons[DMG_COLD],
			$this->duration,
			false,
			[
				DS_SPEED => '200%',
				DS_SPEED_FAST => '200%',
			],
			null,
			[
				DMG_FIRE => $this->damage * $this->duration,
			]
		);

		$damage = new dbhv_takeDamagePerSecond(
			$attack->attacker,
			$this->DMGDL,
			[DMG_COLD => $this->damage / $this->duration]
		);

		$status->behaviours[] = $damage;

//		if (percentageToBool($this->chanceToFreeze))
		if ($attack->isCrit)
		{
			update_sound(SND_FROZEN);

			$movement = new obhv_pauseMovement();
			update_combat("<<#fff>>{$attack->target->name}<> is <<{$DMG_colours[DMG_COLD]}>>frozen<>!");

			$status->behaviours[] = $movement;
			$status->spriteEffects[] = $DMG_colours[DMG_COLD];
		}


		$attack->target->addStatus($status);
	}
}