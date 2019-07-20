<?php

class set_common extends AssetSet
{
	public function t_solid ($colour, $TPL = null)
	{
		$TPL = $TPL ? $TPL : TPL_LOWOBSTACLE;

		return new Tile($colour, ['&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;'], $TPL, $colour);
	}

	public function t_stairs ($col_top, $col_front = null)
	{
		$col_front = $col_front ? $col_front : tint($col_top, -3);

		return new Tile($col_top, ['&#x02584;', '&#x02584;', '&#x02584;', '&#x02584;', '&#x02584;', '&#x02584;'], TPL_STAIRS, $col_front);
	}

	public function scn_archDoor ($colour)
	{
		$slm_half = new SpriteElement(null, $colour, '&#x2584;');
		$slm_full = new SpriteElement($colour, null, '&nbsp;');

		return new Scenery(new Sprite([
			0 => $slm_half,
			1 => $slm_full,
			2 => $slm_half,
			3 => $slm_full,
			4 => $slm_full,
			5 => $slm_full,
		]));
	}

	public function scn_archDoor_l ($colour)
	{
		$slm_half = new SpriteElement(null, $colour, '&#x2584;');
		$slm_full = new SpriteElement($colour, null, '&nbsp;');

		return new Scenery(new Sprite([
			0 => $slm_half,
			1 => $slm_full,
			2 => $slm_full,
			3 => $slm_full,
			4 => $slm_full,
			5 => $slm_full,
		]));
	}

	public function scn_archDoor_r ($colour)
	{
		$slm_half = new SpriteElement(null, $colour, '&#x2584;');
		$slm_full = new SpriteElement($colour, null, '&nbsp;');

		return new Scenery(new Sprite([
			0 => $slm_full,
			1 => $slm_full,
			2 => $slm_half,
			3 => $slm_full,
			4 => $slm_full,
			5 => $slm_full,
		]));
	}

	public function scn_archDoor_half ($colour)
	{
		return new Scenery(new Sprite([
			3 => new SpriteElement(null, $colour, '&#x2584;'),
			4 => new SpriteElement($colour, null, '&nbsp;'),
			5 => new SpriteElement(null, $colour, '&#x2584;'),
		]));
	}

	public function scn_archDoor_half_l ($colour)
	{
		return new Scenery(new Sprite([
			3 => new SpriteElement(null, $colour, '&#x2584;'),
			4 => new SpriteElement($colour, null, '&nbsp;'),
			5 => new SpriteElement($colour, null, '&nbsp;'),
		]));
	}

	public function scn_archDoor_half_r ($colour)
	{
		return new Scenery(new Sprite([
			3 => new SpriteElement($colour, null, '&nbsp;'),
			4 => new SpriteElement($colour, null, '&nbsp;'),
			5 => new SpriteElement(null, $colour, '&#x2584;'),
		]));
	}

	public function scn_fade_up_t ($colour)
	{
		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$colour, '&#x2591;'),
			1 => new SpriteElement(null,$colour, '&#x2591;'),
			2 => new SpriteElement(null,$colour, '&#x2591;'),
			3 => new SpriteElement(null,$colour, '&#x2592;'),
			4 => new SpriteElement(null,$colour, '&#x2592;'),
			5 => new SpriteElement(null,$colour, '&#x2592;'),
		]), null, true);
	}

	public function scn_fade_up_b ($colour)
	{
		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$colour, '&#x2593;'),
			1 => new SpriteElement(null,$colour, '&#x2593;'),
			2 => new SpriteElement(null,$colour, '&#x2593;'),
			3 => new SpriteElement($colour,  null, '&nbsp;'),
			4 => new SpriteElement($colour,  null, '&nbsp;'),
			5 => new SpriteElement($colour,  null, '&nbsp;'),
		]), null, true);
	}

	public function scn_fade_down_t ($colour)
	{
		return new Scenery(new Sprite([
			0 => new SpriteElement($colour,  null, '&nbsp;'),
			1 => new SpriteElement($colour,  null, '&nbsp;'),
			2 => new SpriteElement($colour,  null, '&nbsp;'),
			3 => new SpriteElement(null,$colour, '&#x2593;'),
			4 => new SpriteElement(null,$colour, '&#x2593;'),
			5 => new SpriteElement(null,$colour, '&#x2593;'),
		]), null, true);
	}

	public function scn_fade_down_b ($colour)
	{
		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$colour, '&#x2592;'),
			1 => new SpriteElement(null,$colour, '&#x2592;'),
			2 => new SpriteElement(null,$colour, '&#x2592;'),
			3 => new SpriteElement(null,$colour, '&#x2591;'),
			4 => new SpriteElement(null,$colour, '&#x2591;'),
			5 => new SpriteElement(null,$colour, '&#x2591;'),
		]), null, true);
	}

	public function scn_fade_right ($colour)
	{
		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$colour, '&#x2591;'),
			1 => new SpriteElement(null,$colour, '&#x2592;'),
			2 => new SpriteElement(null,$colour, '&#x2593;'),
			3 => new SpriteElement(null,$colour, '&#x2591;'),
			4 => new SpriteElement(null,$colour, '&#x2592;'),
			5 => new SpriteElement(null,$colour, '&#x2593;'),
		]), null, true);
	}

	public function scn_fade_left ($colour)
	{
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
