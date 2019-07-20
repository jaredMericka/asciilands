<?php

class ebhv_chanceToGrantStatus extends EquipmentBehaviour
{
	public $status;
	public $chance;

	function __construct($TRG, $status, $chance, $cooldown)
	{
		global $TRG_readable;

		$this->$TRG = true;

		$this->status = $status;
		$this->chance = $chance;

		$this->goldValue = $this->status->getGoldValue() * ($this->chance / 100);

		$description = "{$chance}% chance to grant \"{$this->status->name}\" {$TRG_readable[$TRG]} every {$cooldown} seconds.";

		parent::__construct($description, null, $cooldown);
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