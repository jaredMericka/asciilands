<?php

class psv_frustration extends Passive
{
	public $misses;

	public function __construct ($level = 1)
	{
		$this->level = $level;

		$sprite = new Sprite([
			0 => new SpriteElement(null, '#f00', '&#x256c;'),
			3 => new SpriteElement('#f97', '#500', '&bull;'),
			4 => new SpriteElement('#f97', '#500', '&#x2584;'),
			5 => new SpriteElement('#f97', '#500', '&bull;'),
		]);

		$this->onStrike = true;
		$this->onMiss = true;

		parent::__construct('Frustration', $sprite);
	}

	public function getDescription()
	{
		$initial	= 10 + (4 * $this->level);
		$subs		= 4 * $this->level;

		return "Missing your target adds bonus damage to your next successful hit. Initial miss grants bonus damage of {$initial}% increased by {$subs}% for each subsequent miss.";
	}

	public function onStrike (Attack $attack)
	{
		if ($this->misses > 0)
		{
			$modifier = 10 + ($this->misses * 4 * $this->level);

			$attack->damage_modifiers[] = "{$modifier}%";
			update_combat("{$this->owner->name}'s frustration was unleashed! (+{$modifier}%)");
			$this->misses = 0;
		}
	}

	public function onMiss (Attack $attack)
	{
		$this->misses ++;
		update_combat("{$this->owner->name} is becoming frustrated.");
		console_echo("Frustrating misses: <<#fff>>{$this->misses}<>", '#0ff');
	}

	public function getRelatedSkills ()
	{
		return [];
	}

	public function onChangeLevel()
	{
		;
	}
}