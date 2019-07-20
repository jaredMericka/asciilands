<?php

class obhv_moveRandomly extends ObjectBehaviour
{
	public $failedDirections = [];

	public function __construct($cooldown)
	{
		$this->onRegister	= true;
		$this->onIdle		= true;

		$description = 'Moves randomly';

		parent::__construct($description, BHVK_MOVEMENT, $cooldown);
	}

	public function onRegister()
	{
		if ($this->owner->speed_fast)
		{
			console_echo("{$this->owner->name} has had the speed of its random movement behaviour over-ridden.", '#f00');
			$this->cooldown = $this->owner->speed_fast;
		}
		else if ($this->owner->speed)
		{
			console_echo("{$this->owner->name} has had the speed of its random movement behaviour over-ridden.", '#f00');
			$this->cooldown = $this->owner->speed;
		}
		else {console_echo("{$this->owner->name} hasn't had its speed over-ridden.", '#f00');}
	}

	public function onIdle()
	{
		$directions = array_diff([DIR_NORTH, DIR_SOUTH, DIR_WEST, DIR_EAST], $this->failedDirections);
		if (empty($directions))
		{
			$this->failedDirections = [];
			$directions = [DIR_NORTH, DIR_SOUTH, DIR_WEST, DIR_EAST];
		}

		$direction = $directions[array_rand($directions)];

		if ($this->owner->moveInDirection($direction))
		{
			$this->failedDirections = [];
		}
		else
		{
			$this->failedDirections[] = $direction;
		}
	}
}