<?php

class obhv_moveAlongPath extends ObjectBehaviour
{
	public $path;

	public $pathStageIndex;

	public $stageCount;
	public $DIR;


	function __construct($cooldown, $path)
	{
		$this->onIdle = true;

		$this->path = $path;

		$this->pathStageIndex = 0;

		$this->DIR = $path[0][0];
		$this->stageCount = $path[0][1];

		$description = 'Moves along a pre-defined path';

		parent::__construct($description, BHVK_MOVEMENT, $cooldown);
	}

	function onIdle()
	{
		if ($this->stageCount <= 0)
		{
			$this->pathStageIndex ++;

			if (!isset($this->path[$this->pathStageIndex]))
			{
				$this->pathStageIndex = 0;
			}

			$this->DIR = $this->path[$this->pathStageIndex][0];
			$this->stageCount = $this->path[$this->pathStageIndex][1];
		}

		console_echo($this->DIR);

		if ($this->owner->moveInDirection($this->DIR))
		{
			$this->stageCount--;
		}
	}
}