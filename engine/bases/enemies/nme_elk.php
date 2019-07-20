<?php

class nme_elk extends Enemy
{
	public $DMGs = [
		DMG_TRAUMA => 20
	];

	public $DSs = [
		DS_HP_MAX		=> 360,
		DS_EXPERIENCE	=> 220,

		DS_STRENGTH => 400,
		DS_FORCE => 300,
		DS_RESILIENCE => 190,
		DS_CONTROL => 160,
		DS_INERTIA => 140,
		DS_RECOVERY => 140,
		DS_AGILITY => 130,
		DS_DEXTERITY => 240,
		DS_EVASIVENESS => 170,
		DS_FINESSE => 190,
		DS_BALANCE => 350,
		DS_REACH => 200,
		DS_MAGIC => 70,
		DS_DISRUPTION => 20,
		DS_DISCIPLINE => 130,
		DS_FOCUS => 260,
		DS_INSANITY => 120,
		DS_CHARISMA => 260,
		DS_REPUTATION => 130,
		DS_NOTORIETY => 80,
		DS_FAME => 60,
//		DS_DISCOUNT => 100,
//		DS_BARGAINING => 100,
		DS_LEADERSHIP => 120,
		DS_INTELLECT => 70,
		DS_TENACITY => 110,
		DS_KNOWLEDGE => 50,
		DS_HEURISTICS => 240,
		DS_JUDGEMENT => 280,
		DS_PRAXIS => 30,
	];

	public function __construct()
	{
		$gender = mt_rand(0, 1) ? GND_MALE : GND_FEMALE;

		$name = 'Elk';
		$spriteSet = $this->getSpriteSet();


		$this->setUpGender($this->gender);

		parent::__construct($name, $spriteSet, $gender);
	}

	function getLootArray()
	{
		require "{$GLOBALS['rootPath']}content/materials/uncommon_fabrics.mat";
		require "{$GLOBALS['rootPath']}content/materials/common_bone.mat";

		return [
			80 => [
				new itm_material($mat_elkAntler),
				new itm_material($mat_elkPelt),
			]
		];
	}

	public function getSpriteSet ($antlers = false)
	{
		$col_fur = '#a84';
		$col_antlers = '#fda';
		$col_eye = '#000';

		if ($antlers)
		{
			return [
				SPRI_DEFAULT => new Sprite([
					[
						0 => new SpriteElement(null,$col_antlers, '&#x251c;'),
						2 => new SpriteElement(null,$col_antlers, '&#x2524;'),
						3 => new SpriteElement(null,$col_antlers, '&#x2534;'),
						4 => new SpriteElement(null,$col_antlers, '&#x2565;'),
						5 => new SpriteElement(null,$col_antlers, '&#x2534;'),
					],
				])
			];
		}
		else
		{
			return [
				SPRI_WEST => new Sprite([
					[
						0 => new SpriteElement(null,$col_fur, '&#x2580;'),
						1 => new SpriteElement($col_fur, $col_eye, '&deg;'),
						2 => new SpriteElement(null,$col_fur, '&#x2584;'),
						4 => new SpriteElement(null,$col_fur, '&#x258c;'),
						5 => new SpriteElement(null,$col_fur, '&#x2590;'),
					],
				]),

				SPRI_EAST => new Sprite([
					[
						0 => new SpriteElement(null,'#a84', '&#x2584;'),
						1 => new SpriteElement('#a84', '#000', '&deg;'),
						2 => new SpriteElement(null,'#a84', '&#x2580;'),
						3 => new SpriteElement(null,'#a84', '&#x258c;'),
						4 => new SpriteElement(null,'#a84', '&#x2590;'),
					],
				])
			];
		}
	}

	function setUpGender($GND)
	{
		$dbhv_dropConstituents = new dbhv_dropConstituentsOnDeath([-1 => [ 0 => null]]);
		if ($GND === GND_MALE)
		{
			$this->constituents[-1][0] = new ObjectConstituent($this->getSpriteSet(true));
			$this->addBehaviour($dbhv_dropConstituents);
		}
		else
		{
			$this->constituents = null;
			$this->removeBehaviour($dbhv_dropConstituents);
		}
	}

	function __clone()
	{
		$this->gender = mt_rand(0, 1) ? GND_MALE : GND_FEMALE;

		$this->setUpGender($this->gender);
		parent::__clone();
	}
}

