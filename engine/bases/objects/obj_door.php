<?php
/**
 * Sprites: OPEN, CLOSED
 */
class obj_door extends AsObject

{
	public function __construct($name, $spriteSet, $keyItem = null)
	{
		$this->addBehaviour(
			new obhv_door($spriteSet[SPRI_OPEN], $keyItem)
		);

		$spriteSet[SPRI_DEFAULT] = $spriteSet[SPRI_CLOSED];

		parent::__construct($name, $spriteSet, LAYER_DOOR_CLOSED);
	}
}
