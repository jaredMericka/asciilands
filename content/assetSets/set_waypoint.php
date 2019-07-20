<?php

class set_waypoint extends AssetSet
{
	public $col_tiles;
	public $col_portal;

	const TINT_DARK = -1;
	const TINT_FRONT = -2;

	public function __construct($col_tiles = null, $col_portal = null)
	{
		$this->col_tiles = $col_tiles ? $col_tiles : '#874';
		$this->col_portal = $col_portal ? $col_portal : '#0ff';
	}

	////////////////////////////////////////////
	//
	//		WAYPOINT
	//
	////////////////////////////////////////////

	public function t_wp_tl ($colour = null)
	{
		$colour = $this->getColour($this->col_tiles, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&#x2554;', '&#x2550;', '&#x2550;', '&#x2551;', '&nbsp;', '&#x2554;'], TPL_OPENGROUND, $col_dark);
	}

	public function t_wp_t ($colour = null)
	{
		$colour = $this->getColour($this->col_tiles, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&#x2550;', '&#x2550;', '&#x2550;', '&#x2550;', '&#x2550;', '&#x2550;'], TPL_OPENGROUND, $col_dark);
	}

	public function t_wp_tr ($colour = null)
	{
		$colour = $this->getColour($this->col_tiles, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&#x2550;', '&#x2550;', '&#x2557;', '&#x2557;', '&nbsp;', '&#x2551;'], TPL_OPENGROUND, $col_dark);
	}

	public function t_wp_l ($colour = null)
	{
		$colour = $this->getColour($this->col_tiles, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&#x2551;', '&nbsp;', '&#x2551;', '&#x2551;', '&nbsp;', '&#x2551;'], TPL_OPENGROUND, $col_dark);
	}

	public function t_wp_bl ($colour = null)
	{
		$colour = $this->getColour($this->col_tiles, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&#x2551;', '&nbsp;', '&#x255a;', '&#x255a;', '&#x2550;', '&#x2550;'], TPL_OPENGROUND, $col_dark);
	}

	public function t_wp_br ($colour = null)
	{
		$colour = $this->getColour($this->col_tiles, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&#x255d;', '&nbsp;', '&#x2551;', '&#x2550;', '&#x2550;', '&#x255d;'], TPL_OPENGROUND, $col_dark);
	}

	public function t_wp_mid ($colour = null)
	{
		$colour = $this->getColour($this->col_tiles, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

		return new Tile($colour, ['/', '&#x203e;', '&#x005c;', '&#x005c;', '_', '/'], TPL_OPENGROUND, $col_dark);
	}

	////////////////////////////////////////////
	//
	//		CHECKPOINT
	//
	////////////////////////////////////////////

	public function t_cp ($colour = null)
	{
		$colour = $this->getColour($this->col_tiles, $colour);
		$col_dark = tint($colour, self::TINT_DARK);

//		return new Tile($colour, ['&#x2554;', '&#x2550;', '&#x2557;', '&#x255a;', '&#x2550;', '&#x255d;'], TPL_OPENGROUND, $col_dark);
		return new Tile($colour, ['&#x25b2;', '&#x25bc;', '&#x25b2;', '&#x25bc;', '&#x25b2;', '&#x25bc;'], TPL_OPENGROUND, $col_dark);
	}

	function scn_tile_side ($colour = null)
	{
		$colour	= $this->getColour($this->col_tiles, $colour);
		$colour	= tint($colour, self::TINT_FRONT);

		$slm = new SpriteElement(null, $colour, '&#x2580;');

		return new Scenery(new Sprite([
			0 => $slm,
			1 => $slm,
			2 => $slm,
		]));
	}

	public function spr_portal ($colour = null)
	{
		$colour = $this->getColour($this->col_portal, $colour);

		$slm_fs = new SpriteElement(null, $colour, '/');
		$slm_bs = new SpriteElement(null, $colour, '&#x005c;');
		$slm_rp = new SpriteElement(null, $colour, ')');
		$slm_lp = new SpriteElement(null, $colour, '(');
		$slm_b = new SpriteElement(null, $colour, '|');

		return new Sprite([
			[
				$slm_fs, $slm_rp, $slm_bs,
				$slm_bs, $slm_lp, $slm_fs
			],
			[
				$slm_fs, $slm_b, $slm_bs,
				$slm_bs, $slm_b, $slm_fs
			],
			[
				$slm_fs, $slm_lp, $slm_bs,
				$slm_bs, $slm_rp, $slm_fs
			],
			[
				$slm_fs, $slm_b, $slm_bs,
				$slm_bs, $slm_b, $slm_fs
			]
		]);
	}
}
