<?php

class nme_golem extends Enemy
{
	public $FAC = FAC_MONSTER;

	public $big;

	public $material;

	protected $DSs = [
		DS_HANDICAP		=> 1.2,
		DS_HP_MAX		=> 240,
		DS_EP_MAX		=> 120,
		DS_LUCK			=> 80,
		DS_SPEED		=> 1.6,
		DS_SPEED_FAST	=> 1.6,
		DS_STRENGTH		=> 370,
		DS_FORCE		=> 410,
		DS_RESILIENCE	=> 320,
		DS_CONTROL		=> 40,
		DS_INERTIA		=> 220,
		DS_RECOVERY		=> 10,
		DS_AGILITY		=> 2,
		DS_DEXTERITY	=> 40,
		DS_EVASIVENESS	=> 1,
		DS_FINESSE		=> 1,
		DS_BALANCE		=> 200,
		DS_REACH		=> 270,
		DS_MAGIC		=> 90,
		DS_DISRUPTION	=> 30,
		DS_DISCIPLINE	=> 100,
		DS_FOCUS		=> 170,
		DS_INSANITY		=> 8,
		DS_CHARISMA		=> 70,
		DS_REPUTATION	=> 1,
		DS_NOTORIETY	=> 1,
		DS_FAME			=> 1,
		DS_DISCOUNT		=> 1,
		DS_BARGAINING	=> 1,
		DS_LEADERSHIP	=> 1,
		DS_INTELLECT	=> 40,
		DS_TENACITY		=> 5,
		DS_KNOWLEDGE	=> 60,
		DS_HEURISTICS	=> 140,
		DS_JUDGEMENT	=> 190,
	];

	protected $DMGs = [
		DMG_TRAUMA => 15,
	];

	public $technique = [
		TEQT_MELEE => [
			TEQ_DAMAGE		=> [DS_STRENGTH => 0.6, DS_FORCE => 0.5],
			TEQ_HIT_CHANCE	=> [DS_CONTROL => 0.2, DS_INERTIA => 0.5, DS_REACH => 0.7],
			TEQ_CRIT_DAMAGE	=> [DS_FORCE => 1],
			TEQ_CRIT_CHANCE	=> [DS_DEXTERITY => 1],
			TEQ_DEFENCE		=> [DS_RESILIENCE => 1],
			TEQ_DODGE_CHANCE	=> [DS_EVASIVENESS => 1],
		],
		TEQT_MAGIC => [
			TEQ_DAMAGE		=> [DS_MAGIC => 0.8, DS_DISRUPTION => 0.5],
			TEQ_HIT_CHANCE	=> [DS_DISCIPLINE => 1],
			TEQ_CRIT_DAMAGE	=> [DS_FOCUS => 0.5],
			TEQ_CRIT_CHANCE	=> [DS_DISCIPLINE => 0.5, DS_DISRUPTION => 0.7],
			TEQ_DEFENCE		=> [DS_FOCUS => 1],
			TEQ_DODGE_CHANCE	=> [DS_FOCUS => 1],
		]
	];

	public function __construct($material, $big = false)
	{
		$this->material = $material;

		$this->big = $big;

		$name = ($big ? 'Big ' : '') . "{$this->material->name} golem";

		$spriteSet = $this->getSpriteSet();
		if ($big) $this->bigSetup();

		parent::__construct($name, $spriteSet, GND_MALE);


		$this->addBehaviour(new dbhv_crippleOnHit(50, 80, 10));

		$this->applyMaterialProperties();
	}

	function getLootArray()
	{
		return [

		];
	}

	public function getSpriteSet()
	{
		$golemColour = $this->material->colour;

		if ($this->big)
		{
			$bl = new Sprite([
				0 => new SpriteElement($golemColour, null, ' '),
				2 => new SpriteElement($golemColour, null, ' '),
				3 => new SpriteElement($golemColour, null, ' '),
				4 => new SpriteElement(null, $golemColour, '&#x258c;'),
				5 => new SpriteElement(null, $golemColour, '&#x258c;'),
				]);

			$bl_corpse = new Sprite([
				2 => new SpriteElement($golemColour, '#000', '-'),
				3 => new SpriteElement(null, $golemColour, '&#x2584;'),
				4 => new SpriteElement($golemColour, null, ' '),
				5 => new SpriteElement($golemColour, null, ' '),
				]);

			return [
				SPRI_DEFAULT => $bl,
				SPRI_CORPSE => $bl_corpse,
			];
		}
		else
		{
			$spr_golem = new Sprite([
				0 => new SpriteElement(null, $golemColour, '&#x2584;'),
				1 => new SpriteElement($golemColour, '#000', '&#x201c;'),
				2 => new SpriteElement(null, $golemColour, '&#x2584;'),
				3 => new SpriteElement(null, $golemColour, '&#x258c;'),
				4 => new SpriteElement(null, $golemColour, '&Pi;'),
				5 => new SpriteElement(null, $golemColour, '&#x2590;'),
				]);

			$spr_golem_corpse = new Sprite([
				3 => new SpriteElement(null, $golemColour, '&#x2584;'),
				4 => new SpriteElement($golemColour, '#000', '-'),
				5 => new SpriteElement(null, $golemColour, '&#x2584;'),
				]);

			return [
				SPRI_DEFAULT => $spr_golem,
				SPRI_CORPSE => $spr_golem_corpse,
			];
		}


	}

	public function bigSetup()
	{
		$golemColour = $this->material->colour;

		$tl = new Sprite([
			2 => new SpriteElement($golemColour, '#000', '.'),
			3 => new SpriteElement($golemColour, null, ' '),
			4 => new SpriteElement($golemColour, null, ' '),
			5 => new SpriteElement($golemColour, null, ' '),
			]);

		$tr = new Sprite([
			0 => new SpriteElement($golemColour, '#000', '.'),
			3 => new SpriteElement($golemColour, null, ' '),
			4 => new SpriteElement($golemColour, null, ' '),
			5 => new SpriteElement($golemColour, null, ' '),
			]);

//		$bl = new Sprite([
//			0 => new SpriteElement($golemColour, null, ' '),
//			2 => new SpriteElement($golemColour, null, ' '),
//			3 => new SpriteElement($golemColour, null, ' '),
//			4 => new SpriteElement(null, $golemColour, '&#x258c;'),
//			5 => new SpriteElement(null, $golemColour, '&#x258c;'),
//			]);

		$br = new Sprite([
			0 => new SpriteElement($golemColour, null, ' '),
			2 => new SpriteElement($golemColour, null, ' '),
			3 => new SpriteElement(null, $golemColour, '&#x2590;'),
			4 => new SpriteElement(null, $golemColour, '&#x2590;'),
			5 => new SpriteElement($golemColour, null, ' '),
			]);

		$br_corpse = new Sprite([
			0 => new SpriteElement($golemColour, '#000', '-'),
			3 => new SpriteElement($golemColour, null, ' '),
			4 => new SpriteElement($golemColour, null, ' '),
			5 => new SpriteElement(null, $golemColour, '&#x2584;'),
			]);

		$this->constituents[0][1] = new ObjectConstituent([SPRI_DEFAULT => $br, SPRI_CORPSE => $br_corpse]);
		$this->constituents[-1][0] = new ObjectConstituent([$tl]);
		$this->constituents[-1][1] = new ObjectConstituent([$tr]);

		$dropees = [];
		$dropees[-1][0] = null;
		$dropees[-1][1] = null;
		$this->addBehaviour(new dbhv_dropConstituentsOnDeath($dropees));
	}

	public function applyMaterialProperties()
	{
		$arrays = ['DMGs', 'DMGs_def', 'DSs'];

		foreach ($arrays as $statType)
		{
			if (!isset($this->$statType)) continue;

			if (isset($this->$statType) && !empty($this->$statType))
			{
				foreach ($this->$statType as $key => &$value)
				{
					$multiplier = 0;
					$array = $this->material->$statType;
					if (isset($array[$key])) $multiplier += $array[$key] -1;
					$value = $value + ($value * $multiplier);
				}
			}
		}
	}
}


