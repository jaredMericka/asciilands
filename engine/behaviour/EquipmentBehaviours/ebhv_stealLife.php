<?php

class ebhv_stealLife extends EquipmentBehaviour
{
	public $percentage;

	function __construct($percentage)
	{
		$this->onStrike = true;

		$this->goldValue = $percentage * 0.5;

		$this->percentage = $percentage;
		$description = "Steals {$percentage}% life per hit";

		parent::__construct($description, 'stealLife');
	}

	function onStrike(Attack $attack)
	{
		$stealAmount = ($this->percentage * 0.01) * $attack->damage;
		$attack->attacker->alterHp($stealAmount);
	}
}