<?php

class eqp_jacket extends a_eqp_apparel
{
	// Material constants
	const MAT_LINING	= 0;
	const MAT_SHELL		= 1;

	public $DSs_req_mod		= 1;
	public $DSs_mod			= 1.5;
	public $DMGs_def_mod	= 1;

	function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->EQP = EQP_CHEST;
		parent::__construct($level, $name, $description, $spriteSet);
	}

	function getShoppingLists()
	{
		return [
			'coat' => [
				self::MAT_LINING	=> 'mat_lightFabric',
				self::MAT_SHELL		=> 'mat_lightFabric'
			],
			'jacket' => [
				self::MAT_LINING	=> 'mat_lightFabric',
				self::MAT_SHELL		=> 'mat_heavyFabric'
			],
//			'maille' => [
//				self::MAT_LINING	=> 'mat_heavyFabric',
//				self::MAT_SHELL		=> 'mat_metal'
//			],
//			'plate armour' => [
//				self::MAT_LINING	=> 'mat_heavyFabric',
//				self::MAT_SHELL		=> 'mat_metal'
//			],
		];
	}

	function getSpriteSet()
	{
		$spriteSet = [];

		$col_outerLeft = tint($this->materials[self::MAT_SHELL]->colour, 1);
		$col_outerCenter = $this->materials[self::MAT_SHELL]->colour;
		$col_outerRight = tint($this->materials[self::MAT_SHELL]->colour, -1);
		$col_lining = tint($this->materials[self::MAT_LINING]->colour, -2);

		$spriteSet[SPRI_DEFAULT] = new Sprite([
			0 => new SpriteElement(null, $col_outerLeft, '&#x2584;'),
			1 => new SpriteElement($col_outerCenter, $col_lining, 'Y'),
			2 => new SpriteElement(null, $col_outerRight, '&#x2584;'),
			3 => new SpriteElement(null, $col_outerRight, '&#x2590;'),
			4 => new SpriteElement($col_outerCenter, $col_lining, '|'),
			5 => new SpriteElement(null, $col_outerRight, '&#x258c;'),
		]);

		return $spriteSet;
	}

	function getDescription()
	{
		return "{$this->materials[self::MAT_LINING]->name} lined {$this->noun} of {$this->materials[self::MAT_SHELL]->name}.";
	}

	protected function applyQuirks()
	{
		switch ($this->DS_base)
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

		if (percentageToBool(20))
		{
			$status = new Status("double {$this->noun} defence", 'Doubles defence', $this->spriteSet[SPRI_DEFAULT], 10, false, null, null, $this->DMGs_def);
			$bhv_chanceToDoubleDefence = new ebhv_chanceToGrantStatus(TRG_TAKE_HIT, $status, 30, 10);
			$this->addBehaviour($bhv_chanceToDoubleDefence);
		}

		parent::applyQuirks();
	}

	function getName()
	{
		return "{$this->materials[self::MAT_SHELL]->name} {$this->noun}";
	}
}