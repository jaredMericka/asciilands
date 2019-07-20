<?php

class skl_fireBall extends a_skl_projectile
{
	public $requiredLevel = 1;

	public $epCost = 10;

	public $DMGDL	= DMGDL_PLASMA;
	public $DMGs	= [DMG_FIRE => 5];

	public $DMGDL_OT	= DMGDL_PLASMA;
	public $DMGs_OT		= [DMG_FIRE => 10];
	public $duration_OT	= 5;

	public function __construct ($level = 1)
	{
		$this->level = $level;

		$speArray = [
			new SpriteElement(null, '#f90', '&#x25b2;'),
			new SpriteElement(null, '#f90', '&#x2666;'),
			new SpriteElement(null, '#f90', '&#x25bc;'),
			new SpriteElement(null, '#f90', '&#x2666;')
		];

		$this->spriteSet = obj_missile::getSpriteSet($speArray);

		parent::__construct('Fire ball', $this->spriteSet[SPRI_EAST]);
	}

	public function onChangeLevel()
	{
		$fireDamage = 10 + (5 * $this->level);
		$this->DMGs = [DMG_FIRE => $fireDamage];

		$this->range = 10 + round($this->level / 4);

		$this->epCost = 10 + $this->level;
	}

	public function getDescription()
	{
		return "Launch a searing ball of fire to burn your enemies.";
	}

	public function getRelatedSkills()
	{
		return [
			'skl_fireySwell' => 5,
			'psv_frustration' => 2
		];
	}
}
