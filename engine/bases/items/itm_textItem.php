<?php

class itm_textItem extends Item
{
	function __construct($name, $description, $spriteSet, $text, $bg = null, $fg = null, $forcedHeight = null)
	{
		$this->addBehaviour(
			new ibhv_read($text, $bg, $fg, $forcedHeight)
		);

		$this->ICATs[] = ICAT_TEXT;

		parent::__construct($name, $description, $spriteSet);
	}
}
