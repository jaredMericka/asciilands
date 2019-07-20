<?php

class dbhv_takeDamageOverTime extends DudeBehaviour
{
	public $repeats;
	public $attack;
	public $duration;

	public $status;

	public $newSprite;

	public function __construct($attacker, $DMGDL, $DMGs, $duration)
	{
		global $DMGDL_names;
		global $DMG_names;
		global $DMG_effects;

		$this->onIdle = true;
		$this->onRegister = true;

		$cooldown = 0.2;
		$this->duration = $duration;
		$this->repeats = floor($duration / $cooldown);

		$this->highestDMG = array_search(max($DMGs), $DMGs);

		$description = "Taking ";

		$multipleDamages = false;
		foreach ($DMGs as $DMG => $value)
		{
			if ($multipleDamages) $description .= ', ';
			$description .= "{$value} {$DMG_names[$DMG]}";
			$multipleDamages = true;
		}
		$description .= " via {$DMGDL_names[$DMGDL]} over {$this->duration} seconds";

		foreach ($DMGs as &$value)
		{
			$value = $value / $this->repeats;
		}

		$this->attack = new Attack($attacker, $DMGDL, $DMGs);
		$this->attack->alwaysHit = true;
		$this->attack->alwaysCrit = false; // Damage over time shouldn't be critting.


		$this->status = new Status(
			"Sustained {$DMG_names[$this->highestDMG]} damage",
			$description,
			$DMG_effects[$this->highestDMG],
			$this->duration,
			false);

		parent::__construct($description, null, $cooldown);
	}

	public function onRegister()
	{
		global $DMG_osprs;

//		$this->newSprite = $this->owner->sprite->augment($DMG_osprs[$this->highestDMG]);
//		$this->owner->changeSprite($this->newSprite);
		$this->owner->addSpriteEffect($DMG_osprs[$this->highestDMG]);

		$this->owner->addStatus($this->status);
	}

	public function onIdle()
	{
		global $DMG_osprs;

		if ($this->repeats-- <= 0)
		{
//			$this->owner->revertSprite($this->newSprite);
			$this->owner->removeSpriteEffect($DMG_osprs[$this->highestDMG]);
			$this->delete();
			return;
		}
		else
		{
			$this->attack->execute($this->owner);
			console_echo("{$this->repeats} hits left.");
		}
	}
}