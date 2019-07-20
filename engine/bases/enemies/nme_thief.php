<?php

class nme_thief extends Enemy
{
	public function __construct()
	{
		$gender = percentageToBool(85) ? GND_MALE : GND_FEMALE;

		parent::__construct('Thief', self::getSpriteSet($gender), $gender);
	}

	public function getLootArray()
	{
		return [];
	}

	static function getSpriteSet($gender)
	{
		$spriteSet = [];
		$spriteSet[SPRI_DEFAULT] = Dude::getDudeSprite($gender, null, null, '#222', '#222');
		$spriteSet[SPRI_CORPSE] = Dude::getCorpseSprite($spriteSet[SPRI_DEFAULT]);

		return $spriteSet;
	}

	public function __clone()
	{
		parent::__clone();

		$this->gender = percentageToBool(85) ? GND_MALE : GND_FEMALE;
		$this->spriteSet = self::getSpriteSet($this->gender);
		$this->sprite = $this->spriteSet[SPRI_DEFAULT];
	}
}