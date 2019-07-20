<?php

class eqp_banner extends Equipment
{
	public $EQP = EQP_BANNER;

	public function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		parent::__construct($level, $name, $description, $spriteSet);
	}

	protected function applyQuirks()
	{
		parent::applyQuirks();
	}

	public function getDescription()
	{
		;
	}

	public function getSpriteSet()
	{
		;
	}

	public function getShoppingLists()
	{
	;
	}
}