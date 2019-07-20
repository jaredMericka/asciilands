<?php

class obj_pushBlock extends AsObject

{
	public function __construct($name, $spriteSet, $cooldown = null, $slideSpeed = null)
	{
		if (!isset($cooldown)) $cooldown = 0.4;

		$this->addBehaviour(
			new obhv_pushable($cooldown, $slideSpeed)
		);

		parent::__construct($name, $spriteSet, LAYER_PUSHBLOCK);
	}

}
