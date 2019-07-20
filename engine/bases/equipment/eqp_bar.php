<?php

class eqp_bar extends a_eqp_weapon
{
	public $DSs_mod		= 0.5;
	public $DSs_req_mod = 0.8;
	public $DMGs_mod	= 0.8;

	public $DMGDL = DMGDL_BLUNT;

	const MAT_MATERIAL	= 0;

	public $DS_base_choices = [
		DS_STRENGTH,
		DS_AGILITY
	];

	public $technique = [
		TEQT_MELEE =>
		[
			TEQ_DAMAGE			=> [DS_STRENGTH		=> 0.4],
			TEQ_HIT_CHANCE		=> [DS_DEXTERITY	=> 1],
			TEQ_CRIT_DAMAGE		=> [DS_FINESSE		=> 0.6],
			TEQ_CRIT_CHANCE		=> [DS_CONTROL		=> 0.6],
			TEQ_DEFENCE			=> [DS_RESILIENCE	=> 0.6],
			TEQ_DODGE_CHANCE	=> [DS_EVASIVENESS	=> 1],

			TEQ_ATTACK_SPEED	=> [DS_AGILITY		=> 0.6],
			TEQ_CONSISTENCY		=> [DS_CONTROL		=> 0.6],
		]
	];

	public function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->ICATs[] = ICAT_WEAPON;

		parent::__construct($level, $name, $description, $spriteSet);
	}

	public function getShoppingLists()
	{
		return [
			'stick' => [
				self::MAT_MATERIAL => 'mat_wood',
			],
			'branch' => [
				self::MAT_MATERIAL => 'mat_wood',
			],
			'pole' => [
				self::MAT_MATERIAL => 'mat_metal',
			],
			'bar' => [
				self::MAT_MATERIAL => 'mat_metal',
			],
		];
	}

	public function getSpriteSet()
	{
		$colour = $this->materials[self::MAT_MATERIAL]->colour;

		if ($this->materials[self::MAT_MATERIAL] instanceof mat_wood)
		{
			$dooDads = ['&#x2514;', '&#x250c;', '&frasl;', '&bsol;'];
			$dooDad = new SpriteElement(null, $colour, $dooDads[array_rand($dooDads)]);

			$spriteElements = [
				2 => new SpriteElement(null, $colour, '&#x258c;'),
				5 => new SpriteElement(null, $colour, '&#x258c;'),
				(mt_rand(0, 1) ? 1 : 4) => $dooDad
			];

			$overSpriteElements = [ 0 => new SpriteElement(null, $colour, '&#x2524;') ];
		}
		else // Metal
		{
			$char = mt_rand(0, 1) ? '&#x258c;' : '&#x2590;';

			$spriteElements = [
				1 => new SpriteElement(null, $colour, $char),
				4 => new SpriteElement(null, $colour, $char)
			];

			$overSpriteElements = [ 0 => new SpriteElement(null, $colour, '|') ];
		}

		$sprite		= new Sprite($spriteElements);
		$overSprite	= new Sprite($overSpriteElements);

		return [
			SPRI_DEFAULT => $sprite,
			SPRI_OVERSPRITE => $overSprite
		];
	}

	function getName()
	{
		return "{$this->materials[self::MAT_MATERIAL]->name} {$this->noun}";
	}

	function getDescription()
	{
		return "A {$this->materials[self::MAT_MATERIAL]->name} {$this->noun}.";
	}

	function applyQuirks()
	{
		// This should prevent the bar being quirky; it should just be a normal, shit item.
	}
}