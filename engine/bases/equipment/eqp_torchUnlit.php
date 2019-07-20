<?php

class eqp_torchUnlit extends a_eqp_tool
{
	public $DSs_req_mod		= 0.2;

	public $opacity;
	public $colour;
	public $radius;

	public function __construct($radius = 2, $colour = null, $opacity = null)
	{
		if (!isset($colour)) $colour = '#f91';

		$this->colour = $colour;
		$this->radius = $radius;
		$this->opacity = $opacity;

		parent::__construct();
	}

	public function getSpriteSet()
	{
		$spe_handle = new SpriteElement(null, $this->materials['handle']->colour, '|');

		$spr_default = new Sprite([
			[
				1 => new SpriteElement(null, '#000', '.'),
				4 => $spe_handle,
			],
		]);

		$spr_oversprite= new Sprite([
			[
				0 => new SpriteElement(null, '#000', '.'),
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

	public function applyQuirks()
	{
		parent::applyQuirks();
	}

	public function getDescription()
	{
		$plural = $this->radius > 1 ? 's' : '';
		return "A torch of {$this->materials['handle']->name}. Place in a fire to light. When lit, extends view by {$this->radius} pace{$plural}.";
	}

	public function getName()
	{
		return "{$this->materials['handle']->name} {$this->noun} (extinguished)";
	}

	public function onTransform($TFI)
	{
		$torch = $this;

		switch ($TFI)
		{
			case TFI_FIRE:
			case TFI_FURNACE:
				$torch = new eqp_torch($this->radius, $this->colour, $this->opacity);
				$torch->materials = $this->materials;
				$torch->finish();

				$torch->DSs = $this->DSs;
				$torch->DSs_req = $this->DSs_req;
				break;
		}

		return $torch;
	}
}