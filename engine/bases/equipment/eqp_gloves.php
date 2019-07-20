<?php

class eqp_gloves extends a_eqp_apparel
{
	const MAT_IN = 0;
	const MAT_OUT = 1;

	public $DSs_req_mod		= 0.3;
	public $DSs_mod			= 0.3;
	public $DMGs_def_mod	= 0.3;

	function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->EQP = EQP_GLOVES;
		parent::__construct($level, $name, $description, $spriteSet);
	}

	public function getShoppingLists()
	{
		return [
			'gloves' => [
				self::MAT_IN => 'mat_lightFabric',
				self::MAT_OUT => 'mat_lightFabric',
			],
			'mits' => [
				self::MAT_IN => 'mat_lightFabric',
				self::MAT_OUT => 'mat_heavyFabric',
			],
			'heveay mits' => [
				self::MAT_IN => 'mat_heavyFabric',
				self::MAT_OUT => 'mat_heavyFabric',
			],
			'gauntlets' => [
				self::MAT_IN => 'mat_heavyFabric',
				self::MAT_OUT => 'mat_metal',
			],
		];
	}

	function getSpriteSet()
	{
		$spriteSet = [];

		if		($this->materials[self::MAT_OUT] instanceof mat_metal)			$char = '&#x0448;';
		else if	($this->materials[self::MAT_OUT] instanceof mat_heavyFabric)	$char = 'w';
		else if	($this->materials[self::MAT_OUT] instanceof mat_lightFabric)	$char = '&#x03c9;';

		$spe_fingers	= new SpriteElement(null, $this->materials[self::MAT_OUT]->colour, $char);
		$spe_cuff		= new SpriteElement(null, $this->materials[self::MAT_IN]->colour, '&#x2580;');


		$spriteSet[SPRI_DEFAULT] = new Sprite([
			0 => $spe_fingers,
			2 => $spe_fingers,
			3 => $spe_cuff,
			5 => $spe_cuff
		]);

//		&#x02c6; // Another potential character...look for more.

		$spe_overSPriteChar = new SpriteElement(null, $this->materials[self::MAT_OUT]->colour, '&deg;');

		$spriteSet[SPRI_OVERSPRITE] = new Sprite([
			3 => $spe_overSPriteChar,
			5 => $spe_overSPriteChar,
		]);

		return $spriteSet;
	}

	public function getDescription()
	{
		return "{$this->materials[self::MAT_IN]->name} lined {$this->noun} of {$this->materials[self::MAT_OUT]->name}.";
	}

	function getName()
	{
		return "{$this->materials[self::MAT_OUT]->name} {$this->noun}";
	}

	protected function applyQuirks()
	{
		switch($this->DS_base)
		{
			case DS_STRENGTH:
				if (percentageToBool(30)) $this->addBehaviour(new ebhv_frustration(mt_rand(5, 10), mt_rand(10, 30)));
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