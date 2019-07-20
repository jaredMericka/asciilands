<?php

class ebhv_frustration extends EquipmentBehaviour
{
	public $percentage;
	public $currentAmp;
	public $penalty;

	public function __construct($penalty, $percentageAmp)
	{
		$this->onMiss = true;
		$this->onStrike = true;

		$this->penalty = $penalty;
		$this->percentage = $percentageAmp;
		$this->currentAmp = 0;

		$this->goldValue = $percentageAmp * 0.1;

		$description = "Deal {$penalty}% less damage but each miss increases the damage of your next hit by {$percentageAmp}%";

		parent::__construct($description, null);
	}

	public function onMiss(Attack $attack)
	{
		if ($attack->isBaseAttack)
		{
			$this->currentAmp += $this->percentage;
			console_echo("Next hit will be amped by {$this->percentage}% due to frustration", '#fda');
		}
	}

	public function onStrike(Attack $attack)
	{
		if (!$attack->isBaseAttack) return;

		if ($this->currentAmp > 0)
		{
//			$attack->damageModifier += ($this->currentAmp * 0.01);
			$attack->damage_modifiers[] = "{$this->currentAmp}%";
			console_echo("Frustration amp released! ({$this->currentAmp}%)", '#fda');
			update_combat("<<#fff>>{$this->owner->owner->name}'s<> damage was amped by <<#faa>>{$this->currentAmp}%<>!");
			$this->currentAmp = 0;
		}
		else
		{
			$attack->damage_modifiers[] = "-{$this->penalty}%";
		}
	}
}