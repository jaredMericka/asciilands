<?php

abstract class Behaviour
{
	public $owner;
	public $description;

	public $key;
	public $keySuffix;

	public $cooldown;
	public $readyTime;

	public $expiaryTime = null;

	public $spriteSet = [];

	public $onRegister	= null;
	public $onRemove	= null;

	public function __construct($description, $key, $cooldown = 0, $expiary = 0)
	{
		$this->description = $description;
		$this->key = isset($key) ? $key : get_class($this);
		$this->key .= (isset($this->keySuffix) ? "_{$this->keySuffix}" : '');

		$this->cooldown = $cooldown;
		if ($cooldown < 0.00001) $cooldown = 0.00001;
		$this->readyTime = 0;

		if ($expiary) $this->expireInSeconds($expiary);
	}

	public function triggercooldown()
	{
		if ($this->readyTime > $_SERVER['REQUEST_TIME_FLOAT']) return;
		$this->readyTime = $_SERVER['REQUEST_TIME_FLOAT'] + $this->cooldown;
		$this->readyTime -= READY_TIME_CORRECTION;
	}

	public function can($TRG)
	{
		// Delete the behaviour if it has expired.
		if ($this->expiaryTime && $this->expiaryTime < $_SERVER['REQUEST_TIME_FLOAT']) $this->delete();

		// Allow the behaviour to be triggered if it is switched on and ready.
		return isset($this->$TRG) && $this->$TRG && $this->readyTime <= $_SERVER['REQUEST_TIME_FLOAT'];
	}

	public function extendcooldown($seconds)
	{
		$this->readyTime += $seconds;
	}

	public function expireInSeconds($seconds)
	{
		$this->expiaryTime = $_SERVER['REQUEST_TIME_FLOAT'] + $seconds;
	}

	public function delete()
	{
		$this->owner->removeBehaviour($this);
	}

	public function equals(Behaviour $behaviour)
	{
		return get_class($this)		=== get_class($behaviour)
			&& $this->description	=== $behaviour->description
			&& $this->key			=== $behaviour->key
			&& $this->spriteSet		=== $behaviour->spriteSet
			;
	}

	public function onRegister() { }
	public function onRemove() { }

	public function __debugInfo ()
	{
		return [
			'owner' => $this->owner ? $this->owner->name : '### NO OWNER ###',
			'description' => $this->description,
			'key' => $this->key,
			'keySuffix' => $this->keySuffix,
			'cooldown' => $this->cooldown,
			'readyTime' => $this->readyTime,
			'expiaryTime' => $this->expiaryTime,
		];
	}
}

// Anything that has behaviours needs this trait.
trait BehaviourCapability
{
	public $behaviours = [];

	function addBehaviour(Behaviour $behaviour)
	{
		if (isset($this->behaviours[$behaviour->key])) console_class_list($this->behaviours[$behaviour->key], '#f00');

		$newBehaviours = func_get_args();

		foreach ($newBehaviours as $behaviour)
		{
			$behaviour->owner = $this;

			if (isset($this->behaviours[$behaviour->key]))
			{
				console_class_list($this->behaviours[$behaviour->key], '#fff');
				$this->behaviours[$behaviour->key][] = $behaviour;
				console_class_list($this->behaviours[$behaviour->key]);
			}
			else
			{
				$this->behaviours[$behaviour->key] = [$behaviour];
			}
			console_echo("Behaviour with key \"{$behaviour->key}\" has been registered.");

			if ($this->finished && $behaviour->onRegister) $behaviour->onRegister();
		}
	}

	function addBehaviourExclusive(Behaviour $behaviour)
	{
		$newBehaviours = func_get_args();

		foreach ($newBehaviours as $behaviour)
		{
			$behaviour->owner = $this;

			foreach ($this->behaviours[$behaviour->key] as $bhv)
			{
				$bhv->delete();
			}

			$this->behaviours[$behaviour->key] = [$behaviour];

			console_echo("Behaviour with key \"{$behaviour->key}\" has been registered EXCLUSIVELY.");

			if ($this->finished && $behaviour->onRegister) $behaviour->onRegister();
		}
	}

	function executeBehaviours($TRG, $arg1 = null, $arg2 = null, $arg3 = null)
	{
		foreach ($this->behaviours as $behaviour)
		{
			$behaviour = end($behaviour);

			if (!($behaviour instanceof Behaviour))
			{
				global $TRG_readable;
				console_echo($this->name, '#faf');

				console_echo($TRG_readable[$TRG], '#fff');

				foreach ($this->behaviours as $name => $behaviour)
				{
					$behaviour = end($behaviour);
					console_echo($name . ' => ' . get_class($behaviour), '#fff');
				}
			}

			if ($behaviour->can($TRG))
			{
				$behaviour->$TRG($arg1, $arg2, $arg3);
				$behaviour->triggercooldown();
			}
		}

		if ($this instanceof Player) $this->executePassives($TRG, $arg1, $arg2, $arg3);

		if (isset($this->NPCIs))
		{
			console_echo('This thing has NPCIs');
			$this->executeNPCIbehaviours($TRG, $arg1, $arg2, $arg3);
		}
	}

	function hasBehaviour($BHVK)
	{
		return isset($this->behaviours[$BHVK]);
	}

	function removeBehaviour(Behaviour $behaviour)
	{
		$BHVK = $behaviour->key;

		if (!isset($this->behaviours[$BHVK]))
		{
			console_echo("Attempting to remove behaviour with key <<#fff>>\"{$BHVK}\"<> from <<#ffa>>{$this->name}<> but no behaviour was found there.", '#fda', CNS_BEHAVIOUR);
			return;
		}

		foreach ($this->behaviours[$BHVK] as $pos => $bhv)
		{
			if ($behaviour->equals($bhv))
			{
				if ($this->behaviours[$BHVK][$pos]->onRemove)
				{
					console_echo('========== ON REMOVE ===========', '#afa');
					$this->behaviours[$BHVK][$pos]->onRemove();
				}
				else
				{
					console_echo('========== NO ON REMOVE ===========', '#faa');
				}

				unset($this->behaviours[$BHVK][$pos]);
				if (empty($this->behaviours[$BHVK])) unset($this->behaviours[$BHVK]);
				break;
			}
		}
	}

	function behaviour__clone()
	{
		$clonedBehaviours = [];
		foreach ($this->behaviours as $behaviours)
		{
			foreach ($behaviours as $behaviour)
			{
				$clonedBehaviours[] = clone $behaviour;
			}
		}

		$this->behaviours = [];

		foreach ($clonedBehaviours as $behaviour)
		{
			$this->addBehaviour($behaviour);
		}
	}

	function registerBehaviour()
	{
		foreach ($this->behaviours as $behavoiurs)
		{
			foreach ($behavoiurs as $behaviour)
			{
				if (!($behaviour instanceof Behaviour))
				{
					console_var_dump($behaviour, '#faa');
				}

				$behaviour->owner = $this;
				$behaviour->onRegister();
			}
		}
	}
}