<?php

abstract class a_skl_effectPattern extends Skill
{
	public $pattern			= [];
	public $freePlacement	= false;	// Free placement effect happens at cursor location
	public $directional		= false;	// Direction effect is rotated according to which side of the player it happens on.

//	public $attack;

	public $DMGs;
	public $DMGDL;
	public $TEQT;

	public $statuses		= [];

	public $effectSprite;


	public function onUse($n_offset, $w_offset)
	{
		if (($this->directional || $this->freePlacement) && !isset($n_offset, $w_offset)) return false;

		if ($this->directional)
		{
			$n = $n_offset - $this->owner->n_offset;
			$w = $w_offset - $this->owner->w_offset;

			$nCompare = $n > 0 ? $n : 0 - $n;
			$wCompare = $w > 0 ? $w : 0 - $w;

			if ($nCompare > $wCompare)	$rotation = $n < 0 ? 2 : 0;
			else						$rotation = $w < 0 ? 1 : 3;

			if (!$this->freePlacement)
			{
				$n_offset = $this->owner->n_offset;
				$w_offset = $this->owner->w_offset;
			}

			$pattern = rotatePatternArrayCW($this->pattern, $rotation);
		}
		else
		{
			$pattern = $this->pattern;
		}

//		$behaviour = new obhv_effectPattern($n_offset, $w_offset, $pattern, $this->effectSprite, $this->attack, $this->statuses);
		$behaviour = new obhv_effectPattern($n_offset, $w_offset, $pattern, $this->effectSprite, $this->DMGs, $this->DMGDL, $this->TEQT, $this->statuses);

		$this->owner->addBehaviour($behaviour);

		return true;
	}

	public function onRegister(Dude $owner)
	{
		parent::onRegister($owner);
	}
}