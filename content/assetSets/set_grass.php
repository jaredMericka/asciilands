<?php

class set_grass extends AssetSet
{
	public $colour;

	public function __construct($colour = null)
	{
		$this->colour = $colour ? $colour : '#071';
	}

	public function t_grass($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Tile($colour, ['v', 'V', 'w', 'W'], TPL_OPENGROUND, 1);
	}

	public function t_longGrass($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Tile($colour, ['|', '&#x2320;', '&nbsp;'], TPL_OPENGROUND, 1);
	}
}