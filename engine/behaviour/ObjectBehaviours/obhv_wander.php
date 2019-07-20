<?php

class obhv_wander extends ObjectBehaviour
{
	public $direction;
	public $repeats = 0;

	public static $DIRs = [DIR_NORTH, DIR_SOUTH, DIR_WEST, DIR_EAST];

	public function __construct($cooldown = 1)
	{
		$this->onIdle		= true;
		$this->onRegister	= true;

		$description = 'Wanders around randomly.';
		$cooldown		= ($cooldown >= 0.2 ? $cooldown : 0.2);

		parent::__construct($description, BHVK_MOVEMENT, $cooldown);
	}

	public function onRegister()
	{
		if (isset($this->owner->speed))
		{
			console_echo("{$this->owner->name} has had the speed of its wander behaviour over-ridden.", '#f00');
			$this->cooldown = $this->owner->speed;
		}
		else {console_echo("{$this->owner->name} hasn't had its speed over-ridden.", '#f00');}
	}

	public function onIdle()
	{
		if ($this->owner->engagement !== null) return;

		// Do we know where we're going? If we have nothing to repeat, better
		// find out where to go.
		if ($this->repeats <= 0)
		{
			// We're wandering so it's random. Thank shit we're not chasing
			// anyone.
			$this->direction = self::$DIRs[array_rand(self::$DIRs)];
			$this->repeats = mt_rand(0,6);
		}

		if (isset($this->direction))
		{
			// We have our direction? Good. Try to move in that direction.
			if ($this->owner->moveInDirection($this->direction))
			{
				// Alright, we're here now. Decrement the repeates and hit the
				// cool-down timer thing.
				$this->repeats--;
				// $this->triggercooldown();
			}
			else
			{
				// Ok, that didn't work and we don't want to try again. Clear
				// remaining repeats to we can get some fresh instructions
				// in here.
				$this->repeats = 0;
			}
		}
	}
}