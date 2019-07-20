<?php

// This class is inextricably linked to dbhv_takeDamageOverTime. Make sure major
// changes are tested in conjuction with that class, too.

class ebhv_dealDamagePerSecond extends EquipmentBehaviour
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
		global $DMG_icons;

		console_echo('Applying damage over time', '#faf');		//XXX

		if ($attack->target instanceof Dude && $attack->isBaseAttack)
		{
//			$dbhv_takeDamageOverTime = new dbhv_takeDamageOverTime($attack->attacker, $this->DMGDL, $this->DMGs, $this->duration);
			$dbhv_takeDamagePerSecond = new dbhv_takeDamagePerSecond($attack->attacker, $this->DMGDL, $this->DMGs);

			$status = new Status(
				'Take damage over time',
				'Takes damage over time',
				$DMG_icons[array_search(max($this->DMGs), $this->DMGs)],
				$this->duration
			);

			$status->behaviours = [$dbhv_takeDamagePerSecond];

//			$attack->target->addBehaviour($dbhv_takeDamageOverTime);
			$attack->target->addStatus($status);
		}
	}
}
