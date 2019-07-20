<?php

class obhv_moveInDirection extends ObjectBehaviour
{
	public $DIR;
	public $paces;
	public $destroyWhenStopped;

	public function __construct($DIR, $cooldown, $paces = null, $destroyWhenStopped = null)
	{
		$this->onIdle = true;

		$this->DIR = $DIR;

		switch ($DIR)
		{
			case DIR_NORTH:
				$description = 'Moves north ';
				break;
			case DIR_SOUTH:
				$description = 'Moves south ';
				break;
			case DIR_WEST:
				$description = 'Moves west ';
				break;
			case DIR_EAST:
				$description = 'Moves east ';
				break;
		}

		$this->paces = $paces;

		$this->destroyWhenStopped = $destroyWhenStopped ? true : false;

		$description .= 'at '. 1 / $cooldown .' RPS.';

		parent::__construct($description, BHVK_MOVEMENT, $cooldown);
	}

	public function onIdle()
	{
		if ($this->owner->moveInDirection($this->DIR))
		{
			if ($this->paces && --$this->paces <= 0) $this->destroyWhenStopped ? $this->owner->destroy() : $this->delete();
		}
		else
		{
			console_echo("Destroying <<#fff>>\"{$this->owner->name}\"<> because it has stopped.", '#fda');
			if ($this->destroyWhenStopped) $this->owner->destroy();

		}
		console_echo("Paces: {$this->paces}");		//XXX
	}
}