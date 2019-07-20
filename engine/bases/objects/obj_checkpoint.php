<?php

class obj_checkpoint extends AsObject

{
	public function __construct()
	{
		$this->addBehaviour(new obhv_checkpoint());

		$this->permitEntryDefault = true;

		parent::__construct('Checkpoint', self::getSprite(), LAYER_CHECKPOINT);
	}

	static function getSprite ($colour = '#0ff')
	{
		$slm_u = new SpriteElement(null, $colour, '&#x25b2;');
		$slm_d = new SpriteElement(null, $colour, '&#x25bc;');

		return new Sprite([
			[
				0 => $slm_u,
				5 => $slm_d,
			],
			[
				1 => $slm_d,
				4 => $slm_u,
			],
			[
				2 => $slm_u,
				3 => $slm_d,
			],
		]);
	}
}
