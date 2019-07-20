<?php

class obj_waypoint extends AsObject

{
	public $WP;

	public $dest_n_offset;
	public $dest_w_offset;
	public $MAP;

	public $attainable;

	public $permitEntryDefault = true;

	public function __construct($WP, $dest_n_offset, $dest_w_offset, $MAP, $attainable = false)
	{
		$this->WP = $WP;
		$this->dest_n_offset = $dest_n_offset;
		$this->dest_w_offset = $dest_w_offset;
		$this->MAP = $MAP;
		$this->attainable = $attainable;

		parent::__construct('Waypoint', [$this->getSprite()], LAYER_PORTAL);

		$this->addBehaviour(new obhv_waypoint($WP, $dest_n_offset, $dest_w_offset, $MAP, $attainable));
	}

	public function getSprite($colour = '#0ff')
	{
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
