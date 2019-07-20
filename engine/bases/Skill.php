<?php

abstract class Skill implements SkillInterface
{
	public $key; // Oosenupt - may be able to get away with not having this in the future.

	public $name;
	public $sprite;
	public $level	= 1;
	public $exp;

	public $requiredLevel	= 1;

	public $epCost	= 0;
	public $hpCost	= 0;
	public $cooldown;
	public $readyTime;

	// Owner of the skill
	public $owner;
	public $SKLS;

	public $range;

	public function __construct($name, Sprite $sprite)
	{
		$this->name			= $name;
		$this->sprite		= $sprite;
		$this->key			= get_class($this);

		$this->onChangeLevel();
	}

//	public abstract function getDescription ();
	public abstract function onUse($n_offset, $w_offset);

	/*
	 * onChangeLevel will be called every time the skill changes level. It should
	 * be used to alter level-dependant aspects of the skill. Registration should
	 * not be presumed.
	 */
//	public abstract function onChangeLevel();

	/*
	 * the onRegister function is used to initialise parts of the skill that
	 * require access to the skill's owner.
	 */
	public function onRegister (Dude $owner)
	{
		$this->owner = $owner;
	}

	public function inspect() { update_skillInfo($this); }

//	public function getRelatedSkills () { return []; }

	public function __debugInfo()
	{
		return [
			'name' => $this->name,
			'description' => $this->getDescription(),
			'sprite' => $this->sprite,
		];
	}
}

interface SkillInterface
{
	public function getDescription		();
	public function getRelatedSkills	();
	public function onChangeLevel		();
}

trait SkillCapability
{
	public $skills = [];
	public $passives = [];

	public $used_skills = [];

	public function addSkill(SkillInterface $skill)
	{
		if ($skill instanceof Passive) return $this->addPassive($skill);

		console_echo($skill->key);

		if (isset($this->skills[$skill->key])) return false;
		$skill->onRegister($this);
		$this->skills[$skill->key] = $skill;
		return true;
	}

	public function addPassive(Passive $passive)
	{
		if (isset($this->passives[$passive->key])) return false;
		if ($passive->onRegister) $passive->onRegister($this);
		$passive->owner = $this;
		$this->passives[$passive->key] = $passive;

		return true;
	}

	public function upgradeSkill(SkillInterface $skill)
	{
		if ($skill instanceof Passive) return $this->upgradePassive($skill);

		$this->skills[$skill->key]->level += $skill->level;
		$this->skills[$skill->key]->onChangeLevel();

		return true;
	}

	public function upgradePassive(Passive $passive)
	{
		$this->passives[$passive->key]->level += $passive->level;
		$this->passives[$passive->key]->onChangeLevel();

		return true;
	}

	public function getSkillByBinding($SKLS)
	{

	}

	public function useSkill($SKLSorKey, $n_offset = null, $w_offset = null)
	{
		/**************************************************\
		 Is the skill being used via binding or via key?
		\**************************************************/

		if (is_numeric($SKLSorKey))
		{
			/**************************************\
			 Is there a skill bound to this slot?
			\**************************************/

			if (!isset($this->bindings[$SKLSorKey]))
			{
				console_echo("<<#fff>>{$this->name}<> doesn't have a skill in slot <<#fff>>{$SKLSorKey}<>", '#faa');
				return;
			}

			$skill = $this->bindings[$SKLSorKey]->getSubject();
		}
		else
		{
			/**************************************\
			 Is there a skill with the given key?
			\**************************************/

			if (!isset($this->skills[$SKLSorKey]))
			{
				console_echo("<<#fff>>{$this->name}<> doesn't have a skill with key <<#fff>>{$SKLSorKey}<>", '#faa');
				return;
			}

			$skill = $this->skills[$SKLSorKey];
		}

		/**************************************\
		 Can we cast it?
		\**************************************/

		if ($skill->readyTime && $skill->readyTime > $_SERVER['REQUEST_TIME_FLOAT'])
		{
			$timeLeft = round($skill->readyTime - $_SERVER['REQUEST_TIME_FLOAT'] + 0.4, 0, PHP_ROUND_HALF_UP); // Add 0.4 to make it always round up. Does that even work?
			update_thoughts("Can't use {$skill->name} for another {$timeLeft} seconds.");
			return;
		}

		if (isset($skill->range))
		{
			if (!isset($n_offset, $w_offset))
			{
				update_thoughts("Target needed for {$skill->name}!");
				return;
			}

			$n_dist = $this->n_offset - $n_offset;
			if ($n_dist < 0) $n_dist = 0 - $n_dist;

			$w_dist = $this->w_offset - $w_offset;
			if ($w_dist < 0) $w_dist = 0 - $w_dist;

			$distance = $n_dist + $w_dist;

			if ($skill->range < $distance)
			{
				update_thoughts("Can't use {$skill->name} at this distance! ({$skill->range} < {$distance})");
				return;
			}
		}

		if ($skill->hpCost && $this->hp < $skill->hpCost)
		{
			update_thoughts("Not enough health to use {$skill->name}. (" . round($this->hp, 1) . " < {$skill->hpCost})");
			return;
		}

		if ($skill->epCost && $this->ep < $skill->epCost)
		{
			update_thoughts("Not enough energy to use {$skill->name}. (" . round($this->ep, 1) . " < {$skill->epCost})");
			return;
		}

		/**************************************\
		 Yes! Use the skill.
		\**************************************/

		if ($skill->onUse($n_offset, $w_offset))
		{
			/**************************************\
			 Did it work? If so, incur costs.
			\**************************************/

			if ($skill->hpCost) $this->alterHp(0 - $skill->hpCost);
			if ($skill->epCost) $this->alterEp(0 - $skill->epCost);

			if ($skill->cooldown) $skill->readyTime = $_SERVER['REQUEST_TIME_FLOAT'] + $skill->cooldown;

			// Skills with higher cooldowns will have greater weighting per use
			// so that spammy skills don't ALWAYS dominate.
			if (isset($this->used_skills[$skill->key]))
			{
				$this->used_skills[$skill->key] += $skill->cooldown ? $skill->cooldown : 1;
			}
			else
			{
				$this->used_skills[$skill->key] = $skill->cooldown ? $skill->cooldown : 1;
			}
		}
		else
		{
			update_thoughts("{$skill->name} failed!");
		}
	}

	public function executePassives ($TRG, $arg1 = null, $arg2 = null, $arg3 = null)
	{
		foreach ($this->passives as $passive)
		{
			if (!($passive instanceof Passive))
			{
				global $TRG_readable;
				console_echo($this->name, '#faf');

				console_echo($TRG_readable[$TRG], '#fff');
			}

			if ($passive->can($TRG))
			{
				$passive->$TRG($arg1, $arg2, $arg3);
				$passive->triggercooldown();
			}
		}
	}
}

