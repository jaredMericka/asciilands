<?php

//class dude_zombie extends Dude
class nme_zombie extends Enemy
{
	public $DSs =
	[
		DS_HANDICAP => 0.3,
		DS_HP_MAX => 140,
		DS_EP_MAX => 80,
		DS_LUCK => 0,
		DS_SPEED => 1,
		DS_SPEED_FAST => 0.4,
		DS_ATTACKSPEED => 0.4,
		DS_STRENGTH => 90,
		DS_FORCE => 70,
		DS_RESILIENCE => 130,
		DS_CONTROL => 20,
		DS_INERTIA => 110,
		DS_RECOVERY => 70,
		DS_AGILITY => 40,
		DS_DEXTERITY => 20,
		DS_EVASIVENESS => 10,
		DS_FINESSE => 5,
		DS_BALANCE => 60,
		DS_MAGIC => 5,
		DS_DISRUPTION => 4,
		DS_DISCIPLINE => 2,
		DS_FOCUS => 3,
		DS_INSANITY => 200,
		DS_CHARISMA => 0,
		DS_REPUTATION => 0,
		DS_NOTORIETY => 20,
		DS_FAME => 0,
		DS_DISCOUNT => 0,
		DS_BARGAINING => 0,
		DS_LEADERSHIP => 0,
		DS_INTELLECT => 10,
		DS_TENACITY => 40,
		DS_KNOWLEDGE => 0,
		DS_HEURISTICS => 20,
		DS_JUDGEMENT => 10,
	];

	public function __construct($name = null, $spriteSet = null, $gender = null, $DSs = [])
	{
		if (!isset($gender)) $gender = percentageToBool(52) ? GND_MALE : GND_FEMALE;

		$spriteSet = $this->getSpriteSet();

		if ($gender === GND_FEMALE)
		{
			$spriteSet[SPRI_DEFAULT] = $spriteSet[SPRI_FEMALE];
			if (isset($spriteSet[SPRI_FEMALE_CORPSE])) $spriteSet[SPRI_CORPSE] = $spriteSet[SPRI_FEMALE_CORPSE];
		}
		else
		{
			$spriteSet[SPRI_DEFAULT] = $spriteSet[SPRI_MALE];
			if (isset($spriteSet[SPRI_MALE_CORPSE])) $spriteSet[SPRI_CORPSE] = $spriteSet[SPRI_MALE_CORPSE];
		}

		$this->FAC			= FAC_MONSTER;

		$this->DMGs			= [DMG_TRAUMA => 6, DMG_POISON	=> 15];
		$this->DMGDL		= DMGDL_POINT;

		$this->addBehaviour(
			new dbhv_zombify($spriteSet)
		);

		$this->DMGs_def	= [DMG_POISON => 100, DMG_FIRE => -15, DMGDL_BLUNT => 30];

//		parent::__construct($name, $spriteSet, $gender, null, $DSs);
		parent::__construct($name, $spriteSet);
	}

	function getLootArray()
	{
		return [

		];
	}

	/*
	 * The whole $newSpriteSet thing in this function is a fucking mess but
	 * there's all kinds of weird stuff going on with the sprite key holding
	 * references and stuff like that. This is a good guide for future objects
	 * that might have comples sprite clonging problems.
	 */
	function __clone()
	{
		parent::__clone();

		$this->gender = percentageToBool(52) ? GND_MALE : GND_FEMALE;

		$newSpriteSet = [];

		if ($this->gender === GND_FEMALE)
		{
			$newSpriteSet[SPRI_DEFAULT] = $this->spriteSet[SPRI_FEMALE];
			if (isset($this->spriteSet[SPRI_FEMALE_CORPSE])) $newSpriteSet[SPRI_CORPSE] = $this->spriteSet[SPRI_FEMALE_CORPSE];
		}
		else
		{
			$newSpriteSet[SPRI_DEFAULT] = $this->spriteSet[SPRI_MALE];
			if (isset($this->spriteSet[SPRI_MALE_CORPSE])) $newSpriteSet[SPRI_CORPSE] = $this->spriteSet[SPRI_MALE_CORPSE];
		}

		$this->spriteSet = $newSpriteSet + $this->spriteSet;

//		$this->defaultSprite = $this->spriteSet[SPRI_DEFAULT];
		$this->sprite = $this->spriteSet[SPRI_DEFAULT];
	}

	function getSpriteSet($colour = null)
	{
		if (!isset($colour) )$colour = '#0f0';

		$spr_zombie_m = new Sprite([
			[ // Both hands up
				new SpriteElement(null, $colour, '&deg;'),
				new SpriteElement(null, $colour, 'o'),
				new SpriteElement(null, $colour, '&deg;'),
				4 => new SpriteElement(null, '#400', '&lambda;'),
			],
			[ // Left hand down, right hand up
				1 => new SpriteElement(null, $colour, 'o'),
				new SpriteElement(null, $colour, '&deg;'),
				new SpriteElement(null, $colour, '&deg;'),
				4 => new SpriteElement(null, '#400', '&lambda;'),
			],
			[ // Both hands down
				1 => new SpriteElement(null, $colour, 'o'),
				3 => new SpriteElement(null, $colour, '&deg;'),
				new SpriteElement(null, '#400', '&lambda;'),
				new SpriteElement(null, $colour, '&deg;'),
			],
			[ // Left hand up, right hand down
				new SpriteElement(null, $colour, '&deg;'),
				new SpriteElement(null, $colour, 'o'),
				4 => new SpriteElement(null, '#400', '&lambda;'),
				new SpriteElement(null, $colour, '&deg;'),
			],
		]);

		$spr_zombie_f = new Sprite([
			[ // Both hands up
				new SpriteElement(null, $colour, '&deg;'),
				new SpriteElement(null, $colour, 'o'),
				new SpriteElement(null, $colour, '&deg;'),
				4 => new SpriteElement(null, '#400', '&Delta;'),
			],
			[ // Left hand up, right hand down
				new SpriteElement(null, $colour, '&deg;'),
				new SpriteElement(null, $colour, 'o'),
				4 => new SpriteElement(null, '#400', '&Delta;'),
				new SpriteElement(null, $colour, '&deg;'),
			],
			[ // Both hands down
				1 => new SpriteElement(null, $colour, 'o'),
				3 => new SpriteElement(null, $colour, '&deg;'),
				new SpriteElement(null, '#400', '&Delta;'),
				new SpriteElement(null, $colour, '&deg;'),
			],
			[ // Left hand down, right hand up
				1 => new SpriteElement(null, $colour, 'o'),
				new SpriteElement(null, $colour, '&deg;'),
				new SpriteElement(null, $colour, '&deg;'),
				4 => new SpriteElement(null, '#400', '&Delta;'),
			],
		]);

		$spr_zombieCorpse_m = self::getCorpseSprite($spr_zombie_m, '#f50');
		$spr_zombieCorpse_f = self::getCorpseSprite($spr_zombie_f, '#f50');


		return [
			SPRI_MALE			=> $spr_zombie_m,
			SPRI_MALE_CORPSE	=> $spr_zombieCorpse_m,
			SPRI_FEMALE			=> $spr_zombie_f,
			SPRI_FEMALE_CORPSE	=> $spr_zombieCorpse_f
		];
	}
}


