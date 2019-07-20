<?php

// This class is inextricably linked to dbhv_takeDamageOverTime. Make sure major
// changes are tested in conjuction with that class, too.

class ebhv_dealDamageOverTime extends EquipmentBehaviour
{
	public $DMGDL;
	public $DMGs;
	public $duration;

	public $overSprite;

	public function __construct($DMGDL, $DMGs, $duration, $cooldown)
	{
		global $DMG_names;
		global $DMGDL_names;

		$this->goldValue = (array_sum($DMGs) / $duration) * 0.1;

		$this->onStrike = true;

		$description = "Deals ";

		$multipleDamages = false;
		foreach ($DMGs as $DMG => $value)
		{
			if ($multipleDamages) $description .= ', ';
			$description .= "{$value} {$DMG_names[$DMG]}";
			$multipleDamages = true;
		}
		$description .= " via {$DMGDL_names[$DMGDL]} over {$duration} seconds";

		$this->DMGs = $DMGs;
		$this->DMGDL = $DMGDL;
		$this->duration = $duration;

		$this->keySuffix = '234';

		parent::__construct($description, 'DMG_OVER_TIME', $cooldown);
	}

	public function onStrike(Attack $attack)
	{
		console_echo('Applying damage over time', '#faf');		//XXX

		if ($attack->target instanceof Dude && $attack->isBaseAttack)
		{
			$dbhv_takeDamageOverTime = new dbhv_takeDamageOverTime($attack->attacker, $this->DMGDL, $this->DMGs, $this->duration);
			$attack->target->addBehaviour($dbhv_takeDamageOverTime);
		}
	}
}
