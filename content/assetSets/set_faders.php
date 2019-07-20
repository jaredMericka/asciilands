<?php

class set_faders extends AssetSet
{
	public $colour;

	public function __construct($colour)
	{
		$this->colour = $colour;
	}

	public function t_solid ($colour = null, $TPL = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$TPL = $TPL ? $TPL : TPL_LOWOBSTACLE;

		return new Tile($colour, ['&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;'], $TPL, $colour);
	}

	public function scn_down_1 ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$colour, '&#x2591;'),
			1 => new SpriteElement(null,$colour, '&#x2591;'),
			2 => new SpriteElement(null,$colour, '&#x2591;'),
			3 => new SpriteElement(null,$colour, '&#x2592;'),
			4 => new SpriteElement(null,$colour, '&#x2592;'),
			5 => new SpriteElement(null,$colour, '&#x2592;'),
		]), null, true);
	}

	public function scn_down_2 ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$colour, '&#x2593;'),
			1 => new SpriteElement(null,$colour, '&#x2593;'),
			2 => new SpriteElement(null,$colour, '&#x2593;'),
			3 => new SpriteElement($colour,  null, '&nbsp;'),
			4 => new SpriteElement($colour,  null, '&nbsp;'),
			5 => new SpriteElement($colour,  null, '&nbsp;'),
		]), null, true);
	}

	public function scn_up_1 ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery(new Sprite([
			0 => new SpriteElement($colour,  null, '&nbsp;'),
			1 => new SpriteElement($colour,  null, '&nbsp;'),
			2 => new SpriteElement($colour,  null, '&nbsp;'),
			3 => new SpriteElement(null,$colour, '&#x2593;'),
			4 => new SpriteElement(null,$colour, '&#x2593;'),
			5 => new SpriteElement(null,$colour, '&#x2593;'),
		]), null, true);
	}

	public function scn_up_2 ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$colour, '&#x2592;'),
			1 => new SpriteElement(null,$colour, '&#x2592;'),
			2 => new SpriteElement(null,$colour, '&#x2592;'),
			3 => new SpriteElement(null,$colour, '&#x2591;'),
			4 => new SpriteElement(null,$colour, '&#x2591;'),
			5 => new SpriteElement(null,$colour, '&#x2591;'),
		]), null, true);
	}

	public function scn_right ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$colour, '&#x2591;'),
			1 => new SpriteElement(null,$colour, '&#x2592;'),
			2 => new SpriteElement(null,$colour, '&#x2593;'),
			3 => new SpriteElement(null,$colour, '&#x2591;'),
			4 => new SpriteElement(null,$colour, '&#x2592;'),
			5 => new SpriteElement(null,$colour, '&#x2593;'),
		]), null, true);
	}

	public function scn_left ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$colour, '&#x2593;'),
			1 => new SpriteElement(null,$colour, '&#x2592;'),
			2 => new SpriteElement(null,$colour, '&#x2591;'),
			3 => new SpriteElement(null,$colour, '&#x2593;'),
			4 => new SpriteElement(null,$colour, '&#x2592;'),
			5 => new SpriteElement(null,$colour, '&#x2591;'),
		]), null, true);
	}
}
