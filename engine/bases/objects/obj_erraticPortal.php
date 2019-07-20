<?php

class obj_erraticPortal extends AsObject

{
	public function __construct($name, $spriteSet, $teleport_possibilities, $teleport_final_n_offset = null, $teleport_final_w_offset = null, $iterations = null)
	{
		$this->addBehaviour(
			new obhv_erraticTeleporter($teleport_possibilities, $teleport_final_n_offset, $teleport_final_w_offset, $iterations)
		);

		parent::__construct($name, $spriteSet, LAYER_PORTAL);
	}
}

