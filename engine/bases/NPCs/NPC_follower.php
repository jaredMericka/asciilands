<?php

class NPC_follower extends NPC
{
	public function __construct($name, $spriteSet, $gender = null, $speechFile = null)
	{
		$this->addBehaviour(
			new dbhv_follower()
		);

		parent::__construct($name, $spriteSet, $gender, $speechFile);
	}
}

