<?php

class eqp_shield extends a_eqp_apparel
{
	public $DSs_req_mod		= 1.1;
	public $DSs_mod			= 0.6;
	public $DMGs_def_mod	= 1.2;

	const MAT_WOOD = 0;
	const MAT_METAL = 1;

	function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->EQP = EQP_OFFHAND;
		parent::__construct($level, $name, $description, $spriteSet);
	}

	public function getShoppingLists()
	{
		return [
			'shield' =>
			[
				self::MAT_WOOD => 'mat_wood',
				self::MAT_METAL => 'mat_metal',
			]
		];
	}

	function getSpriteSet()
	{
		$spriteSet = [];

		$wood_light = $this->materials[self::MAT_WOOD]->colour;
		$wood_dark = tint($this->materials[self::MAT_WOOD]->colour, -2);
		$metal_light = $this->materials[self::MAT_METAL]->colour;
		$metal_dark = tint($this->materials[self::MAT_METAL]->colour, -2);

		$symbols = [
			'&#x03a8;',
			'&#x25bc;',
			'&#x2666;',
			'&#x2665;',
			'&#x2020;',
			'&#x2021;',
			'&#x263c;',
			'&#x0428;',
		];

		$symbol = $symbols[array_rand($symbols)];

		$spriteSet[SPRI_DEFAULT] = new Sprite([
			0 => new SpriteElement($wood_light, $metal_light, '&#x258c;'),
			1 => new SpriteElement($wood_dark, $metal_dark, $symbol),
			2 => new SpriteElement($wood_dark, $metal_dark, '&#x2590;'),
			3 => new SpriteElement(null, $metal_light, '&#x2590;'),
			4 => new SpriteElement($wood_dark, $metal_dark, '&#x2584;'),
			5 => new SpriteElement(null, $metal_dark, '&#x258c;'),
			]);

//		&#x02c6; // Another potential character...look for more.

		$spe_overSPriteChar = new SpriteElement(null, $this->materials[self::MAT_WOOD]->colour, ']');

		$spriteSet[SPRI_OVERSPRITE] = new Sprite([
			5 => $spe_overSPriteChar,
		]);

		return $spriteSet;
	}

	public function getDescription()
	{
		return "{$this->materials[self::MAT_WOOD]->name} shield {$this->noun} edged with {$this->materials[self::MAT_METAL]->name}.";
	}

	function getName()
	{
		return "{$this->materials[self::MAT_WOOD]->name} {$this->noun}";
	}

//	protected function applyQuirks()
//	{
//		switch($this->DS_base)
//		{
//			case DS_STRENGTH:
//				$this->addBehaviour(new ebhv_frustration(mt_rand(5, 10), mt_rand(10, 30)));
//				break;
//			case DS_AGILITY:
//
//				break;
//			case DS_MAGIC:
//
//				break;
//			case DS_CHARISMA:
//
//				break;
//			case DS_INTELLECT:
//
//				break;
//		}
//	}
}