<?php

class obhv_addBehaviourOnProximity extends ObjectBehaviour
{
	public $target;
	public $proximity;
	public $range;

	public $behaviours;

	public $isActive = false;

	public function __construct($target, $behaviours, $proximity = null, $range = null)
	{
		$this->onIdle = true;

		$this->behaviours = is_array($behaviours) ? $behaviours : [$behaviours];

		$this->proximity = isset($proximity) ? $proximity : 5;
		$this->range = isset($range) ? $range : 10;

		if ($target instanceof Player) $target = null;

		$this->target = $target;

		parent::__construct('Adds behaviours on proximity', id(), 1);
	}

	public function onIdle()
	{
		$target = isset($this->target) ? $this->target : $GLOBALS['player'];

		if ($this->isActive)
		{
			if ($this->owner->distanceFrom($target) > $this->range)
			{
				foreach ($this->behaviours as $behaviour)
				{
					$this->owner->removeBehaviour($behaviour);
				}
				$this->isActive = false;
			}
		}
		else
		{
			if ($this->owner->distanceFrom($target) < $this->proximity)
			{
				foreach ($this->behaviours as $behaviour)
				{
					$this->owner->addBehaviour($behaviour);
				}
				$this->isActive = true;
			}
		}
	}

	public function __clone()
	{
		$newBehaviours = [];

		foreach ($this->behaviours as $behaviour)
		{
			$newBehaviours[] = clone $behaviour;
		}

		$this->behaviours = $newBehaviours;
	}
}