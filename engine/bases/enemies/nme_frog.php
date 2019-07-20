<?php

class nme_frog extends Enemy
{
	public $FAC = FAC_ANIMAL;

	public $DSs = [

	];

	public $DMGs_def = [
		DMG_WATER => 500,
		DMG_POISON => 500,
		DMG_FIRE => -10,
	];

	public function __construct($DMG = null)
	{
		if ($DMG)
		{
			global $DMG_colours;
			global $DMG_names;

			$spriteSet = self::getSprite($DMG_colours[$DMG], '#000');
			$name = "{$DMG_names[$DMG]} frog";
		}
		else
		{
			$spriteSet = self::getSprite('#0a0', '#ff0');
			$name = 'giant frog';
		}

		parent::__construct($name, $spriteSet);

		if ($DMG)
		{
			$this->DMGs = [
				DMG_TRAUMA => 5,
				$DMG => 15
			];
		}
		else
		{
			$this->DMGs = [
				DMG_TRAUMA => 15
			];
		}
	}

	function getLootArray()
	{
		return [

		];
	}

	static function getSprite($bodyColour, $eyeColour)
	{
		$bodyColour_dark = tint($bodyColour, -3);

		$spr_frog_up = new Sprite([
			0 => new SpriteElement(null, $bodyColour, '&#x2514;'),
			1 => new SpriteElement($bodyColour, $eyeColour, '&#x201d;'),
			2 => new SpriteElement(null, $bodyColour, '&#x2518;'),
			3 => new SpriteElement(null, $bodyColour, '<'),
			4 => new SpriteElement(null, $bodyColour_dark, '&#x2580;'),
			5 => new SpriteElement(null, $bodyColour, '>'),
			]);

		$spr_frog_down = new Sprite([
			0 => new SpriteElement(null, $bodyColour, '<'),
			1 => new SpriteElement(null, $bodyColour_dark, '&#x2584;'),
			2 => new SpriteElement(null, $bodyColour, '>'),
			3 => new SpriteElement(null, $bodyColour_dark, '&#x250c;'),
			4 => new SpriteElement($bodyColour, $eyeColour, '&#x201e;'),
			5 => new SpriteElement(null, $bodyColour, '&#x2510;'),
			]);

		return [
			SPRI_DEFAULT => $spr_frog_down,
			SPRI_NORTH => $spr_frog_up,
			SPRI_SOUTH => $spr_frog_down
		];
	}
}