<?php

class obhv_slide extends ObjectBehaviour
{
	public $DIR;

	public function __construct($DIR, $cooldown = 1)
	{
		$this->onIdle = true;

		$this->DIR = $DIR;

		$description = 'Moves in a direction until obstructed.';

		parent::__construct($description, BHVK_MOVEMENT, $cooldown);
	}

	public function onIdle()
	{
		if (!$this->owner->moveInDirection($this->DIR))
		{
			console_echo("{$this->owner->name} cannot slide any more.");
			$this->delete();
		}
	}
}