<?php

class obj_sign extends AsObject

{
	public function __construct($name, $spriteSet, $text, $bg = null, $fg = null, $forcedHeight = null)
	{
		$this->addBehaviour(
			new obhv_showText($name, $text, $bg, $fg, $forcedHeight)
		);

		parent::__construct($name, $spriteSet, LAYER_SIGN);
	}
}

