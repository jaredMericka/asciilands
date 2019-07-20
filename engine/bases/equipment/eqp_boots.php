<?php

class eqp_boots extends a_eqp_apparel
{
	const MAT_IN = 0;
	const MAT_OUT = 1;

	public $DSs_req_mod		= 0.4;
	public $DSs_mod			= 0.4;
	public $DMGs_def_mod	= 0.5;

	function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->EQP = EQP_BOOTS;
		parent::__construct($level, $name, $description, $spriteSet);
	}

	public function getShoppingLists()
	{
		return [
			'slippers' => [
				self::MAT_IN => 'mat_lightFabric',
				self::MAT_OUT => 'mat_lightFabric',
			],
			'boots' => [
				self::MAT_IN => 'mat_lightFabric',
				self::MAT_OUT => 'mat_heavyFabric',
			],
			'rugged boots' => [
				self::MAT_IN => 'mat_heavyFabric',
				self::MAT_OUT => 'mat_heavyFabric',
			],
			'heavy boots' => [
				self::MAT_IN => 'mat_heavyFabric',
				self::MAT_OUT => 'mat_metal',
			],
		];
	}

	function getSpriteSet()
	{
		$spriteSet = [];

		$spriteSet[SPRI_DEFAULT] = new Sprite([
			0 => new SpriteElement(null, $this->materials[self::MAT_IN]->colour, '&#x2584;'),
			2 => new SpriteElement(null, $this->materials[self::MAT_IN]->colour, '&#x2584;'),
			3 => new SpriteElement(null, $this->materials[self::MAT_OUT]->colour, '&#x255c;'),
			5 => new SpriteElement(null, $this->materials[self::MAT_OUT]->colour, '&#x2559;'),
		]);

		return $spriteSet;
	}

	function getName()
	{
		return "{$this->materials[self::MAT_OUT]->name} {$this->noun}";
	}

	public function getDescription()
	{
		return "{$this->materials[self::MAT_IN]->name} lined {$this->noun} of {$this->materials[self::MAT_OUT]->name}.";
	}

	protected function applyQuirks()
	{
		switch($this->DS_base)
		{
			case DS_STRENGTH:

				break;
			case DS_AGILITY:

				break;
			case DS_MAGIC:

				break;
			case DS_CHARISMA:

				break;
			case DS_INTELLECT:

				break;
		}

		parent::applyQuirks();
	}
}