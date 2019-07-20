<?php

class skl_frozenBall extends a_skl_projectile
{
	public $epCost = 15;

	public $DMGDL	= DMGDL_VAPOUR;
	public $DMGs	= [DMG_COLD => 10];

	public function __construct ($level = 1)
	{
		$this->level = $level;
		global $DMG_effects;

		$this->status = new Status('Frozen', 'Slows movement', $DMG_effects[DMG_COLD], 5, false, [DS_SPEED => '200%', DS_SPEED_FAST => '200%']);
		$this->status->behaviours = [new obhv_flee($GLOBALS['player'])];
//		$this->status->behaviours = [new obhv_wander()];

		$speArray = [
			new SpriteElement(null, '#fff', 'x'),
			new SpriteElement(null, '#fff', '+'),
			new SpriteElement(null, '#fff', '*'),
		];

		$this->spriteSet = obj_missile::getSpriteSet($speArray);

		parent::__construct('Frozen ball', $this->spriteSet[SPRI_EAST]);
	}

	public function getDescription()
	{
		return 'Cast a ball of frozen mist to freeze your enemies.';
	}

	public function onChangeLevel()
	{
		$fireDamage = 10 + (5 * $this->level);
		$this->DMGs = [DMG_COLD => $fireDamage];

		$this->range = 10 + round($this->level / 4);
	}

	public function getRelatedSkills()
	{
		return [];
	}
}
