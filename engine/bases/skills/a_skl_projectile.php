<?php

abstract class a_skl_projectile extends Skill
{
	public $epCost	= 10;

	public $range	= 20;

	public $DMGDL	= DMGDL_MISSILE;
	public $DMGs	= null;
	public $TEQT	= TEQT_RANGED;

	// Over time
	public $DMGDL_OT	= DMGDL_BLUNT;
	public $DMGs_OT		= null;
	public $duration_OT	= 5;

	public $status	= null;

	public $spriteSet;

	public function onUse($n_offset, $w_offset)
	{
		if (!isset($n_offset, $w_offset)) return false;

		global $map;
		global $view;

		$n = $n_offset - $this->owner->n_offset;
		$w = $w_offset - $this->owner->w_offset;

		$nCompare = $n > 0 ? $n : 0 - $n;
		$wCompare = $w > 0 ? $w : 0 - $w;

		if ($nCompare > $wCompare)	$DIR = $n < 0 ? DIR_NORTH : DIR_SOUTH;
		else						$DIR = $w < 0 ? DIR_WEST : DIR_EAST;

		$this->spriteSet[$DIR] = $view->addClientSprite($this->spriteSet[$DIR]);

		$sprite = $this->spriteSet[$DIR];

		$obj_projectile = new obj_missile(
			'Projectile', [$sprite], $this->owner, $DIR, $this->TEQT, $this->range,

			$this->DMGDL,		$this->DMGs,
			$this->DMGDL_OT,	$this->DMGs_OT,		$this->duration_OT,
			$this->status);

		$obj_projectile->n_offset = $this->owner->n_offset;
		$obj_projectile->w_offset = $this->owner->w_offset;

		$map->addObjects($obj_projectile);

		return true;
	}
}
