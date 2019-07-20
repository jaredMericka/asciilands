<?php

class skl_electricalStorm extends a_skl_effectPattern
{
	public $duration;
	public $coverage;

	public function __construct($level = 1)
	{
		global $DMG_icons;
		global $DMG_effects;

		$this->level = $level;

		$this->effectSprite = $DMG_effects[DMG_ELECTRIC];
		$this->freePlacement = true;
		$this->directional = false;

		$this->TEQT = TEQT_MAGIC;
		$this->DMGDL = DMGDL_PLASMA;

		parent::__construct('Electrical Storm', $DMG_icons[DMG_ELECTRIC]);
	}

	public function getDescription()
	{
		return "Strikes an area with lightning bolts causing {$this->DMGs[DMG_ELECTRIC]} electrical (as plasma) damage per strike.";
	}

	public function onChangeLevel()
	{
		$damage = 6 + ($this->level * 4);
		$this->duration = 4 + round($this->level / 2);
		$this->coverage = min(3 + round($this->level / 3), 10);

		$this->DMGs = [DMG_ELECTRIC => $damage];
	}

	public function onUse($n_offset, $w_offset)
	{
		$this->pattern = [[[0,0]]];
		$coverage = 1;

		for ($i = 1; $i <= $this->duration; $i++)
		{
			$frame = [];
			$range = min($coverage, $this->coverage);

			for ($j = 0; $j <= 2 * $i; $j++)
			{
				$frame[] = [mt_rand(0 - $range, $range), mt_rand(0 - $range, $range)];
			}

			$coverage++;
			$this->pattern[] = $frame;
		}

		return parent::onUse($n_offset, $w_offset);
	}

	public function getRelatedSkills()
	{
		return [];
	}
}