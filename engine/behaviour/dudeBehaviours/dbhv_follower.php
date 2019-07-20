<?php

class dbhv_follower extends DudeBehaviour
{
	public $isFollowing = false;
	public $target;
	public $movementcooldown;

	public $movementBehaviour;

	public function __construct($cooldown = 0.4)
	{
		$description = 'Colliding toggles follow behaviour.';

		$this->onReaction = true;
		$this->movementcooldown = $cooldown;

		parent::__construct($description, BHVK_PRIMARY, 2);
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		if ($instigator instanceof Player)
		{
			if ($this->isFollowing)
			{
				if ($instigator != $this->target) return;
				$this->owner->removeBehaviour($this->movementBehaviour);
				$this->movementBehaviour = null;
				$this->target = null;
				$this->owner->speak(SPSI_WAITING);
				console_echo("{$this->owner->name} has stopped following {$instigator->name}.");		//XXX
			}
			else
			{
				$this->movementBehaviour = new obhv_chase($instigator, $this->movementcooldown, 1, 3);
				$this->owner->addBehaviour($this->movementBehaviour);
				$this->target = $instigator;
				$this->owner->speak(SPSI_FOLLOWING);
				console_echo("{$this->owner->name} is following {$instigator->name}.");		//XXX
			}

			$this->isFollowing = !$this->isFollowing;
		}
//		parent::onReaction($instigator, $DIR);

	}
}