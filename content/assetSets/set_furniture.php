<?php

class set_furniture extends AssetSet
{
	public $col_frame;
	public $col_fabric;

	const TINT_TOP = 2;

	public function __construct($col_frame = null, $col_fabric = null)
	{
		$this->col_frame	= $col_frame	? $col_frame	: '#740';
		$this->col_fabric	= $col_fabric	? $col_fabric	: '#c55';
	}

	public function t_tableTop ($colour = null)
	{
		$colour = $this->getColour($this->col_frame, $colour);
		$colour = tint($colour, self::TINT_TOP);

		return new Tile($colour, ['&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;'], TPL_HIGHOBSTACLE);
	}

	public function scn_tableFront_l ($colour = null)
	{
		$colour = $this->getColour($this->col_frame, $colour);
		$colour_light = tint($colour, self::TINT_TOP);

		return new Scenery(new Sprite([
			0 => new SpriteElement($colour_light, null, '&nbsp;'),
			1 => new SpriteElement($colour_light, null, '&nbsp;'),
			2 => new SpriteElement($colour_light, null, '&nbsp;'),
			3 => new SpriteElement(null, $colour, '&#x2590;'),
			4 => new SpriteElement(null, $colour, '&#x2580;'),
			5 => new SpriteElement(null, $colour, '&#x2580;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_tableFront_m ($colour = null)
	{
		$colour = $this->getColour($this->col_frame, $colour);
		$colour_light = tint($colour, self::TINT_TOP);

		return new Scenery(new Sprite([
			0 => new SpriteElement($colour_light, null, '&nbsp;'),
			1 => new SpriteElement($colour_light, null, '&nbsp;'),
			2 => new SpriteElement($colour_light, null, '&nbsp;'),
			3 => new SpriteElement(null, $colour, '&#x2580;'),
			4 => new SpriteElement(null, $colour, '&#x2580;'),
			5 => new SpriteElement(null, $colour, '&#x2580;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_tableFront_r ($colour = null)
	{
		$colour = $this->getColour($this->col_frame, $colour);
		$colour_light = tint($colour, self::TINT_TOP);

		return new Scenery(new Sprite([
			0 => new SpriteElement($colour_light, null, '&nbsp;'),
			1 => new SpriteElement($colour_light, null, '&nbsp;'),
			2 => new SpriteElement($colour_light, null, '&nbsp;'),
			3 => new SpriteElement(null, $colour, '&#x2580;'),
			4 => new SpriteElement(null, $colour, '&#x2580;'),
			5 => new SpriteElement(null, $colour, '&#x258c;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_tableHalf_t ($colour = null)
	{
		$colour = $this->getColour($this->col_frame, $colour);
		$colour_light = tint($colour, self::TINT_TOP);

		return new Scenery(new Sprite([
			0 => new SpriteElement($colour_light, null, '&nbsp;'),
			1 => new SpriteElement($colour_light, null, '&nbsp;'),
			2 => new SpriteElement($colour_light, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_tableHalf_b ($colour = null)
	{
		$colour = $this->getColour($this->col_frame, $colour);
		$colour_light = tint($colour, self::TINT_TOP);

		return new Scenery(new Sprite([
			3 => new SpriteElement($colour_light, null, '&nbsp;'),
			4 => new SpriteElement($colour_light, null, '&nbsp;'),
			5 => new SpriteElement($colour_light, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_chair_l ($colour = null)
	{
		$colour = $this->getColour($this->col_frame, $colour);

		return new Scenery(new Sprite([
			2 => new SpriteElement(null, $colour, '&#x258c;'),
			3 => new SpriteElement(null, $colour, '&#x2590;'),
			4 => new SpriteElement(null, $colour, '&#x2580;'),
			5 => new SpriteElement(null, $colour, '&#x258c;'),
		]));
	}

	public function scn_chair_r ($colour = null)
	{
		$colour = $this->getColour($this->col_frame, $colour);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $colour, '&#x2590;'),
			3 => new SpriteElement(null, $colour, '&#x2590;'),
			4 => new SpriteElement(null, $colour, '&#x2580;'),
			5 => new SpriteElement(null, $colour, '&#x258c;'),
		]));
	}

	public function scn_bed_tl ($col_frame = null, $col_fabric = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		$col_fabric		= $this->getColour($this->col_fabric, $col_fabric);
		$col_fabric_top	= tint($col_fabric, self::TINT_TOP);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_frame_top, '&#x2584;'),
			3 => new SpriteElement($col_frame_top, null, '&nbsp;'),
			4 => new SpriteElement('#bbb', '#fff', '&#x2584;'),
			5 => new SpriteElement($col_fabric_top, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bed_tr ($col_frame = null, $col_fabric = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		$col_fabric		= $this->getColour($this->col_fabric, $col_fabric);
		$col_fabric_top	= tint($col_fabric, self::TINT_TOP);

		return new Scenery(new Sprite([
			2 => new SpriteElement(null, $col_frame_top, '&#x2584;'),
			3 => new SpriteElement($col_fabric_top, null, ' '),
			4 => new SpriteElement($col_fabric_top, null, ' '),
			5 => new SpriteElement($col_frame_top, null, ' '),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bed_bl ($col_frame = null, $col_fabric = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		$col_fabric		= $this->getColour($this->col_fabric, $col_fabric);
		$col_fabric_top	= tint($col_fabric, self::TINT_TOP);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_frame, null, ' '),
//			1 => new SpriteElement($col_fabric, $col_fabric_top, '&#x2580;'),
			1 => new SpriteElement('#888', '#bbb', '&#x2580;'),
			2 => new SpriteElement($col_fabric, $col_fabric_top, '&#x2580;'),
			3 => new SpriteElement($col_frame, null, ' '),
			4 => new SpriteElement(null, $col_frame, '&#x2580;'),
			5 => new SpriteElement(null, $col_frame, '&#x2580;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bed_br ($col_frame = null, $col_fabric = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		$col_fabric		= $this->getColour($this->col_fabric, $col_fabric);
		$col_fabric_top	= tint($col_fabric, self::TINT_TOP);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_fabric, $col_fabric_top, '&#x2580;'),
			1 => new SpriteElement($col_fabric, $col_fabric_top, '&#x2580;'),
			2 => new SpriteElement($col_frame, null, ' '),
			3 => new SpriteElement(null, $col_frame, '&#x2580;'),
			4 => new SpriteElement(null, $col_frame, '&#x2580;'),
			5 => new SpriteElement($col_frame, null, ' '),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_shelf_tl ($col_frame = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		return new Scenery(new Sprite([
		[
			3 => new SpriteElement(null,$col_frame_top, '&#x2590;'),
			4 => new SpriteElement($col_frame_top, $col_frame_top, '&nbsp;'),
			5 => new SpriteElement($col_frame_top, $col_frame_top, '&nbsp;'),
		]]), TPL_HIGHOBSTACLE);
	}

	public function scn_shelf_ml ($col_frame = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		return new Scenery(new Sprite([
		[
			0 => new SpriteElement(null,$col_frame, '&#x2590;'),
			1 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			2 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			3 => new SpriteElement(null,$col_frame, '&#x2590;'),
			4 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			5 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
		]]), TPL_HIGHOBSTACLE);
	}

	public function scn_shelf_bl ($col_frame = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		return new Scenery(new Sprite([
		[
			0 => new SpriteElement(null,$col_frame, '&#x2590;'),
			1 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			2 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
		]]), TPL_HIGHOBSTACLE);
	}

	public function scn_shelfCabinet_bl ($col_frame = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);

		return new Scenery(new Sprite([
		[
			0 => new SpriteElement(null,$col_frame, '&#x2590;'),
			1 => new SpriteElement($col_frame, $col_frame, '&nbsp;'),
			2 => new SpriteElement($col_frame, '#aaa', '&bull;'),
		]]), TPL_HIGHOBSTACLE);
	}

	public function scn_shelf_tr ($col_frame = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		return new Scenery(new Sprite([
		[
			3 => new SpriteElement($col_frame_top, $col_frame_top, '&nbsp;'),
			4 => new SpriteElement($col_frame_top, $col_frame_top, '&nbsp;'),
			5 => new SpriteElement(null,$col_frame_top, '&#x258c;'),
		]]), TPL_HIGHOBSTACLE);
	}

	public function scn_shelf_mr ($col_frame = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		return new Scenery(new Sprite([
		[
			0 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			1 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			2 => new SpriteElement(null,$col_frame, '&#x258c;'),
			3 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			4 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			5 => new SpriteElement(null,$col_frame, '&#x258c;'),
		]]), TPL_HIGHOBSTACLE);
	}

	public function scn_shelf_br ($col_frame = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		return new Scenery(new Sprite([
		[
			0 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			1 => new SpriteElement($col_frame_top, $col_frame, '&#x203e;'),
			2 => new SpriteElement(null,$col_frame, '&#x258c;'),
		]]), TPL_HIGHOBSTACLE);
	}

	public function scn_shelfCabinet_br ($col_frame = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);

		return new Scenery(new Sprite([
		[
			0 => new SpriteElement($col_frame, '#aaa', '&bull;'),
			1 => new SpriteElement($col_frame, $col_frame, '&nbsp;'),
			2 => new SpriteElement(null,$col_frame, '&#x258c;'),
		]]), TPL_HIGHOBSTACLE);
	}

	public function scn_smallTable ($col_frame = null)
	{
		$col_frame		= $this->getColour($this->col_frame, $col_frame);
		$col_frame_top	= tint($col_frame, self::TINT_TOP);

		return new Scenery(new Sprite([
		[
//			0 => new SpriteElement(null, $col_frame, '&#x2017;'),
//			1 => new SpriteElement($col_frame_top, $col_frame, '&#x2017;'),
//			2 => new SpriteElement(null, $col_frame, '&#x2017;'),
			0 => new SpriteElement(null, $col_frame_top, '&#x2590;'),
			1 => new SpriteElement($col_frame_top, null, '&nbsp;'),
			2 => new SpriteElement(null, $col_frame_top, '&#x258c;'),
			3 => new SpriteElement(null, $col_frame, '&#x2590;'),
			4 => new SpriteElement(null, $col_frame, '&#x2580;'),
			5 => new SpriteElement(null, $col_frame, '&#x258c;'),
		]]), TPL_HIGHOBSTACLE);
	}

}
