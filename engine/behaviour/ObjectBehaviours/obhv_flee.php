<?php

class obhv_flee extends obhv_chase
{
	public function __construct(AsObject $target, $cooldown = 1, $pauseOnTouch = 1)
	{
		parent::__construct($target, $cooldown, $pauseOnTouch);
	}
}