<?php

class eqp_belt extends a_eqp_apparel
{
	const MAT_STRAP = 0;
	const MAT_BUCKLE = 1;

	public $DSs_req_mod		= 0.1;
	public $DSs_mod			= 0.1;
	public $DMGs_def_mod	= 0.1;

	function __construct($level = null, $name = null, $description = null, $spriteSet = null)//, $DSs_req = null, $DSs = null, $DMGs_def = null, $behaviours = null)
	{
		$this->EQP = EQP_BELT;
		parent::__construct($level, $name, $description, $spriteSet);//, EQP_BELT, $DSs_req, $DSs, $DMGs_def, $behaviours);
	}

	public function getShoppingLists()
	{
		return [
			'belt' => [
				self::MAT_STRAP => 'mat_heavyFabric',
				self::MAT_BUCKLE => 'mat_metal',
			]
		];
	}

	public function getSpriteSet()
	{
		return [ SPRI_DEFAULT => new Sprite([
			3 => new SpriteElement(tint($this->materials[self::MAT_STRAP]->colour, 1), '#000', '&#x0387;'),
			4 => new SpriteElement($this->materials[self::MAT_STRAP]->colour, $this->materials[self::MAT_BUCKLE]->colour, 'E'),
			5 => new SpriteElement(tint($this->materials[self::MAT_STRAP]->colour, -1), '#000', '&#x0387;'),
		])];
	}

	function getName()
	{
		return "{$this->materials[self::MAT_STRAP]->name} {$this->noun}";
	}

	function getDescription()
	{
		return "A {$this->materials[self::MAT_STRAP]->name} {$this->noun} with a buckle of {$this->materials[self::MAT_BUCKLE]->name}.";
	}

	protected function applyQuirks()
	{
		parent::applyQuirks();
	}
}