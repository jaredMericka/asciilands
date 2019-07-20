<?php

class ebhv_dmg_electric extends EquipmentBehaviour
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
		if (!isset($this->DMGDL)) $this->DMGDL = DMGDL_PLASMA;

		$description = "Deals {$this->damage} electric (via {$DMGDL_names[$this->DMGDL]}) damage over {$this->duration} seconds and cuts all agility related stats between 10% and 80%.";

		$this->goldValue = ($damage / $duration) * 0.16;

		parent::__construct($description, null, 1); // Oosenupt
	}

	public function onStrike(Attack $attack)
	{
		if (!$attack->isBaseAttack) return;

		global $DMG_icons;
		global $DS_types;
		global $DMGDL_names;

		$agilityPenalty = '-' . mt_rand(10, 80) . '%';

		$DSs = [DS_AGILITY => $agilityPenalty];
		foreach ($DS_types[DS_AGILITY] as $DS)
		{
			$DSs[$DS] = $agilityPenalty;
		}

		$status = new Status(
			'Electrocution',
			"Electrocuted taking {$this->damage} electric damage (via {$DMGDL_names[$this->DMGDL]}) over {$this->duration} seconds and agility reduced by {$agilityPenalty}.",
			$DMG_icons[DMG_ELECTRIC],
			$this->duration,
			false,
			$DSs
		);

		$damage = new dbhv_takeDamagePerSecond(
			$attack->attacker,
			$this->DMGDL,
			[DMG_ELECTRIC => $this->damage / $this->duration]
		);

		$status->behaviours[] = $damage;

		$attack->target->addStatus($status);
	}
}