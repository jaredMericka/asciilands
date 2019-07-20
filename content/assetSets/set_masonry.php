<?php


class set_masonry extends AssetSet
{
	public $colour;

	const TINT_TOP				= 3;
	const TINT_PAVERS			= 3;
	const TINT_PAVERS_SIDE		= 0;
//	const TINT_PAVERS			= 5;
//	const TINT_PAVERS_SIDE		= 2;
	const TINT_DARK				= -2;
	const TINT_VOID				= -5;

	const COL_DREAMSCAPE		= '#737';
	const COL_REDBRICK			= '#832';
	const COL_DIRT				= '#972';

	public function __construct($colour = null)
	{
		$this->colour = $this->getColour('#654', $colour);
	}

	function t_bigWall ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		return new Tile($colour, ['_', 'I', '_', 'L', '_', '_'], TPL_VERTICAL);
	}

	function t_bigWall_top ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_TOP);
		return new Tile($colour, ['L', '_', '_', '_', 'I', '_'], TPL_HIGHOBSTACLE);
	}

	function t_bigWall_void ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_VOID);
		return new Tile($colour, ['L', '_', '_', '_', 'I', '_'], TPL_HIGHOBSTACLE);
	}

	function t_smallWall ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		return new Tile($colour, ['&#x2584;', '&#x2584;', '&nbsp;', '&nbsp;', '&#x2584;', '&#x2584;'], TPL_VERTICAL, -1);
	}

	function t_smallWall_top ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_TOP);
		return new Tile($colour, ['&#x2584;', '&#x2584;', '&nbsp;', '&nbsp;', '&#x2584;', '&#x2584;'], TPL_VERTICAL, -1);
	}

	function t_smallWall_void ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_VOID);
		return new Tile($colour, ['&#x2584;', '&#x2584;', '&nbsp;', '&nbsp;', '&#x2584;', '&#x2584;'], TPL_VERTICAL, -1);
	}

	function t_pavers ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_PAVERS);
		return new Tile($colour, ['L', '_', 'L', 'L', 'L', '_'], TPL_OPENGROUND, -1);
	}

	function t_stairs ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour_top = tint($colour, self::TINT_PAVERS);
		return new Tile($colour_top, ['&#x02584;', '&#x02584;', '&#x02584;', '&#x02584;', '&#x02584;', '&#x02584;'], TPL_OPENGROUND, $colour);
	}

	function t_stonyFloor ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_TOP);
		return new Tile($colour, ['O','(',')','(',')','O'], TPL_OPENGROUND, -1);
	}

	function t_cliff ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		return new Tile($colour, ['&nbsp;','_','/','/','&#x005c;','&nbsp;'], TPL_VERTICAL);
	}

	function t_cliff_top ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_TOP);
		return new Tile($colour, ['/','&nbsp;','&nbsp;','&nbsp;', '_', '/'], TPL_OPENGROUND);
	}

	function t_cliff_void ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_VOID);
		return new Tile($colour, ['/','&#x005c;','&nbsp;','&#x005c;','&nbsp;','&nbsp;'], TPL_WALL);
	}

	function t_cairn_w ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&#x2588;','&nbsp;','&nbsp;','&#x258c;', '&nbsp;', '&nbsp;'], TPL_HIGHOBSTACLE, $col_dark);
	}

	function t_cairn ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&nbsp;','&nbsp;','&nbsp;','&nbsp;', '&nbsp;', '&nbsp;'], TPL_HIGHOBSTACLE, $col_dark);
	}

	function t_cairn_s ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&nbsp;','&nbsp;','&nbsp;','&#x2584;', '&#x2588;', '&#x2588;'], TPL_HIGHOBSTACLE, $col_dark);
	}

	function t_tiles ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_TOP);


		return new Tile($colour, ['&#x2588;','&#x258c;','&nbsp;','&nbsp;', '&#x2590;', '&#x2588;'], TPL_OPENGROUND, -1);
	}

	function spr_brick ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);
		$col_top = tint($colour, self::TINT_TOP);

		return new Sprite([
			new SpriteElement($col_top, $col_dark, 'L'),
			new SpriteElement($col_top, $col_dark, '_'),
			new SpriteElement($col_top, $col_dark, '_'),
			new SpriteElement($colour, $col_top, 'L'),
			new SpriteElement($colour, $col_top, '_'),
			new SpriteElement($colour, $col_top, '_')
		]);
	}

	function scn_paverSide ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_PAVERS_SIDE);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $colour, '&#x2580;'),
			1 => new SpriteElement(null, $colour, '&#x2580;'),
			2 => new SpriteElement(null, $colour, '&#x2580;'),
		]));
	}

	function scn_rock ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_dark, '&#x2590;'),
			1 => new SpriteElement($colour, null, '&nbsp;'),
			2 => new SpriteElement(null, $colour, '&#x2584;'),
			3 => new SpriteElement($col_dark, null, '&nbsp;'),
			4 => new SpriteElement($colour, $col_dark, '&#x2584;'),
			5 => new SpriteElement($colour, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_rock_sign ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_dark, '&#x2590;'),
			1 => new SpriteElement($colour, $col_dark, '&#x2261;'),
			2 => new SpriteElement(null, $colour, '&#x2584;'),
			3 => new SpriteElement($col_dark, null, '&nbsp;'),
			4 => new SpriteElement($colour, $col_dark, '&#x2584;'),
			5 => new SpriteElement($colour, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_bigRock_tl ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			1 => new SpriteElement(null, $col_dark, '&#x2590;'),
			2 => new SpriteElement($colour, null, '&nbsp;'),
			3 => new SpriteElement(null, $col_dark, '&#x2584;'),
			4 => new SpriteElement($colour, $col_dark, '&#x258c;'),
			5 => new SpriteElement($colour, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_bigRock_bl ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_dark, null, '&nbsp;'),
			1 => new SpriteElement($col_dark, $col_dark, '&nbsp;'),
			2 => new SpriteElement($colour, null, '&nbsp;'),
			3 => new SpriteElement(null, $col_dark, '&#x2580;'),
			4 => new SpriteElement($col_dark, null, '&nbsp;'),
			5 => new SpriteElement($col_dark, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_bigRock_tr ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);

		return new Scenery(new Sprite([
			0 => new SpriteElement($colour, null, '&nbsp;'),
			1 => new SpriteElement(null, $colour, '&#x2584;'),
			3 => new SpriteElement($colour, null, '&nbsp;'),
			4 => new SpriteElement($colour, null, '&nbsp;'),
			5 => new SpriteElement(null, $colour, '&#x258c;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_bigRock_br ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			0 => new SpriteElement($colour, null, '&nbsp;'),
			1 => new SpriteElement($colour, null, '&nbsp;'),
			2 => new SpriteElement($colour, null, '&nbsp;'),
			3 => new SpriteElement($colour, $col_dark, '&#x2584;'),
			4 => new SpriteElement($colour, $col_dark, '&#x2584;'),
			5 => new SpriteElement(null, $col_dark, '&#x2580;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_cairn_l ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_dark, '&#x2584;'),
			1 => new SpriteElement($colour, null, '&nbsp;'),
			2 => new SpriteElement($colour, null, '&nbsp;'),
			3 => new SpriteElement(null, $col_dark, '&#x2580;'),
			4 => new SpriteElement($col_dark, null, '&nbsp;'),
			5 => new SpriteElement($col_dark, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_cairn_r ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			0 => new SpriteElement($colour, null, '&nbsp;'),
			1 => new SpriteElement($colour, null, '&nbsp;'),
			2 => new SpriteElement(null, $colour, '&#x2584;'),
			3 => new SpriteElement($col_dark, $colour, '&nbsp;'),
			4 => new SpriteElement($col_dark, null, '&nbsp;'),
			5 => new SpriteElement(null, $col_dark, '&#x2580;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_crack_t ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_VOID);

		return new Scenery(new Sprite([
			[
				1 => new SpriteElement(null, $colour, '&#x258c;'),
				4 => new SpriteElement($colour, null, '&nbsp;'),
			],
		]));
	}

	function scn_crack_b ($colour = null)
	{
		$colour = $this->getColour($this->colour, $colour);
		$colour = tint($colour, self::TINT_VOID);

		return new Scenery(new Sprite([
			[
				0 => new SpriteElement(null, $colour, '&#x2590;'),
				1 => new SpriteElement($colour, null, '&nbsp;'),
				2 => new SpriteElement(null, $colour, '&#x258c;'),
				3 => new SpriteElement($colour, null, '&nbsp;'),
				4 => new SpriteElement($colour, null, '&nbsp;'),
				5 => new SpriteElement(null, $colour, '&#x258c;'),
			],
		]));
	}
}
