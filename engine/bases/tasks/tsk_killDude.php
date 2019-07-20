<?php

class tsk_KillDude extends Task
{
	public $mask;
	public $enforceLevel;

	public function __construct(Mask $mask, $enforceLevel = null)
	{
		$this->mask = $mask;
		$this->enforceLevel = isset($enforceLevel) ? $enforceLevel : true;

		$name = isset($mask->name) ? $mask->name : $mask->class;

		$suffix = $this->enforceLevel ? ' at a level equal to, or higher than, your own.' : '';

		$this->description = "Kill {$name}{$suffix}.";

		$this->EOI = EOI_ATTACK;
	}

	public function check($args)
	{
		global $player;
		list($attack) = $args;

		$minLevel = $this->enforceLevel ? $player->level : 0;
		if ($minLevel && $attack->leveled) $minLevel--;

		if ($attack->attacker !== $player
			|| !$attack->kill
			|| $player->level < $minLevel
			|| !$this->mask->compare($attack->target)
		) return;

		$this->complete();
	}
}