<?php

abstract class a_eqp_tool extends Equipment
{
	public function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->ICATs[] = ICAT_TOOL;
		$this->EQP = EQP_HAND;

		parent::__construct($level, $name, $description, $spriteSet);
	}

	protected function applyQuirks()
	{
	
	}
}