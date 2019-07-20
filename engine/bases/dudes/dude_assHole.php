<?php

class dude_assHole extends Dude
{
	public function __construct($name, $spriteSet)
	{
		$this->addBehaviour(
			new obhv_moveInDirection(DIR_WEST, 0.2, 37),
			new dbhv_speak('billyTheDouche')
		);

		$this->FAC	= FAC_NPC_NEUTRAL;

		parent::__construct($name, $spriteSet);
	}
}