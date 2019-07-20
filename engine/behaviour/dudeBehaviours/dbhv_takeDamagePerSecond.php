<?php

class dbhv_takeDamagePerSecond extends DudeBehaviour
{
	public $attack;
	public $newSprite;

	public $higestDMG;

	public function __construct($attacker, $DMGDL, $DMGs, $critOnMove = null)
	{
		global $DMGDL_names;
		global $DMG_names;

		$this->onIdle = true;
		$this->onRegister = true;
		$this->onRemove = true;
		$this->onMove = isset($critOnMove) && $critOnMove;

		$cooldown = 0.2;

		$this->highestDMG = array_search(max($DMGs), $DMGs);

		$description = "Taking ";

		$multipleDamages = false;
		foreach ($DMGs as $DMG => &$value)
		{
			if ($multipleDamages) $description .= ', ';
			$description .= "{$value} {$DMG_names[$DMG]}";
			$multipleDamages = true;

			$value /= 5;
		}
		$description .= " via {$DMGDL_names[$DMGDL]} per second";

		$this->attack = new Attack($attacker, $DMGDL, $DMGs);
		$this->attack->alwaysHit = true;
		$this->attack->alwaysCrit = false; // Damage over time shouldn't be critting.
		$this->attack->alwaysReady = true;

		parent::__construct($description, null, $cooldown);
	}

	public function onRegister()
	{
		global $DMG_osprs;

//		$this->newSprite = $this->owner->sprite->augment($DMG_osprs[$this->highestDMG]);
//		$this->owner->changeSprite($this->newSprite);

		$this->owner->addSpriteEffect($DMG_osprs[$this->highestDMG]);
	}

	public function onRemove()
	{
		global $DMG_osprs;
//		console_echo('Reverting painted sprite', '#faa');
//		$this->owner->revertSprite($this->newSprite);

		$this->owner->removeSpriteEffect($DMG_osprs[$this->highestDMG]);
	}

	public function onIdle()
	{
		$this->attack->execute($this->owner);
	}

	public function onMove($new_n_offset, $new_w_offset)
	{
		$this->attack->alwaysCrit = true;
		$this->attack->execute($this->owner);
		$this->attack->alwaysCrit = false;
	}
}