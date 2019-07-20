<?php

class set_edges extends AssetSet
{
	public $colour;

	public function __construct($colour)
	{
		$this->colour = $colour;
	}

	function scn_n ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		1 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		2 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		]), [DIR_NORTH => TPL_VERTICAL]);
	}

	function scn_s ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		3 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		4 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		5 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		]), [DIR_SOUTH => TPL_VERTICAL]);
	}

	function scn_w ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),

		3 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		]), [DIR_WEST => TPL_VERTICAL]);
	}

	function scn_e ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		2 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),

		5 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),
		]), [DIR_EAST => TPL_VERTICAL]);
	}

	function scn_nw ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		1 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		2 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),

		3 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		]), [DIR_NORTH => TPL_VERTICAL, DIR_WEST => TPL_VERTICAL]);
	}

	function scn_ne ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		1 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		2 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),

		5 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),
		]), [DIR_NORTH => TPL_VERTICAL, DIR_EAST => TPL_VERTICAL]);
	}

	function scn_sw ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),

		3 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		4 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		5 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		]), [DIR_SOUTH => TPL_VERTICAL, DIR_WEST => TPL_VERTICAL]);
	}

	function scn_se ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		2 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),

		3 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		4 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		5 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),
		]), [DIR_SOUTH => TPL_VERTICAL, DIR_EAST => TPL_VERTICAL]);
	}

	function scn_ns ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		1 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		2 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),

		3 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		4 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		5 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		]), [DIR_NORTH => TPL_VERTICAL, DIR_SOUTH => TPL_VERTICAL]);
	}

	function scn_ew ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		2 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),

		3 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		5 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),
		]), [DIR_EAST => TPL_VERTICAL, DIR_WEST => TPL_VERTICAL]);
	}

	function scn_nsw ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		1 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		2 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),

		3 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		4 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		5 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		]), [DIR_NORTH => TPL_VERTICAL, DIR_SOUTH => TPL_VERTICAL, DIR_WEST => TPL_VERTICAL]);
	}

	function scn_nse ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		1 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		2 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),

		3 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		4 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		5 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),
		]), [DIR_NORTH => TPL_VERTICAL, DIR_SOUTH => TPL_VERTICAL, DIR_EAST => TPL_VERTICAL]);
	}

	function scn_ewn ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		1 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		2 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),

		3 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		5 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),
		]), [DIR_EAST => TPL_VERTICAL, DIR_WEST => TPL_VERTICAL, DIR_NORTH => TPL_VERTICAL]);
	}

	function scn_ews ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		2 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),

		3 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		4 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		5 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),
		]), [DIR_EAST => TPL_VERTICAL, DIR_WEST => TPL_VERTICAL, DIR_SOUTH => TPL_VERTICAL]);
	}

	function scn_nswe ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery( new Sprite([
		0 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		1 => new SpriteElement(null, tint($colour, 2, true), '&#x2580;'),
		2 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),

		3 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x258c;'),
		4 => new SpriteElement(null, tint($colour, 2, true), '&#x2584;'),
		5 => new SpriteElement(tint($colour, 2, true), tint($colour, 1, true), '&#x2590;'),
		]),	TPL_VERTICAL);
	}
}