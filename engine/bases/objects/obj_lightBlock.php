<?php

class obj_lightBlock extends obj_pushBlock
{
	function __construct($name, $spriteSet, $distance = 1, $colour = null, $opacity = 0, $absoluteOpacity = true)
	{
		$this->addBehaviour(
			new obhv_illuminateWhileEngaged($distance, $colour, $opacity, $absoluteOpacity, $spriteSet[SPRI_ACTIVE])
		);

		parent::__construct($name, $spriteSet, 0.5);
	}
}