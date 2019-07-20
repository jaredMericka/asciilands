<?php

class obj_fire extends obj_chest
{
	const SPR_LEFT	= 1;
	const SPR_MID	= 2;
	const SPR_RIGHT	= 3;

	public function __construct()
	{
		parent::__construct('Fire', [self::getSprite()]);

		$this->inventory->TFI = TFI_FIRE;
	}

	static function getSprite ($SPR = null)
	{
		$slm_fire_lp = new SpriteElement(null, '#f40', '(');
		$slm_fire_rp = new SpriteElement(null, '#f40', ')');
//		$slm_fire_us = new SpriteElement(null, '#f40', '_');
		$slm_fire_us = new SpriteElement(null, '#f40', '&#x039b;');

		switch ($SPR)
		{

			case null: return new Sprite(
				[
					[
						0 => $slm_fire_lp,
						1 => $slm_fire_lp,
						3 => $slm_fire_lp,
						4 => new SpriteElement(null, '#fa0', '&#x25b2;'),
						5 => $slm_fire_rp
					],
					[
						1=>$slm_fire_rp,
						2 => $slm_fire_rp,
						3 => $slm_fire_lp,
						4 => new SpriteElement(null, '#fa0', '&#x2666;'),
						5 => $slm_fire_rp
					],
				]);

			case self::SPR_LEFT: return new Sprite(
				[
					[
						0 => $slm_fire_lp,
						1 => $slm_fire_lp,
						2 => $slm_fire_us,
						3 => $slm_fire_lp,
						4 => new SpriteElement(null, '#fa0', '&#x25b2;'),
					],
					[
						1=>$slm_fire_rp,
						2 => $slm_fire_rp,
						3 => $slm_fire_lp,
						4 => new SpriteElement(null, '#fa0', '&#x2666;'),
					],
				]);

			case self::SPR_MID: return new Sprite(
				[
					[
						0 => $slm_fire_lp,
						1 => $slm_fire_lp,
						2 => $slm_fire_us,
						4 => new SpriteElement(null, '#fa0', '&#x25b2;'),
					],
					[
						0 => $slm_fire_us,
						1 => $slm_fire_rp,
						2 => $slm_fire_rp,
						4 => new SpriteElement(null, '#fa0', '&#x2666;'),
					],
				]);

			case self::SPR_RIGHT: return new Sprite(
				[
					[
						0 => $slm_fire_lp,
						1 => $slm_fire_lp,
						4 => new SpriteElement(null, '#fa0', '&#x25b2;'),
						5 => $slm_fire_rp,
					],
					[
						0 => $slm_fire_us,
						1 => $slm_fire_rp,
						2 => $slm_fire_rp,
						4 => new SpriteElement(null, '#fa0', '&#x2666;'),
						5 => $slm_fire_rp,
					],
				]);
		}
	}
}