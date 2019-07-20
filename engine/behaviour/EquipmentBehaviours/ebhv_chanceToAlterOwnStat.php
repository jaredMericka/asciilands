<?php

class ebhv_chanceToAlterOwnStat extends EquipmentBehaviour
{
	public $status;
	public $chance;

	function __construct($TRG, $DS, $amount, $chance, $duration)
	{
		global $TRG_readable;
		global $DS_names;

		$this->$TRG = true;
		$this->chance = $chance;

		if ($amount > 0)
		{
			$whatDo = 'increase';
			$sprite = spr_DS_boost($DS);
		}
		else
		{
			$whatDo = 'decrease';
			$sprite = spr_DS_lower($DS);
		}

		$name = ucwords("{$whatDo} {$DS_names[$DS]}");


		$s_description = ucfirst("{$whatDo}s {$DS_names[$DS]} by {$amount}.");
		$this->status = new Status($name, $s_description, $sprite, $duration, false, [$DS => $amount]);

		$this->goldValue = $this->status->getGoldValue() * ($this->chance / 100);

		$description = "{$chance}% chance to {$whatDo} {$DS_names[$DS]} by {$amount} for {$duration} secs {$TRG_readable[$TRG]}.";

		parent::__construct($description, null, 0);
	}

	function onAttack	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onMiss		(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onStrike	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onKill		(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }

	function onDefend	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onDeflect	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onTakeHit	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }
	function onDeath	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply(); }

	function apply()
	{
		$this->owner->owner->addStatus(clone $this->status);
	}
}