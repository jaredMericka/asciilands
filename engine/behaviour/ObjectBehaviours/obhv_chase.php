<?php

class obhv_chase extends ObjectBehaviour
{
	public $direction;
//	public $repeats = 0;
	public $chase;
	public $target;
	public static $directions = [DIR_NORTH, DIR_SOUTH, DIR_EAST, DIR_WEST];
	public $failedDirections = [];
	public $pauseOnTouch;
	public $personalSpace;

	public function __construct(AsObject $target, $cooldown = null, $pauseOnTouch = 1, $personalSpace = null)
	{
		$this->onIdle		= true;
		$this->onCollision	= true;
		$this->onRegister	= true;

		$this->target		= $target->id;
		if (!$cooldown)	$cooldown = 1;
		$cooldown			= ($cooldown >= 0.2 ? $cooldown : 0.2);
		$this->pauseOnTouch	= $pauseOnTouch;
		// If this is extended, it must be obhv_flee.
		$this->chase = get_class($this) === get_class();

		$description = ($this->chase ? 'Chases' : 'Flees') . " {$target->name} at ". 1 / $cooldown .' RPS.'; // This will always say "chase"

		$this->personalSpace = $personalSpace;

		parent::__construct($description, BHVK_MOVEMENT, $cooldown);
	}

	public function onRegister()
	{
		if (!($this->owner instanceof Dude)) return;

		if ($this->owner->speed_fast)
		{
			console_echo("{$this->owner->name} has had the speed of its chase behaviour over-ridden.", '#f00');
			$this->cooldown = $this->owner->speed_fast;
		}
		else if ($this->owner->speed)
		{
			console_echo("{$this->owner->name} has had the speed of its chase behaviour over-ridden.", '#f00');
			$this->cooldown = $this->owner->speed;
		}
		else {console_echo("{$this->owner->name} hasn't had its speed over-ridden.", '#f00');}
	}

	public function onCollision(AsObject $receiver, $DIR)
	{
		if ($receiver === $this->target)
		{
			$this->extendcooldown($this->pauseOnTouch);
		}
	}

	public function onIdle()
	{
		global $map;

		if (!($target = $map->objectRegister[$this->target])) $this->expiaryTime = 0;

		if ($this->chase && $this->personalSpace)
		{
			console_echo("{$this->owner->name} is considering {$target->name}'s personal space. ({$this->personalSpace})", '#ccc');
			if ($this->owner->distanceFrom($target) <= $this->personalSpace)
			{
				console_echo("{$this->owner->name} is respecting {$target->name}'s personal space.", '#fff');
				return;
			}
			console_echo("{$this->owner->name} is disregarding {$target->name}'s personal space.", '#ccc');
		}

		if ($this->owner->n_offset > $target->n_offset)
		{
			$this->direction = ($this->chase ? DIR_NORTH : DIR_SOUTH);
		}
		elseif ($this->owner->n_offset < $target->n_offset)
		{
			$this->direction = ($this->chase ? DIR_SOUTH : DIR_NORTH);
		}
		else
		{
			$this->direction = null;
		}

		if (!isset($this->direction)
			|| in_array($this->direction, $this->failedDirections)
			|| mt_rand(0,1))
		{

			if ($this->owner->w_offset > $target->w_offset)
			{
				$this->direction = ($this->chase ? DIR_WEST : DIR_EAST);
			}
			elseif ($this->owner->w_offset < $target->w_offset)
			{
				$this->direction = ($this->chase ? DIR_EAST : DIR_WEST);
			}
		}

		if (in_array($this->direction, $this->failedDirections))
		{
			$otherDirections = array_diff(self::$directions, $this->failedDirections);
			if (count($otherDirections) === 0)
			{
				global $DIR_opposites;

				$this->failedDirections = [];
				$this->direction = array_rand($DIR_opposites);
			}
			else
			{
				$this->direction = $otherDirections[array_rand($otherDirections)];
			}
		}

		if (isset($this->direction))
		{
			// We have our direction? Good. Try to move in that direction.
			if ($this->owner->moveInDirection($this->direction))
			{
				// Alright, we're here now. Decrement the repeates and hit the
				// cool-down timer thing.
				// $this->triggercooldown();
				$this->failedDirections = [];
			}
			else
			{
				// Ok, that didn't work and we don't want to try again. Add
				// the direction we just moved to the list of unmovable
				// directions.
				// We still have to trigger the cooldown in case they activated
				// something with the collision EVEN THOUGH they didn't move.
				// $this->triggercooldown();
				$this->failedDirections[] = $this->direction;
			}
		}
	}
}