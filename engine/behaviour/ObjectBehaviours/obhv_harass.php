<?php

class obhv_harass extends obhv_chase
{
	public $tooClose;
	public $tooFar;

	public function __construct(AsObject $target, $cooldown, $tooClose = null, $tooFar = null)
	{
		if (!isset($tooClose)) $tooClose = 3;
		if (!isset($tooFar)) $tooFar = 8;

		$this->tooClose = $tooClose;
		$this->tooFar = $tooFar;

		parent::__construct($target, $cooldown);

		$this->description = "Tries to maintain proximity of between {$this->tooClose} and {$this->tooFar} tiles.";
	}

	public function onIdle()
	{
		if ($this->chase)
		{
			$this->chase = $this->owner->distanceFrom($this->target) > $this->tooClose;
		}
		else
		{
			$this->chase = $this->owner->distanceFrom($this->target) > $this->tooFar;
		}

		parent::onIdle();
	}
}