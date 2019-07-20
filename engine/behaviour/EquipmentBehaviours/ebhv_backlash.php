<?php

class ebhv_backlash extends EquipmentBehaviour
{
	public $DS;
	public $DSname;
	public $percentChance;

	function __construct($DS, $percentChance)
	{
		global $DS_names;

		$this->onTakeHit = true;

		$this->DS = $DS;
		$this->DSname = $DS_names[$DS];
		$this->percentChance = $percentChance;

		$description = "{$percentChance}% chance to use your higher {$DS_names[$DS]} to fuel a counter-attack.";

		$this->goldValue = $percentChance / 10;

		parent::__construct($description, 'backlash');
	}

	function onTakeHit(Attack $attack)
	{
		console_echo('Are we going to backlash?');
		if (!percentageToBool($this->percentChance))
		{
			console_echo('No backlash');
			return;
		}

		console_echo('Running backlash');

		// "attacker" and "target" describe the roles within backlash (as opposed to the roles within the current attack).
		$attackerDS = $attack->target->$this->DSname;
		$targetDS = $attack->attacker->$this->DSname;

		$returnedDMGpercentage = 1 - ($targetDS / $attackerDS);

		if ($returnedDMGpercentage > 0)
		{
			$returnedDMGs = [];

			foreach ($attack->DMGs as $DMG => $value)
			{
				$returnedDMGs[$DMG] = $value * $returnedDMGpercentage;
			}

			$backlashAttack = new Attack($this->owner->owner, $attack->DMGDL, $returnedDMGs, $attack->TEQT);
			$backlashAttack->alwaysHit = true;

			console_echo("{$attack->target->name} is using backlash on {$attack->attacker->name} using their superior {$this->DSnames} to hit for ". array_sum($returnedDMGs) . ' damage.', '#afa');

			update_combat("<<#fff>>{$backlashAttack->attacker->name}<> used <<#00f>backlash<> on <<#fff>>{$attack->attacker}<>!");
			$backlashAttack->execute($attack->attacker);
		}
		else
		{
			console_echo("Disparity is $returnedDMGpercentage. Aborting backlash.", '#fda');
		}
	}
}