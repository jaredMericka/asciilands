<?php

class skl_heal extends Skill
{
	public $epCost = 50;
	public $cooldown = 10;

	public $amount;

	public function __construct ($level = 1)
	{
		$this->level = $level;

		$this->amount = 35;

		parent::__construct('Heal', new Sprite([]));
	}

	public function onUse($n_offset, $w_offset)
	{
		$this->owner->alterHP($this->amount + (5 * $this->level));
		update_sound(SND_RECOVER);

		return true;
	}

	public function getDescription()
	{
		$amount = $this->amount + (5 * $this->level);
		return "Heals caster for {$amount} HP";
	}

	public function onChangeLevel()
	{
		$this->amount = 35 + ($this->level * 10);
		$this->epCost = 50 + ($this->level * 2);
	}

	public function getRelatedSkills()
	{
		return [];
	}
}