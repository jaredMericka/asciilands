<?php

abstract class Boon
{
	public $name;
	public $description;

	public function __construct ($name, $description)
	{
		$this->name			= $name;
		$this->description	= $description;
	}

	abstract function deliver(Player &$player);
}

class boon_skill extends Boon
{
	public $skill;

	public function __construct (SkillInterface $skill)
	{
		$name = "Practice {$skill->name}";
		$plural = $skill->level > 1 ? 's' : '';
		$description = "Gain {$skill->level} level{$plural} of {$skill->name}";
		$this->skill = $skill;

		parent::__construct($name, $description);
	}

	public function deliver(Player &$player)
	{
		if (!$player->addSkill($this->skill))
		{
			console_echo("Already had skill <<#fff>>\"{$this->skill->name}\"<> so we'll just level it up instead", '#fda');
			console_echo($this->skill->key);

			$player->upgradeSkill($this->skill);
		}

		if ($this->skill instanceof Passive)	update_passives();
		else									update_skills();
	}
}

//class boon_passive extends Boon
//{
//	public $passive;
//
//	public function __construct (Passive $passive)
//	{
//		$name = "Learn {$passive->name}";
//		$plural = $passive->level > 1 ? 's' : '';
//		$description = "Gain {$passive->level} level{$plural} of {$passive->name}";
//		$this->passive = $passive;
//
//		parent::__construct($name, $description);
//	}
//
//	public function deliver(Player &$player)
//	{
//		if (!$player->addPassive($this->passive))
//		{
//			$player->passive[$this->passive->key]->level += $this->passive->level;
//		}
//
//		update_passives();
//	}
//}

class boon_stats extends Boon
{
	public $DSs;

	public function __construct($DSs)
	{
		global $DS_names;
		$this->DSs = $DSs;

		$max = max($DSs);
		$maxIndex = array_search($max, $DSs);

		$name = "+{$max} to {$DS_names[$maxIndex]}";

		$description = [];

		foreach ($this->DSs as $DS => $amount)
		{
			$description[] = "+{$amount} to {$DS_names[$DS]}";
		}

		$description = 'Grants ' . implode(', ', $description);

		parent::__construct($name, $description);
	}

	public function deliver(Player &$player)
	{
		global $DS_names;

		foreach ($this->DSs as $DS => $amount)
		{
//			$player->DSs[$DS] += $amount;
			$player->{$DS_names[$DS]} += $amount;
		}

		update_stats(array_keys($this->DSs));

		$thought = [];
		foreach ($this->DSs as $DS => $amount)
		{
			$thought[] = "+{$amount} to {$DS_names[$DS]}";
		}
		$thought = 'Granted ' . implode(', ', $thought);

		update_thoughts($thought);
	}
}
