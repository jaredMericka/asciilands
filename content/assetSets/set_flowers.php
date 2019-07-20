<?php

class set_flowers extends AssetSet
{
	public $col_stem;

	public function __construct($col_stem = null)
	{
		$this->col_stem = $col_stem ? $col_stem : '#0a0';
	}

	public function scn_flower_1 ($col_flower = null, $col_stem = null)
	{
		$col_flower = $col_flower ? $col_flower : '#faf';

		$col_stem = $this->getColour($this->col_stem, $col_stem);

		return new Scenery(new Sprite([
			[
				0 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_stem, '|'),
				4 => new SpriteElement(null,$col_flower, '*'),
			],
			[
				0 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_stem, '|'),
				4 => new SpriteElement(null,$col_flower, '*'),
			],
			[
				1 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_stem, '&#x2320;'),
				5 => new SpriteElement(null,$col_flower, '*'),
			],
			[
				1 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_stem, '&#x2320;'),
				5 => new SpriteElement(null,$col_flower, '*'),
			],
		]));
	}

	public function scn_flower_2 ($col_flower = null, $col_stem = null)
	{
		$col_flower = $col_flower ? $col_flower : '#aaf';

		$col_stem = $this->getColour($this->col_stem, $col_stem);

		return new Scenery(new Sprite([
			[
				0 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_stem, '|'),
				5 => new SpriteElement(null,$col_flower, '*'),
			],
			[
				0 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_stem, '|'),
				5 => new SpriteElement(null,$col_flower, '*'),
			],
			[
				2 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_flower, '*'),
				5 => new SpriteElement(null,$col_stem, '|'),
			],
			[
				2 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_flower, '*'),
				5 => new SpriteElement(null,$col_stem, '|'),
			],
		]));
	}

	public function scn_flower_3 ($col_flower = null, $col_stem = null)
	{
		$col_flower = $col_flower ? $col_flower : '#fff';

		$col_stem = $this->getColour($this->col_stem, $col_stem);

		return new Scenery(new Sprite([
			[
				2 => new SpriteElement(null,$col_flower, '*'),
				4 => new SpriteElement(null,$col_flower, '*'),
				5 => new SpriteElement(null,$col_stem, '|'),
			],
			[
				2 => new SpriteElement(null,$col_flower, '*'),
				4 => new SpriteElement(null,$col_flower, '*'),
				5 => new SpriteElement(null,$col_stem, '|'),
			],
			[
				1 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_flower, '*'),
				5 => new SpriteElement(null,$col_stem, '&#92;'),
			],
			[
				1 => new SpriteElement(null,$col_flower, '*'),
				3 => new SpriteElement(null,$col_flower, '*'),
				5 => new SpriteElement(null,$col_stem, '&#92;'),
			],
		]));
	}
}
