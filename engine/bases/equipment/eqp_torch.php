<?php

class eqp_torch extends a_eqp_tool
{
	public $DSs_req_mod		= 0.2;

	public $colour;
	public $radius;

	public function __construct($radius = 2, $colour = null, $opacity = null)
	{
		if (!isset($colour)) $colour = '#f91';

		$this->colour = $colour;
		$this->radius = $radius;

		$this->addBehaviour(new ebhv_illuminate($radius, $colour, $opacity));

		parent::__construct();
	}

	public function getSpriteSet()
	{
		$spe_handle = new SpriteElement(null, $this->materials['handle']->colour, '|');

		$spr_default = new Sprite([
			[
				1 => new SpriteElement(null, $this->colour, '&#x25bc;'),
				4 => $spe_handle,
			],
			[
				1 => new SpriteElement(null, $this->colour, '&#x2666;'),
				4 => $spe_handle,
			],
		]);

		$spr_oversprite= new Sprite([
			[
				0 => new SpriteElement(null, $this->colour, '&#x25bc;'),
			],
			[
				0 => new SpriteElement(null, $this->colour, '&#x2666;'),
			],
		]);

		return [
			SPRI_DEFAULT	=> $spr_default,
			SPRI_OVERSPRITE	=> $spr_oversprite
		];
	}

	public function getShoppingLists()
	{
		return [
			'torch' => [ 'handle' => 'mat_wood' ],
		];
	}

	protected function applyQuirks()
	{
		parent::applyQuirks();
	}

	public function getDescription()
	{
		return "A torch of {$this->materials['handle']->name}.";
	}

	public function getName()
	{
		if (!$this->noun) $this->noun = 'torch';
		return "{$this->materials['handle']->name} {$this->noun} (lit)";
	}
}