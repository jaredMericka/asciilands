<?php

class dude_punchBag extends Dude
{
	protected $DSs = [
		DS_HP_MAX		=> 10000,
		DS_REGENERATION	=> 100,
	];

	public $DMGs_def = [
//		DMG_TRAUMA => 15,
//		DMGDL_BLUNT => 0,
//		DMGDL_CUT => 6,
//		DMG_COLD => -10,
//		DMGDL_VAPOUR => 15,
	];

	public function __construct()
	{
		$this->FAC	= FAC_MONSTER;

		$this->stationary = true;

		$spriteSet = [new Sprite([
			new SpriteElement(null, "#ccc", '&#x250c;'),
			new SpriteElement(null, "#ccc", '&#x252c;'),
			new SpriteElement(null, "#ccc", '&#x2510;'),

			new SpriteElement(null, "#ccc", '&#x2502;'),
			new SpriteElement('#f22', "#800", '&#x2590;'),
			new SpriteElement(null, "#ccc", '&#x2502;'),
		])];

		parent::__construct('Punchbag', $spriteSet);
	}
}