<?php

class set_rug extends AssetSet
{
	public $col_rug;
	public $col_trim;
	public $col_detail;

	public function __construct($col_rug = null, $col_trim = null, $col_detail = null)
	{
		$this->col_rug		= $col_rug		? $col_rug		: '#99e';
		$this->col_detail	= $col_detail	? $col_detail	: tint($col_rug, -3);
		$this->col_trim		= $col_trim		? $col_trim		: '#ffc';
	}

	public function scn_trim_l ($col_trim = null)
	{
		$col_trim = $this->getColour($this->col_trim, $col_trim);
		$col_trim_dark = tint($col_trim, -6);

		return new Scenery(new Sprite([
			2 => new SpriteElement($col_trim, $col_trim_dark, '&#x039e;'),
			5 => new SpriteElement($col_trim, $col_trim_dark, '&#x039e;'),
		]));
	}

	public function scn_trim_r ($col_trim = null)
	{
		$col_trim = $this->getColour($this->col_trim, $col_trim);
		$col_trim_dark = tint($col_trim, -6);

		return new Scenery(new Sprite([
			0 => new SpriteElement($col_trim, $col_trim_dark, '&#x039e;'),
			3 => new SpriteElement($col_trim, $col_trim_dark, '&#x039e;'),
		]));
	}

	public function t_triangles_t ($col_rug = null, $col_detail = null)
	{
		$col_rug = $this->getColour($this->col_rug, $col_rug);
		$col_detail = $this->getColour($this->col_detail, $col_detail);

		return new Tile($col_rug, ['&#x2550;', '&#x2550;', '&#x2550;', '&#x25b2;', '&#x25b2;', '&#x25b2;'], true, $col_detail);

	}

	public function t_triangles_b ($col_rug = null, $col_detail = null)
	{
		$col_rug = $this->getColour($this->col_rug, $col_rug);
		$col_detail = $this->getColour($this->col_detail, $col_detail);

		return new Tile($col_rug, ['&#x25bc;', '&#x25bc;', '&#x25bc;', '&#x2550;', '&#x2550;', '&#x2550;'], true, $col_detail);

	}
}
