<?php

class NPC_basic extends NPC
{
	public function __construct($name, $spriteSet = null, $gender = GND_MALE, $speechFile = null)
	{
		$this->addBehaviour(
			new obhv_wander(1),
			new dbhv_speak(),
			new dbhv_giveRandomItem(),
			new dbhv_giveWay()
		);

		$this->FAC		= FAC_NPC_NEUTRAL;
		$this->canPush	= true;

		parent::__construct($name, $spriteSet, $gender, $speechFile);
	}
}