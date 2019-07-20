<?php

class ebhv_chanceToAmpDamage extends EquipmentBehaviour
{
	public $amp;
	public $chance;

	public $DMG;

	public function __construct($percentageAmp, $percentageChance, $DMG = null)
	{
		$this->onStrike = true;

		$this->amp		= max($percentageAmp, 10);
		$this->chance	= max($percentageChance, 10);

		$this->DMG		= $DMG;

		if (isset($DMG))
		{
			global $DMG_names;

			$descriptionAttack = $DMG_names[$DMG];
		}
		else
		{
			$descriptionAttack = 'attack';
		}

		$this->goldValue = ($percentageAmp * $percentageChance * ($DMG === null ? 2 : 1)) * 0.02;

		$description = "{$percentageChance}% chance to increase {$descriptionAttack} damage by {$percentageAmp}%";

		parent::__construct($description, 'chanceToAmp' . $DMG);
	}

	public function onStrike(Attack $attack)
	{
		if (($attack->isBaseAttack || isset($this->DMG) ) && percentageToBool($this->chance))
		{
			if (isset($this->DMG))
			{
				if (isset($attack->DMGmodifiers[$this->DMG]))
				{
					$attack->DMGmodifiers[$this->DMG] += ($this->amp * 0.01);
				}
				else
				{
					$attack->DMGmodifiers[$this->DMG] = 1 + ($this->amp * 0.01);
				}
			}
			else
			{
				$attack->damageModifier += ($this->amp * 0.01);
			}

			$DMGname = '';
			if ($this->DMG)
			{
				global $DMG_names;
				$DMGname = "{$DMG_names[$this->DMG]} ";
			}

			update_combat("<<#fff>>{$attack->attacker->name}'s<> {$DMGname}damage is <<#aaf>>amped by {$this->amp}%<>!");

			console_echo("Amping damage! Damage modifier = {$attack->damageModifier}",'#ffa');
		}
		else	//XXX
		{		//XXX
			console_echo('Not amping damage.', '#ffa');
		}		//XXX
	}
}