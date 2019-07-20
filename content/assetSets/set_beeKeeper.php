<?php

class set_beeKeeper extends AssetSet
{
	public $col_bees;
	public $col_hive;

	public function __construct($col_bees = null, $col_hive = null)
	{
		$this->col_bees = $col_bees ? $col_bees : '#fa0';
		$this->col_hive = $col_hive ? $col_hive : '#997';
	}

	public function scn_hive ($colour = null)
	{
		$col_hiveWall = $this->getColour($this->col_hive, $colour);
		$col_hiveRoof = tint($col_hiveWall, 6);
		$col_hiveRoofDark = tint($col_hiveRoof, -3);

		return new Scenery(new Sprite([
			[
				0 => new SpriteElement($col_hiveRoofDark, $col_hiveRoof, '&#x258c;'),
				1 => new SpriteElement($col_hiveRoofDark, $col_hiveRoof, '&#x258c;'),
				2 => new SpriteElement($col_hiveRoofDark, $col_hiveRoof, '&#x258c;'),
				3 => new SpriteElement($col_hiveWall,  null, '&nbsp;'),
				4 => new SpriteElement($col_hiveWall, '#000', '&bull;'),
				5 => new SpriteElement($col_hiveWall,  null, '&nbsp;'),
			],
		]), TPL_HIGHOBSTACLE);
	}

	public function spr_bees ($colour)
	{
		$colour = $this->getColour($this->col_bees, $colour);


		$bees = [
			new SpriteElement(null, $colour, '.'),
			new SpriteElement(null, tint($colour, 1), '.'),
			new SpriteElement(null, tint($colour, -1), '.'),
			null,
			null,
			null,
		];

		$frames = [];

		shuffle($bees);
		$frames[] = $bees;
		shuffle($bees);
		$frames[] = $bees;
		shuffle($bees);
		$frames[] = $bees;
		shuffle($bees);
		$frames[] = $bees;

		return new Sprite($frames);
	}

	public function scn_bees ($colour = null)
	{
		return new Scenery($this->spr_bees($colour));
	}

}