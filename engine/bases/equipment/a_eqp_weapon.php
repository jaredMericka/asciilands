<?php

abstract class a_eqp_weapon extends Equipment
{
	public $DMGDL			= null; // This should just be one DMGDL constant.
	public $DMGs			= null; // This should be an array.

	public $DMGs_mod		= 1;
	public $DMGs_count		= 1;

	public $cooldown		= 1;

	public function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->ICATs[] = ICAT_WEAPON;
		$this->EQP = EQP_HAND;

		parent::__construct($level, $name, $description, $spriteSet);
	}

	protected function fillGaps()
	{
		parent::fillGaps();

//		if (!isset($this->DMGs)) $this->generateDMGs();
		if (!$this->DMGs) $this->generateDMGs();

		if (!isset($this->spriteSet[SPRI_OVERSPRITE]))
		{
			if ($slm = $this->spriteSet[SPRI_DEFAULT]->frames[0][1])
			{
				$this->spriteSet[SPRI_OVERSPRITE] = new Sprite([0 => $slm]);
			}
			else
			{
				$this->spriteSet[SPRI_OVERSPRITE] =
					new Sprite(
						[
							[0 => new SpriteElement(null, '#0f0', '#')],
							[0 => new SpriteElement(null, '#f0f', '#')]
						]
					);
			}
		}
	}

	protected function consolidate($problems = [])
	{
		if (!isset($this->DMGDL)) $problems[] = 'damage delivery method missing';

		$this->problemCheck($problems);

		parent::consolidate($problems);
	}

	public function generateDMGs($number = null)
	{
		global $DMG_constants;

		$base = (5 + ($this->level ^ 1.2)) * $this->DMGs_mod;
		if (!isset($number)) $number = $this->DMGs_count;

		$this->DMGs[DMG_TRAUMA] = $base;

		for ($i = 1; $i < $number; $i ++)
		{
			$DMG = array_rand($DMG_constants);
			if (isset($this->DMGs[$DMG])) continue;
			$this->DMGs[$DMG] = $base;
		}

		$this->DMGs = $this->getRequiredStats('DMGs') + $this->DMGs;
	}

	protected function applyQuirks()
	{
		if (isset($this->DMGs[DMG_POISON]))
		{
			$damage = getNuancedValue($this->DMGs[DMG_POISON] * 6, 20);
			$duration = mt_rand(5, 8);

//			$this->addBehaviour(new ebhv_dealDamagePerSecond(
//				$this->DMGDL,
//				[DMG_POISON => $damage], $duration, 0));

			$this->addBehaviour(new ebhv_dmg_poison($damage, $duration));

			unset($this->DMGs[DMG_POISON]);
		}

//		if (percentageToBool(30))
		if (true)
		{
			$DMG = array_search(max($this->DMGs), $this->DMGs);

			$damage = getNuancedValue($this->DMGs[$DMG] * 3, 10);
			$duration = mt_rand(3, 5);

			switch ($DMG)
			{
				case DMG_TRAUMA:	$behaviour = new ebhv_dmg_trauma($damage, $duration); break;
				case DMG_FIRE:		$behaviour = new ebhv_dmg_fire($damage, $duration); break;
				case DMG_COLD:		$behaviour = new ebhv_dmg_cold($damage, $duration); break;
				case DMG_ELECTRIC:	$behaviour = new ebhv_dmg_electric($damage, $duration); break;
				case DMG_WATER:		$behaviour = new ebhv_dmg_water($damage, $duration); break;
//				case DMG_POISON:	$behaviour = new ebhv_dmg_poison($damage, $duration); break;
				case DMG_INFECTION:	$behaviour = new ebhv_dmg_infection($damage, $duration); break;
			}

			if ($behaviour instanceof EquipmentBehaviour) $this->addBehaviour($behaviour);
		}
	}

	function equals(Item $item)
	{
		if (parent::equals($item))
		{
			if ($this->DSs		!== $item->DSs		) return false;
			if ($this->DMGs		!== $item->DMGs		) return false;
			if ($this->DSs_req	!== $item->DSs_req	) return false;
			if ($this->DMGDL	!== $item->DMGDL	) return false;
		}
		else
		{
			return false;
		}
		return true;
	}
}