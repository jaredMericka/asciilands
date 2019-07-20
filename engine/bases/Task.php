<?php

abstract class Task
{
	public $quest;				// Link to owning quest
	public $description;		// Description of the task
	public $number;				// Number of the task within the quest

	public $EOI;				// Event of interest

	public $failureCondition = false;	// If true, completion of this task will cause the quest to fail.

	public $active		= false;
	public $complete	= false;	// Completion status of the task

	public $hidden		= false;

	// $args will differ depending on the triggering event.
	// If the task criteria have been met, compelte() sould be called from within
	// this function.
	public abstract function check($args);

	// If you need to run some shit when the task first becomes active, override
	// this function and put it in there.
	// (e.g., checking if you already have the item that you're fetching)
	public function onActivate() { }

	final public function complete()
	{
		console_echo($this->description . ' has been completed!', '#afa');

		$this->complete = true;
		$this->active = false;

		if ($this->failureCondition)
		{
			$this->quest->fail();
		}
	}

	public function getAjaxObject()
	{
		$ajaxObj = new stdClass();

		$ajaxObj->desc	= $this->description;
		$ajaxObj->num	= $this->number;
		$ajaxObj->qst	= $this->quest->name;

		$ajaxObj->fail	= $this->failureCondition;
		$ajaxObj->comp	= $this->complete;
		$ajaxObj->actv	= $this->active;

		return $ajaxObj;
	}
}