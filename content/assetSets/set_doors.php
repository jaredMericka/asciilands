<?php

class set_doors extends AssetSet
{
	public $col_panel;
	public $col_handle;
	public $col_hinges;

	public function __construct ($col_panel = null, $col_handle = null, $col_hinges = null)
	{
		$this->col_panel = $col_panel ? $col_panel : '#620';
		$this->col_handle = $col_handle ? $col_handle : '#bbb';
		$this->col_hinges = $col_hinges ? $col_hinges : $this->col_handle;
	}

	public function sprs_door_r ($col_panel = null, $col_handle = null, $col_hinges = null)
	{
		$col_panel = $this->getColour($this->col_panel, $col_panel);
		$col_handle = $this->getColour($this->col_handle, $col_handle);
		$col_hinges = $this->getColour($this->col_hinges, $col_hinges);

		$spr_closed = new Sprite([
			[
				0 => new SpriteElement($col_panel, $col_hinges, '&#x2561;'),
				1 => new SpriteElement($col_panel, null, '&nbsp;'),
				2 => new SpriteElement($col_panel, null, '&nbsp;'),
				3 => new SpriteElement($col_panel, $col_hinges, '&#x2561;'),
				4 => new SpriteElement($col_panel, null, '&nbsp;'),
				5 => new SpriteElement($col_panel, $col_handle, '&bull;'),
			],
		]);

		$spr_open = new Sprite([
			[
				0 => new SpriteElement(null, $col_panel, '&#x258c;'),
				3 => new SpriteElement(null, $col_panel, '&#x258c;'),
			],
		]);

		return [
			SPRI_CLOSED => $spr_closed,
			SPRI_OPEN => $spr_open,
		];
	}

	public function sprs_door_l ($col_panel = null, $col_handle = null, $col_hinges = null)
	{
		$col_panel = $this->getColour($this->col_panel, $col_panel);
		$col_handle = $this->getColour($this->col_handle, $col_handle);
		$col_hinges = $this->getColour($this->col_hinges, $col_hinges);

		$spr_closed = new Sprite([
			[
				0 => new SpriteElement($col_panel, null, '&nbsp;'),
				1 => new SpriteElement($col_panel, null, '&nbsp;'),
				2 => new SpriteElement($col_panel, $col_hinges, '&#x255e;'),
				3 => new SpriteElement($col_panel, $col_handle, '&bull;'),
				4 => new SpriteElement($col_panel, null, '&nbsp;'),
				5 => new SpriteElement($col_panel, $col_hinges, '&#x255e;'),
			],
		]);

		$spr_open = new Sprite([
			[
				2 => new SpriteElement(null, $col_panel, '&#x2590;'),
				5 => new SpriteElement(null, $col_panel, '&#x2590;'),
			],
		]);

		return [
			SPRI_CLOSED => $spr_closed,
			SPRI_OPEN => $spr_open,
		];
	}
}