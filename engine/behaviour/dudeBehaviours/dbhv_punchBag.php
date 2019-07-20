<?php

class dbhv_punchBag extends DudeBehaviour
{
	public function __construct()
	{
		$this->onDefend = true;
		$this->onTakeHit = true;

		parent::__construct('Hard to break.', BHVK_PRIMARY, 0.4);
	}

	public function onDefend(Attack $attack)
	{
		$this->owner->speak("Damage: ~{$attack->damage}");
		$this->owner->alterHp(100);
	}

	public function onTakeHit(Attack $attack)
	{
		$this->owner->alterHp(100);
	}
}