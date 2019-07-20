<?php

class dbhv_alterAttackerHealthOnDefend extends DudeBehaviour
{
	public $alteration;
	public $chance;

	function __construct($alteration, $TRG, $chance = 100)
	{
		 $this->alteration = $alteration;
		 $this->$TRG = true;
		 $this->chance = $chance;

		$description = "Alters attacker's HP by {$alteration}";

		parent::__construct($description, null);
	}

	function onAttack	(Attack $attack)
	{
		if (percentageToBool($this->chance))
		{
			$this->apply($attack);
		}
	}

	function onMiss		(Attack $attack) { if (percentageToBool($this->chance)) $this->apply($attack); }
	function onStrike	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply($attack); }
	function onKill		(Attack $attack) { if (percentageToBool($this->chance)) $this->apply($attack); }
	function onDefend	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply($attack); }
	function onDeflect	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply($attack); }
	function onTakeHit	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply($attack); }
	function onDeath	(Attack $attack) { if (percentageToBool($this->chance)) $this->apply($attack); }

	function apply(Attack $attack)
	{
		if (!$attack->isBaseAttack) return;


		$attack->attacker ->alterHP($this->alteration);


		console_echo("Things Have Happened.");
	}
}
