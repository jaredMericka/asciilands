<?php

class set_wood extends AssetSet
{
	public $colour;

	const TINT_LIGHT	= 1;
	const TINT_DARK		= -2;
	const TINT_DARKER	= -3;

	public function __construct($colour = null)
	{
		$this->colour = $colour ? $colour : '#960';
	}

	public function sprs_doubleDoors ($col_panel = null, $col_handle = null, $col_gap = null)
	{
		$col_panel = $this->getColour($this->colour, $col_panel);
		$col_handle = $col_handle ? $col_handle : '#ddd';
		$col_handle = $col_gap ? $col_gap : tint($col_panel, 2);

		$slm_doubleDoorPanel	= new SpriteElement($col_panel, null, '&nbsp;');
		$slm_doubleDoorGap		= new SpriteElement($col_panel, $col_gap, '&#x2502;');
		$slm_doubleDoorHandle	= new SpriteElement($col_panel, $col_handle, '&bull;');

		$slm_doubleDoorOpenLeft	= new SpriteElement(null, $col_panel, '&#x258C');
		$slm_doubleDoorOpenRight	= new SpriteElement(null, $col_panel, '&#x2590');

		$spr_closed = new Sprite([
			0 => $slm_doubleDoorPanel,
			1 => $slm_doubleDoorGap,
			2 => $slm_doubleDoorPanel,

			3 => $slm_doubleDoorHandle,
			4 => $slm_doubleDoorGap,
			5 => $slm_doubleDoorHandle
		]);

		$spr_open = new Sprite([
			0 => $slm_doubleDoorOpenLeft,
			2 => $slm_doubleDoorOpenRight,
			3 => $slm_doubleDoorOpenLeft,
			5 => $slm_doubleDoorOpenRight
		]);

		return [
			SPRI_CLOSED => $spr_closed,
			SPRI_OPEN => $spr_open
		];

	}

	function t_planks ($colour = null)
	{
		$colour		= $this->getColour($this->colour, $colour);
		$col_dark	= tint($colour, self::TINT_DARK);

		return new Tile($colour, array('&#x0251C;', '&#x02524;', '&#x02502;', '&#x02502;', '&#x0251C;', '&#x02524;'), TPL_OPENGROUND, $col_dark);
	}

	function t_stairs ($colour = null)
	{
		$colour		= $this->getColour($this->colour, $colour);
		$col_dark	= tint($colour, self::TINT_DARK);

		return new Tile($colour, ['&#x02584;', '&#x02584;', '&#x02584;', '&#x02584;', '&#x02584;', '&#x02584;'], TPL_OPENGROUND, $col_dark);
	}

	function scn_planksSide ($colour = null)
	{
		$colour	= $this->getColour($this->colour, $colour);
		$colour	= tint($colour, self::TINT_DARK);

		$slm = new SpriteElement(null, $colour, '&#x2580;');

		return new Scenery(new Sprite([
			0 => $slm,
			1 => $slm,
			2 => $slm,
		]));
	}

	function scn_planksSide_post ($colour = null)
	{
		$colour	= $this->getColour($this->colour, $colour);
		$col_dark	= tint($colour, self::TINT_DARK);

		$slm = new SpriteElement(null, $col_dark, '&#x2580;');

		return new Scenery(new Sprite([
			0 => $slm,
			1 => new SpriteElement($col_dark, null, '&nbsp;'),
			2 => $slm,
			4 => new SpriteElement($colour, $col_dark, '&#x02590;'),
		]), TPL_VERTICAL);
	}

	function scn_post ($colour = null)
	{
		$colour	= $this->getColour($this->colour, $colour);
		$col_dark	= tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			1 => new SpriteElement($colour, $col_dark, '&#x02590;'),
			4 => new SpriteElement($colour, $col_dark, '&#x02590;'),
		]), TPL_VERTICAL);
	}

	function scn_railing ($colour = null)
	{
		$colour	= $this->getColour($this->colour, $colour);
		$colour	= tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			3 => new SpriteElement(null, $colour, '&#x2580;'),
			4 => new SpriteElement($colour,  null, '&nbsp;'),
			5 => new SpriteElement(null, $colour, '&#x2580;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_fence ($colour = null)
	{
		$colour	= $this->getColour($this->colour, $colour);
		$col_dark	= tint($colour, self::TINT_DARK);

		return new Scenery(new Sprite([
			0 => new SpriteElement(null,$col_dark, '&#x2584;'),
			1 => new SpriteElement($colour, $col_dark, '&#x2590;'),
			2 => new SpriteElement(null,$col_dark, '&#x2584;'),
			3 => new SpriteElement(null,$col_dark, '&#x2584;'),
			4 => new SpriteElement($colour, $col_dark, '&#x2590;'),
			5 => new SpriteElement(null,$col_dark, '&#x2584;'),
		]), TPL_HIGHOBSTACLE);
	}

	function scn_crate ($col_top = null)
	{
		$col_top	= $this->getColour($this->colour, $col_top);
		$col_front	= tint($col_top, self::TINT_DARK);

		$col_top_dark	= tint($col_top, -1);
		$col_front_dark	= tint($col_front, -1);

		return new Scenery(new Sprite([
			[
				0 => new SpriteElement(null,$col_top, '&#x2590;'),
				1 => new SpriteElement($col_top, $col_top_dark, '&#x039e;'),
				2 => new SpriteElement(null,$col_top, '&#x258c;'),
				3 => new SpriteElement(null,$col_front, '&#x2590;'),
				4 => new SpriteElement($col_front, $col_front_dark, '&#x2044;'),
				5 => new SpriteElement(null,$col_front, '&#x258c;'),
			],
		]), TPL_HIGHOBSTACLE);
	}

//	function createWoodAssets($name, $colour)
//	{
//		$colour_light = tint($colour, 1, true);
//		$colour_dark = tint($colour, -2, true);
//		$colour_vDark = tint($colour, -3, true);
//
//		$slm_post = new SpriteElement($colour, $colour_dark, '&#x02590;');
//		$slm_bridgeSide = new SpriteElement(null, $colour_dark, '&#x2580;');
//
//		$spr_planksSide = new Sprite([
//			$slm_bridgeSide, $slm_bridgeSide, $slm_bridgeSide
//		]);
//

//
//
//		return [
//			"t_{$name}_planks" => new Tile($colour_light, array('&#x0251C;', '&#x02524;', '&#x02502;', '&#x02502;', '&#x0251C;', '&#x02524;'), TPL_OPENGROUND, -2),
//
//			"t_{$name}_logWall" => new Tile($colour, ['&#x2580;', '&#x2580;', '&#x2580;', '&#x2580;', '&#x2580;', '&#x2580;'], TPL_VERTICAL, 1),
//			"t_{$name}_logFloor" => new Tile($colour_light, ['&#x251c;', '&#x2524;', '&#x2502;', '&#x2524;', '&#x251c;', '&#x253c;'], TPL_OPENGROUND, -1),
//
//			"t_{$name}_wallTop_h" => new Tile($colour_vDark, ['&#x2580;', '&#x2580;', '&#x2580;', '&#x2584;', '&#x2584;', '&#x2584;'], TPL_HIGHOBSTACLE, 1),
//			"t_{$name}_wallTop_v" => new Tile($colour_vDark, ['&#x258c;', '&nbsp;', '&#x2590;', '&#x258c;', '&nbsp;', '&#x2590;'], TPL_HIGHOBSTACLE, 1),
//			"t_{$name}_wallTop_x" => new Tile($colour_vDark, ['/', '&OverBar;', '&#x005c;', '&#x005c;', '_', '/'], TPL_HIGHOBSTACLE, 1),
//
//			"t_{$name}_wickerRoof_l" => new Tile($colour, ['/', '/', '/', '/', '/', '/'], TPL_HIGHOBSTACLE),
//			"t_{$name}_wickerRoof_m" => new Tile($colour, ['|', '|', '|', '|', '|', '|'], TPL_HIGHOBSTACLE),
//			"t_{$name}_wickerRoof_r" => new Tile($colour, ['\\', '\\', '\\', '\\', '\\', '\\'], TPL_HIGHOBSTACLE),
//
//			"t_{$name}_tileRoof" => new Tile($colour, ['U', 'U', 'U', 'U', 'U', 'U'], TPL_HIGHOBSTACLE, -2),
//
//		];
//	}

}