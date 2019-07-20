<?php

abstract class a_eqp_apparel extends Equipment
{
	public $DMGs_def		= null;

	public $DMGs_def_mod	= 1;
	public $DMGs_def_count	= 1;

	public function __construct($level = null, $name = null, $description = null, $spriteSet = null)//, $EQP, $DSs_req = null, $DSs = null, $DMGs_def = null, $behaviours = null)
	{
		$this->ICATs[] = ICAT_ARMOR;

		parent::__construct($level, $name, $description, $spriteSet);//, $EQP, $DSs_req, $DSs, null, $DMGs_def, $behaviours);
	}

	protected function fillGaps()
	{
		parent::fillGaps();

		if (!isset($this->DMGs_def))	$this->DMGs_def	= $this->generateDMGs_def();
	}

	protected function consolidate($problems = [])
	{
//		$this->problemCheck($problems);

		return parent::consolidate($problems);
	}

	function generateDMGs_def($number = null)
	{
		global $DMGDL_constants;
		global $DMG_constants;

		$base = (10 + ($this->level ^ 1.2)) * $this->DMGs_def_mod;
		if (!isset($number)) $number = $this->DMGs_def_count;

		$DMGs_def = [];

		for ($i = getNuancedValue($number, 50); $i > 0; $i--)
		{
			if (mt_rand(0, 4))
			{
				$DMGs_def[$DMGDL_constants[array_rand($DMGDL_constants)]] = $base;
			}
			else
			{
				$DMGs_def[$DMG_constants[array_rand($DMG_constants)]] = $base;
			}
		}

		return $this->getRequiredStats('DMGs_def') + $DMGs_def;
	}

	function equals(Item $item)
	{
		if (parent::equals($item))
		{
			if ($this->DSs		!== $item->DSs		) return false;
			if ($this->DMGs_def	!== $item->DMGs_def	) return false;
			if ($this->DSs_req	!== $item->DSs_req	) return false;
		}
		else
		{
			return false;
		}
		return true;
	}

	protected function applyQuirks()
	{
		global $DS_names;

		switch ($this->DS_base)
		{
			case DS_STRENGTH:
				if (percentageToBool(60))
				{
					$this->DSs[DS_INTELLECT] = getNuancedValue(-5 - ($this->level ^ 1.2), 13);
				}

				if (percentageToBool(40))
				{
					$this->DSs[DS_HP_MAX] = mt_rand($this->level * 5, ($this->level + 2) * 5);
				}
				break;
			case DS_AGILITY:

				break;

			case DS_MAGIC:
				if (percentageToBool(50))
				{
					// Penalise strength equal to a fraction of the magic requirement.
					$this->DSs[DS_STRENGTH] = getNuancedValue($this->DSs_req[DS_MAGIC], 10) / 5;
				}

				if (percentageToBool(80))
				{
					if (percentageToBool(70))
					{
						// Bonus to energy
						if (!isset($this->DSs[DS_EP_MAX])) $this->DSs[DS_EP_MAX] = getNuancedValue(10 + ($this->level * 1.5), 10);
					}
					else
					{
						// Percentage bonus to energy recharge
						if (!isset($this->DSs[DS_RECHARGE])) $this->DSs[DS_RECHARGE] = mt_rand(5, 20) . '%';
					}
				}
				break;
			case DS_CHARISMA:

				break;
			case DS_INTELLECT:

				break;
		}

		if (percentageToBool(30)) // Take a stat, tripple it, make it an onTakeHit bonus.
		{
			global $DS_typed;
			$DS = array_rand($this->DSs);

			if ($this->DSs[$DS] > 0 && in_array($DS, $DS_typed))
			{
				$value = $this->DSs[$DS] * 3;

				unset($this->DSs[$DS]);

				$duration = mt_rand(10, 25);

				$chance = mt_rand(10, 30);

				$this->addBehaviour(new ebhv_chanceToAlterOwnStat(TRG_TAKE_HIT, $DS, $value, $chance, $duration));
			}
		}
	}
}