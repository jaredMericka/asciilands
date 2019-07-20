<?php

class set_water extends AssetSet
{
	public $colour;

	public function __construct($colour = null)
	{
		$this->colour = $colour ? $colour : '#44e';
	}

	public function t_water ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Tile($colour, ['&nbsp;','&nbsp;','~'], TPL_LOWOBSTACLE);
	}

	public function t_waterFall ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, -3);


		return new Tile($colour, ['&nbsp;','&nbsp;','|', '!'], TPL_LOWOBSTACLE);
	}
}