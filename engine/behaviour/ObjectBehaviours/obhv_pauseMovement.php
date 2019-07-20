<?php

class obhv_pauseMovement extends ObjectBehaviour
{
	function __construct($duration = null)
	{
		$this->onIdle = true; //XXX

		parent::__construct('Pauses movement', BHVK_MOVEMENT);

		if (isset($duration)) $this->expireInSeconds($duration);
	}

	function onIdle()	//XXX
	{					//XXX
		console_echo("{$this->owner->name} has had its movement paused.");
	}					//XXX
}