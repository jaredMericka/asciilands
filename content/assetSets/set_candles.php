<?php

class set_candles extends AssetSet
{
	public $col_candle;
	public $col_flames;
	public $col_wood;
	public $col_metal;

	const POS_LEFT		= 1;
	const POS_MIDDLE	= 2;
	const POS_RIGHT		= 3;

	public function __construct ($col_candle = null, $col_flames = null, $col_wood = null, $col_metal = null)
	{
		$this->col_candle	= $col_candle	? $col_candle	: '#fde';
		$this->col_flames	= $col_flames	? $col_flames	: '#fa0';
		$this->col_wood		= $col_wood		? $col_wood		: '#740';
		$this->col_metal	= $col_metal	? $col_metal	: '#ccc';
	}

	private function slm_candle ($colour)
	{
		$colour_dark = tint($colour, -2);

		return new SpriteElement($colour, $colour_dark, '&#x2590;');
	}

	private function slms_flames ($colour)
	{
		return [
			new SpriteElement(null, $colour, '&#x2666;'),
			new SpriteElement(null, $colour, '&#x25bc;'),
			new SpriteElement(null, $colour, '&#x2666;'),
			new SpriteElement(null, $colour, '&#x25b2;'),
		];
	}

	public function scn_candle ($POS = null, $col_candle = null, $col_flames = null)
	{
		$POS = $POS ? $POS : self::POS_MIDDLE;

		$col_candle	= $this->getColour($this->col_candle, $col_candle);
		$col_flames	= $this->getColour($this->col_flames, $col_flames);

		$slms_flames = $this->slms_flames($col_flames);
		$slm_candle = $this->slm_candle($col_candle);

		$POS_flames = $POS - 1;
		$POS_candle = $POS + 2;

		return new Scenery(new Sprite([
			[
				$POS_flames => $slms_flames[0],
				$POS_candle => $slm_candle,
			],
			[
				$POS_flames => $slms_flames[1],
				$POS_candle => $slm_candle,
			],
			[
				$POS_flames => $slms_flames[2],
				$POS_candle => $slm_candle,
			],
			[
				$POS_flames => $slms_flames[3],
				$POS_candle => $slm_candle,
			],
		]), TPL_HIGHOBSTACLE);
	}


}
