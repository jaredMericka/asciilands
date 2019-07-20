<?php

abstract class Passive extends DudeBehaviour implements SkillInterface
{
	public $key;

	public $name;
	// Behaviour class has a description variable already.
	public $sprite;
	public $level = 1;

	public $requiredLevel = 1;

	public function __construct($name, Sprite $sprite)
	{
		$this->name		= $name;
		$this->sprite	= $sprite;

		$this->key		= get_class($this);

		parent::__construct(null, null);
	}

//	public abstract function getDescription ();

	public function inspect() { update_passiveInfo($this); }

	public function __debugInfo()
	{
		return [
			'name' => $this->name,
			'description' => $this->getDescription(),
			'sprite' => $this->sprite,
			'level' => $this->level,
			'owner' => $this->owner->name,
		] + parent::__debugInfo();
	}
}