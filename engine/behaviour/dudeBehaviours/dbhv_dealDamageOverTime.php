<?php

class dbhv_dealDamageOverTime extends DudeBehaviour
{
	public $DMGDL;
	public $DMGs;
	public $duration;

	public $overSprite;

	public function __construct($DMGDL, $DMGs, $duration, $cooldown)
	{
		global $DMG_names;
		global $DMGDL_names;

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
			global $DMG_icons;

			$maxDMG = array_search(max($this->DMGs), $this->DMGs);

			$status = new Status('DoT', 'Taking damage over time', $DMG_icons[$maxDMG], $this->duration);
			$status->behaviours = [new dbhv_takeDamagePerSecond($attack->attacker, $this->DMGDL, $this->DMGs)];

			$attack->target->addStatus($status);
		}
	}
}