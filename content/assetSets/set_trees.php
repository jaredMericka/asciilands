<?php

class set_trees extends AssetSet
{
	public $col_leaves;
	public $col_trunk;

	public $spe_detail;
	public $spe_detail_dark;

	const TINT_TRUNK_DARK	= -2;
	const TINT_TRUNK_LIGHT	= 2;
	const TINT_TRUNK_TOP	= 5;

	const TINT_LEAVES_DARK	= -2;

	public function __construct($col_leaves = null, $col_trunk = null, SpriteElement $spe_detail = null)
	{
		$this->col_leaves		= $this->getColour('#0a0', $col_leaves);
		$this->col_trunk		= $this->getColour('#840', $col_trunk);

		$this->spe_detail = $spe_detail;
	}

	public function getDetailElement($colour, $isDark)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);

		if ($isDark)
		{
			if ($this->spe_detail)
			{
				$slm = $this->spe_detail;
				$slm->bg = tint($col_leaves, self::TINT_LEAVES_DARK);
				return $slm;
			}
			else
			{
				return new SpriteElement(tint($col_leaves, self::TINT_LEAVES_DARK), $col_leaves, 'v');
			}
		}
		else
		{
			if ($this->spe_detail)
			{
				$slm = $this->spe_detail;
				$slm->bg = $col_leaves;
				return $slm;
			}
			else
			{
				return new SpriteElement($col_leaves, tint($col_leaves, self::TINT_LEAVES_DARK), 'v');
			}
		}
	}

	function scn_trunk ($colour = null)
	{
		$col_trunk = $this->getColour($this->col_trunk, $colour);
		$col_trunk_dark = tint($col_trunk, self::TINT_TRUNK_DARK);
		$col_trunk_light = tint($col_trunk, self::TINT_TRUNK_LIGHT);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_trunk_light, '&#x2590;'),
			1 => new SpriteElement($col_trunk, $col_trunk_light, '&#x2502;'),
			2 => new SpriteElement(null, $col_trunk_dark, '&#x258c;'),
			3 => new SpriteElement(null, $col_trunk_light, '&#x2590;'),
			4 => new SpriteElement($col_trunk, $col_trunk_light, '&#x2502;'),
			5 => new SpriteElement(null, $col_trunk_dark, '&#x258c;'),
		]), TPL_HIGHOBSTACLE);
	}
//
	function scn_stump ($colour = null)
	{
		$col_trunk = $this->getColour($this->col_trunk, $colour);
		$col_trunk_dark = tint($col_trunk, self::TINT_TRUNK_DARK);
		$col_trunk_light = tint($col_trunk, self::TINT_TRUNK_LIGHT);

		$col_trunk_top = tint($col_trunk, self::TINT_TRUNK_TOP);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_trunk_top, '&#x2590;'),
			1 => new SpriteElement($col_trunk_top, $col_trunk_light, '@'),
			2 => new SpriteElement(null, $col_trunk_top, '&#x258c;'),
			3 => new SpriteElement(null, $col_trunk_light, '&#x2590;'),
			4 => new SpriteElement($col_trunk, $col_trunk_light, '&#x2502;'),
			5 => new SpriteElement(null, $col_trunk_dark, '&#x258c;'),
		]), TPL_HIGHOBSTACLE);

	}


	public function scn_tree_tl ($colour = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_dark = $this->getDetailElement($col_leaves, true);

		return new Scenery(new Sprite([
			1 => new SpriteElement(null, $col_leaves_dark, '&#x2584;'),
			2 => new SpriteElement($col_leaves, $col_leaves_dark, '&#x2580;'),
			3 => new SpriteElement(null, $col_leaves_dark, '&#x2584;'),

			4 => $spe_detail_dark,

			5 => new SpriteElement($col_leaves, $col_leaves_dark, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_tree_tr ($colour = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_light = $this->getDetailElement($col_leaves, false);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_leaves, $col_leaves_dark, '&nbsp;'),
			1 => new SpriteElement(null, $col_leaves, '&#x2584;'),
			3 => new SpriteElement($col_leaves, $col_leaves_dark, '&nbsp;'),

			4 => $spe_detail_light,

			5 => new SpriteElement(null, $col_leaves, '&#x2584;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_tree_tm ($colour = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_light = $this->getDetailElement($col_leaves, false);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
			1 => new SpriteElement($col_leaves, $col_leaves_dark, '&nbsp;'),

			2 => $spe_detail_light,

			3 => new SpriteElement($col_leaves, $col_leaves_dark, '&nbsp;'),
			4 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
			5 => new SpriteElement($col_leaves, $col_leaves_dark, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_tree_bl ($colour = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_leaves_dark, '&#x2580;'),
			1 => new SpriteElement($col_leaves_dark, $col_leaves, '&nbsp;'),
			2 => new SpriteElement($col_leaves, $col_leaves_dark, '&#x2584;'),
			4 => new SpriteElement(null, $col_leaves_dark, '&#x2580;'),
			5 => new SpriteElement($col_leaves_dark, $col_leaves, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_tree_br ($colour = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_dark = $this->getDetailElement($col_leaves, true);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_leaves, $col_leaves_dark, '&nbsp;'),
			1 => new SpriteElement($col_leaves, $col_leaves_dark, '&#x2584;'),
			2 => new SpriteElement(null, $col_leaves_dark, '&#x2580;'),

			3 => $spe_detail_dark,

			4 => new SpriteElement(null, $col_leaves_dark, '&#x2580;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_tree_bm ($colour = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_light = $this->getDetailElement($col_leaves, false);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
			0 => $spe_detail_light,
			1 => new SpriteElement($col_leaves, $col_leaves_dark, '&nbsp;'),
			2 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
			3 => new SpriteElement($col_leaves_dark, $col_leaves, '&nbsp;'),
			4 => new SpriteElement($col_leaves_dark, $col_leaves, 'v'),
			5 => new SpriteElement($col_leaves_dark, $col_leaves, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bush_w ($colour = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_dark = $this->getDetailElement($col_leaves, true);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_leaves_dark, '&#x2584;'),
			1 => new SpriteElement($col_leaves, null, '&nbsp;'),
			2 => new SpriteElement($col_leaves, null, '&nbsp;'),
			3 => new SpriteElement(null, $col_leaves_dark, '&#x2580;'),
			4 => new SpriteElement($col_leaves_dark, null, '&nbsp;'),
			5 => $spe_detail_dark,
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bush_ew_stem ($col_leaves = null, $col_stem = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $col_leaves);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$col_stem = $this->getColour($this->col_trunk, $col_stem);
		$col_stem = tint($col_stem, -2);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_leaves, null, '&nbsp;'),
			1 => new SpriteElement(null, $col_leaves, '&#x2584;'),
			2 => new SpriteElement($col_leaves, null, '&nbsp;'),
			3 => new SpriteElement($col_leaves_dark, $col_stem, '&#x2514;'),
			4 => new SpriteElement($col_leaves_dark, $col_stem, '&#x252c;'),
			5 => new SpriteElement($col_leaves_dark, $col_stem, '&#x2518;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bush_ew ($colour = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_light = $this->getDetailElement($col_leaves, false);

		return new Scenery(new Sprite([
			0 => $spe_detail_light,
			1 => new SpriteElement(null, $col_leaves, '&#x2584;'),
			2 => new SpriteElement($col_leaves, null, '&nbsp;'),
			3 => new SpriteElement($col_leaves_dark, null, '&nbsp;'),
			4 => new SpriteElement($col_leaves_dark, $col_leaves, 'v'),
			5 => new SpriteElement($col_leaves_dark, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bush_ew_trunk ($col_leaves = null, $col_stem = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $col_leaves);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$col_stem = $this->getColour($this->col_trunk, $col_stem);
		$col_stem = tint($col_stem, -2);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
			1 => new SpriteElement(null, $col_leaves, '&#x2584;'),
			2 => new SpriteElement($col_leaves, null, '&nbsp;'),
			3 => new SpriteElement($col_leaves_dark, null, '&nbsp;'),
			4 => new SpriteElement($col_leaves_dark, $col_stem, 'Y'),
			5 => new SpriteElement($col_leaves_dark, null, '&nbsp;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bush_e ($col_leaves = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $col_leaves);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_light = $this->getDetailElement($col_leaves, false);

		return new Scenery(new Sprite([
			0 => $spe_detail_light,
			1 => new SpriteElement($col_leaves, null, '&nbsp;'),
			2 => new SpriteElement(null, $col_leaves, '&#x2584;'),
			3 => new SpriteElement($col_leaves_dark, $col_leaves, 'v'),
			4 => new SpriteElement($col_leaves_dark, null, '&nbsp;'),
			5 => new SpriteElement(null, $col_leaves_dark, '&#x2580;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bush_ns ($col_leaves = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $col_leaves);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_light = $this->getDetailElement($col_leaves, false);
//		$spe_detail_dark = $this->getDetailElement($col_leaves, true);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_leaves_dark, '&#x2590;'),
			1 => $spe_detail_light,
			2 => new SpriteElement($col_leaves, $col_leaves_dark, '&#x2584;'),
			3 => new SpriteElement($col_leaves_dark, $col_leaves, 'v'),
//			3 => $spe_detail_dark,
			4 => new SpriteElement($col_leaves, null, '&nbsp;'),
			5 => new SpriteElement(null, $col_leaves, '&#x258c;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bush_nsew ($col_leaves = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $col_leaves);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		$spe_detail_light = $this->getDetailElement($col_leaves, false);

		return new Scenery(new Sprite([
			0 => $spe_detail_light,
			1 => new SpriteElement($col_leaves, null, '&nbsp;'),
			2 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
			3 => new SpriteElement($col_leaves, $col_leaves_dark, '&#x2584;'),
			4 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
			5 => new SpriteElement($col_leaves, $col_leaves_dark, '&#x2584;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bush_n ($col_leaves = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $col_leaves);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);


		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $col_leaves_dark, '&#x2584;'),
			1 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
			2 => new SpriteElement(null, $col_leaves, '&#x2584;'),
			3 => new SpriteElement($col_leaves, $col_leaves_dark, '&#x258c;'),
			4 => new SpriteElement($col_leaves, null, '&nbsp;'),
			5 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_bush_s ($col_leaves = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $col_leaves);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);


		return new Scenery(new Sprite([
			0 => new SpriteElement($col_leaves, $col_leaves_dark, '&#x258c;'),
			1 => new SpriteElement($col_leaves, $col_leaves_dark, 'v'),
			2 => new SpriteElement($col_leaves, null, '&nbsp;'),
			3 => new SpriteElement(null, $col_leaves_dark, '&#x2580;'),
			4 => new SpriteElement($col_leaves, $col_leaves_dark, '&#x2584;'),
			5 => new SpriteElement(null, $col_leaves_dark, '&#x2580;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function t_forest ($colour = null)
	{
		$col_leaves = $this->getColour($this->col_leaves, $colour);
		$col_leaves_dark = tint($col_leaves, self::TINT_LEAVES_DARK);

		return new Tile($col_leaves, ['&nbsp;', '&nbsp;', 'v'], TPL_HIGHOBSTACLE, $col_leaves_dark);
	}
}