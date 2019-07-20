<?php

class dbhv_fleeWhenAttacked extends DudeBehaviour
{
//	public $oldMovement = null;

	public $safeDistance;
	public $target;
	public $fleecooldown;

	public $dbhv;

	function __construct($safeDistance = 20, $fleecooldown = 0.4)
	{
		$this->onDefend = true;
		$this->onIdle = true;

		$description = 'Flees when attacked.';
		$this->safeDistance = $safeDistance;
		$this->fleecooldown = $fleecooldown;

		parent::__construct($description, 'flee');
	}

	function onDefend(Attack $attack)
	{
		if (!$attack->isBaseAttack) return;

		$this->target = $attack->attacker;
		console_echo("{$this->owner->name} is fleeing {$this->target->name}.");

		$this->dbhv = new obhv_flee($attack->attacker, $this->fleecooldown);

		$this->owner->addBehaviour($this->dbhv);

	}

	function onIdle()
	{
		if (isset($this->target) && $this->owner->distanceFrom($this->target) > $this->safeDistance)
		{
			console_echo("{$this->owner->name} is no longer fleeing {$this->target->name}.");

			$this->owner->removeBehaviour($this->dbhv);

			unset($this->target);
		}
	}
}