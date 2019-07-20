<?php

class obhv_pushable extends ObjectBehaviour
{
	public $slideSpeed;

	public function __construct($cooldown = 1, $slideSpeed = null)
	{
		$this->onReaction = true;

		$this->slideSpeed = $slideSpeed;

		$description = 'Can be pushed by any dude with the "canPush" property.';
		if ($slideSpeed) $description .= ' Will slide in the direction pushed until obstructed.';

		parent::__construct($description, BHVK_PUSHABLE, $cooldown);
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
        if ($instigator instanceof Dude && $instigator->canPush)
        {
			// Oosenupt - See if this can be improved now that the DIR is being passed in.
			$n_offsetNew = $this->owner->n_offset + ($this->owner->n_offset - $instigator->n_offset);
            $w_offsetNew = $this->owner->w_offset + ($this->owner->w_offset - $instigator->w_offset);

			if ($this->owner->move($n_offsetNew, $w_offsetNew))
            {
				$this->owner->permitEntry = true;

				if ($this->slideSpeed)
				{
					$DIRs = [DIR_NORTH => 'north', DIR_SOUTH => 'south', DIR_EAST => 'east', DIR_WEST => 'west'];

					$DIR = offsetToDirection(
						$this->owner->n_offset - $instigator->n_offset,
						$this->owner->w_offset - $instigator->w_offset
					);

					console_echo ("{$this->owner->name} is sliding {$DIRs[$DIR]}");

					$behaviour = new obhv_slide($DIR, $instigator->speed_fast);
					$behaviour->readyTime = $_SERVER['REQUEST_TIME_FLOAT'] + 0.01;

					$this->owner->addBehaviour($behaviour);
				}

                return true;
            }
            return false;
        }
        else
        {
            return false;
        }
	}
}