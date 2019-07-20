<?php

class skl_fireySwell extends a_skl_effectPattern
{
	public $requiredLevel = 8;

	public $epCost	= 20;

	public function __construct($level = 1)
	{
		global $DMG_icons;
		global $DMG_effects;

		$this->level = $level;

		$this->pattern = [
			[
				[1,0],
			],
			[
				[1,0],
				[2,0],
			],
			[
				[1,0],
				[2,0],
				[3,0],
			],
			[
				[2,0],
				[3,0],
				[4,0],
			],
			[
				[3,0],
				[4,0],
				[5,0],
			],
			[
				[4,0],
				[5,0],
				[5,1],
				[5,-1],
				[6,0],
			],
			[
				[4,0],
				[5,1],
				[5,-1],
				[6,0],
			],
		];

		$this->effectSprite = $DMG_effects[DMG_FIRE];
		$this->freePlacement = false;
		$this->directional = true;

		$this->TEQT = TEQT_MAGIC;
		$this->DMGDL = DMGDL_PLASMA;

		parent::__construct('Firey Swell', $DMG_icons[DMG_FIRE]);
	}

	public function getDescription()
	{
		return "Sprays fire at your enemies causing {$this->DMGs[DMG_FIRE]} fire (as plasma) damage for each moment spent in the flames.";
	}

	public function onChangeLevel()
	{
		$damage = 6 + ($this->level * 4);

		$this->DMGs = [DMG_FIRE => $damage];
	}

	public function getRelatedSkills()
	{
		return [
			'skl_fireBall' => 2
		];
	}
}