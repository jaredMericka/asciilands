<?php

class tsk_killDudes extends Task
{
	public $mask;
	public $count;
	public $allowedMaps;

	public $descriptionBase;

	public function __construct(Mask $mask, $count = 1, $allowedMaps = null)
	{
		if (isset($allowedMaps))
		{
			if (is_string($allowedMaps)) $allowedMaps = [$allowedMaps];
			$this->allowedMaps = $allowedMaps;
		}

		$this->EOI = EOI_ATTACK;

		$this->mask				= $mask;
		$this->count			= $count;

		$name = isset($mask->name) ? $mask->name : 'dude';
		$plural = $count > 1 ? 's' : '';

		$this->descriptionBase = "Kill {$count} {$name}{$plural}.";
		$this->description = $this->descriptionBase;
	}

	public function check($args)
	{
		if (isset($this->allowedMaps))
		{
			global $map;
			if (array_search($map->mapPath, $this->allowedMaps) === false)
			{
				$dudeName = $args[0]->target->name;							//XXX
				$allowedMaps = implode('<>, <<#faf>>', $this->allowedMaps);	//XXX
				console_echo("You killed the right dude (<<#aaf>>{$dudeName}<>) but you killed it in the wrong map. Allowed maps are: <<#faf>>{$allowedMaps}<>.", '#fff');
				return;
			}
		}

		global $player;
		list($attack) = $args;

		if ($attack->attacker !== $player
			|| !$attack->kill
			|| !$this->mask->compare($attack->target)
		) return;

		if (--$this->count < 1)
		{
			$this->description = $this->descriptionBase;
			$this->complete();
		}
		else
		{
			$this->description = $this->descriptionBase . " ({$this->count} to go)";
			update_task($this);
		}
	}
}