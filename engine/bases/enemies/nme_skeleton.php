<?php

class nme_skeleton extends Enemy
{
	public $FAC = FAC_MONSTER;

	public $DSs = [
		DS_HANDICAP => 0.7,
		DS_HP_MAX => 80,
		DS_REGENERATION => 0,
//		DS_EPMAX => 100,
//		DS_RECHARGE => 1,
		DS_LUCK => 120,
//		DS_ATTACKSPEED => 1,
//		DS_SPEED => 1,
//		DS_SPEED_FAST => 0.4,
//		DS_DAMAGE => 100,
//		DS_HITCHANCE => 100,
//		DS_CRITDAMAGE => 100,
//		DS_CRITCHANCE => 100,
//		DS_DEFENCE => 100,
//		DS_ENERGYUSE => 100,
//		DS_STRENGTH => 100,
//		DS_FORCE => 100,
//		DS_RESILIENCE => 100,
//		DS_CONTROL => 100,
//		DS_INERTIA => 100,
//		DS_RECOVERY => 100,
//		DS_AGILITY => 100,
//		DS_DEXTERITY => 100,
//		DS_EVASIVENESS => 100,
//		DS_FINESSE => 100,
//		DS_BALANCE => 100,
//		DS_REACH => 100,
//		DS_MAGIC => 100,
//		DS_DISRUPTION => 100,
//		DS_DISCIPLINE => 100,
//		DS_FOCUS => 100,
//		DS_INSANITY => 100,
//		DS_CHARISMA => 100,
//		DS_REPUTATION => 100,
//		DS_NOTORIETY => 100,
//		DS_FAME => 100,
//		DS_DISCOUNT => 100,
//		DS_BARGAINING => 100,
//		DS_LEADERSHIP => 100,
//		DS_INTELLECT => 100,
//		DS_TENACITY => 100,
//		DS_KNOWLEDGE => 100,
//		DS_HEURISTICS => 100,
//		DS_JUDGEMENT => 100,
	];

	public $DMGs_def = [

	];

	public $lvl_DSs = [
		DS_HP_MAX => 3
	];

	public $lvl_DMGs = [
		DMG_TRAUMA => 2
	];

	public function __construct()
	{

		$spriteSet = self::getSpriteSet();
		$name = 'skeleton';

		parent::__construct($name, $spriteSet);


		$this->DMGs = [
			DMG_TRAUMA => 20
		];

//		global $player;

	}

	function getLootArray()
	{
		return [

		];
	}

	static function getSpriteSet($colour = null)
	{
		if (!isset($colour)) $colour = '#fda';

		$spr_skeleton = new Sprite([
			1 => new SpriteElement(null, $colour, '&#x2640;'),
			3 => new SpriteElement(null, $colour, '&deg;'),
			4 => new SpriteElement(null, $colour, '&Lambda;'),
			5 => new SpriteElement(null, $colour, '&deg;'),
			]);

		$spr_skeleton_corpse = new Sprite([
			3 => new SpriteElement(null, $colour, '&#x2640;'),
			4 => new SpriteElement(null, $colour, '&omega;'),
			5 => new SpriteElement(null, $colour, '<'),
			]);

		return [
			SPRI_DEFAULT	=> $spr_skeleton,
			SPRI_CORPSE		=> $spr_skeleton_corpse
		];
	}
}