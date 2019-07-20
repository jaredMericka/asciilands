<?php

class set_stove extends AssetSet
{
	const TINT_TOP = 2;

	public $col_iron;

	public $col_fire_1 = '#f60';
	public $col_fire_2 = '#f90';
	public $col_fire_3 = '#fc0';

	public function __construct($col_iron = null)
	{
		$this->col_iron = $col_iron ? $col_iron : '#222';
	}

	public function t_stove_top ($col_iron = null)
	{
		$col_iron = $this->getColour($this->col_iron, $col_iron);
		$col_iron_top = tint($col_iron, self::TINT_TOP);

		return new Tile($col_iron_top, ['&nbsp;', '&nbsp;', '&nbsp;', '&#x2584;', '&#x2584;', '&#x2584;'], TPL_HIGHOBSTACLE, $col_iron);
	}

	public function scn_stove_l ($col_iron = null) { return new Scenery($this->spr_stove_l($col_iron), TPL_HIGHOBSTACLE); }
	public function spr_stove_l ($col_iron = null)
	{
		$col_iron = $this->getColour($this->col_iron, $col_iron);

		return new Sprite([
			[
				0 => new SpriteElement(null,$col_iron, '&#x2590;'),
				1 => new SpriteElement($this->col_fire_1, $col_iron, '&#x256b;'),
				2 => new SpriteElement($this->col_fire_1, $col_iron, '&#x256b;'),
				3 => new SpriteElement(null,$col_iron, '&#x2590;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement(null,$col_iron, '&#x2580;'),
			],
			[
				0 => new SpriteElement(null,$col_iron, '&#x2590;'),
				1 => new SpriteElement($this->col_fire_2, $col_iron, '&#x256b;'),
				2 => new SpriteElement($this->col_fire_2, $col_iron, '&#x256b;'),
				3 => new SpriteElement(null,$col_iron, '&#x2590;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement(null,$col_iron, '&#x2580;'),
			],
			[
				0 => new SpriteElement(null,$col_iron, '&#x2590;'),
				1 => new SpriteElement($this->col_fire_3, $col_iron, '&#x256b;'),
				2 => new SpriteElement($this->col_fire_3, $col_iron, '&#x256b;'),
				3 => new SpriteElement(null,$col_iron, '&#x2590;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement(null,$col_iron, '&#x2580;'),
			],
			[
				0 => new SpriteElement(null,$col_iron, '&#x2590;'),
				1 => new SpriteElement($this->col_fire_2, $col_iron, '&#x256b;'),
				2 => new SpriteElement($this->col_fire_2, $col_iron, '&#x256b;'),
				3 => new SpriteElement(null,$col_iron, '&#x2590;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement(null,$col_iron, '&#x2580;'),
			],
		]);
	}

	public function scn_stove_r ($col_iron = null) { return new Scenery($this->spr_stove_r($col_iron), TPL_HIGHOBSTACLE); }
	public function spr_stove_r ($col_iron = null)
	{
		$col_iron = $this->getColour($this->col_iron, $col_iron);

		return new Sprite([
			[
				0 => new SpriteElement($this->col_fire_1, $col_iron, '&#x256b;'),
				1 => new SpriteElement($this->col_fire_1, $col_iron, '&#x256b;'),
				2 => new SpriteElement(null,$col_iron, '&#x258c;'),
				3 => new SpriteElement(null,$col_iron, '&#x2580;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement(null,$col_iron, '&#x258c;'),
			],
			[
				0 => new SpriteElement($this->col_fire_2, $col_iron, '&#x256b;'),
				1 => new SpriteElement($this->col_fire_2, $col_iron, '&#x256b;'),
				2 => new SpriteElement(null,$col_iron, '&#x258c;'),
				3 => new SpriteElement(null,$col_iron, '&#x2580;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement(null,$col_iron, '&#x258c;'),
			],
			[
				0 => new SpriteElement($this->col_fire_3, $col_iron, '&#x256b;'),
				1 => new SpriteElement($this->col_fire_3, $col_iron, '&#x256b;'),
				2 => new SpriteElement(null,$col_iron, '&#x258c;'),
				3 => new SpriteElement(null,$col_iron, '&#x2580;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement(null,$col_iron, '&#x258c;'),
			],
			[
				0 => new SpriteElement($this->col_fire_2, $col_iron, '&#x256b;'),
				1 => new SpriteElement($this->col_fire_2, $col_iron, '&#x256b;'),
				2 => new SpriteElement(null,$col_iron, '&#x258c;'),
				3 => new SpriteElement(null,$col_iron, '&#x2580;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement(null,$col_iron, '&#x258c;'),
			],
		]);
	}

	public function scn_stove_small ($col_iron = null) { return new Scenery($this->spr_stove_small($col_iron)); }
	public function spr_stove_small ($col_iron = null)
	{
		$col_iron = $this->getColour($this->col_iron, $col_iron);

		return new Sprite([
			[
				0 => new SpriteElement($col_iron,  null, '&nbsp;'),
				1 => new SpriteElement($this->col_fire_1, $col_iron, '&#x2593;'),
				2 => new SpriteElement($col_iron, '#777', '&#x2190;'),
				3 => new SpriteElement($col_iron,  null, '&nbsp;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement($col_iron,  null, '&nbsp;'),
			],
			[
				0 => new SpriteElement($col_iron,  null, '&nbsp;'),
				1 => new SpriteElement($this->col_fire_2, $col_iron, '&#x2593;'),
				2 => new SpriteElement($col_iron, '#777', '&#x2190;'),
				3 => new SpriteElement($col_iron,  null, '&nbsp;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement($col_iron,  null, '&nbsp;'),
			],
			[
				0 => new SpriteElement($col_iron,  null, '&nbsp;'),
				1 => new SpriteElement($this->col_fire_3, $col_iron, '&#x2593;'),
				2 => new SpriteElement($col_iron, '#777', '&#x2190;'),
				3 => new SpriteElement($col_iron,  null, '&nbsp;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement($col_iron,  null, '&nbsp;'),
			],
			[
				0 => new SpriteElement($col_iron,  null, '&nbsp;'),
				1 => new SpriteElement($this->col_fire_2, $col_iron, '&#x2593;'),
				2 => new SpriteElement($col_iron, '#777', '&#x2190;'),
				3 => new SpriteElement($col_iron,  null, '&nbsp;'),
				4 => new SpriteElement(null,$col_iron, '&#x2580;'),
				5 => new SpriteElement($col_iron,  null, '&nbsp;'),
			],
		]);
	}

	public function scn_saucepan ()
	{
		return new Scenery(new Sprite([
			0 => new SpriteElement(null,  '#aaa', '&#x2584;'),
			1 => new SpriteElement(null,  '#a60', '&#x2500;'),
		]));
	}

	public function scn_baconAndEggs ()
	{
		return new Scenery(new Sprite([
			0 => new SpriteElement('#f88', '#ffa', '|'),
			2 => new SpriteElement('#fff', '#f80', '&bull;'),
		]));
	}

	public function scn_steam ($int_locations)
	{
		$locations = intToBinaryBools($int_locations, 3);

		$l = new SpriteElement(null, '#999', '(');
		$r = new SpriteElement(null, '#999', ')');

		return new Scenery(new Sprite([
			[
				3 => $locations[0] ? $l : null,
				4 => $locations[1] ? $l : null,
				5 => $locations[2] ? $l : null,
			],
			[
				3 => $locations[0] ? $r : null,
				4 => $locations[1] ? $r : null,
				5 => $locations[2] ? $r : null,
			],
		]));
	}
}