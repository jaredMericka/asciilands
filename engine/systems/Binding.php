<?php

abstract class Binding
{
	public $index;
	public $owner;
	public $SKLS;

	public function __construct($owner, $index, $SKLS)
	{
		$this->owner	= $owner;
		$this->index	= $index;
		$this->SKLS		= $SKLS;
	}

	public function bind()
	{
		if (isset($this->owner->bindings[$this->SKLS]))
		{
			$this->owner->bindings[$this->SKLS]->unbind();
		}
		$this->owner->bindings[$this->SKLS] = $this;

		$subject = $this->getSubject();

		if ($subject->SKLS)
		{
			$this->owner->bindings[$subject->SKLS]->unbind();
		}

		$subject->SKLS = $this->SKLS;

		console_class_list($this->owner->bindings, '#afa');
	}

	public function unbind()
	{
		$this->getSubject()->SKLS = null;
		unset($this->owner->bindings[$this->SKLS]);

		console_class_list($this->owner->bindings, '#fda');
	}

	public abstract function &getSubject();
}

class ItemBinding extends Binding
{
	public function &getSubject()
	{
		return $this->owner->inventory->contents[$this->index];
	}

	public function bind()
	{
		parent::bind();
		update_items($this->getSubject());
	}
}

class SkillBinding extends Binding
{
	public function &getSubject()
	{
		return $this->owner->skills[$this->index];
	}

	public function bind()
	{
		parent::bind();
		update_skills();
	}
}