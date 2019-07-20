<?php

class obj_missile extends obj_projectile
{
	public function __construct($name, $spriteSet, $spawner,
		$DIR, $TEQT, $range = null,
		$DMGDL_impact = null, $DMGs_impact = null,
		$DMGDL_OT = null, $DMGs_OT = null, $duration_OT = null,
		$status = null)
	{
		parent::__construct($name, $spriteSet, $spawner, $DIR, $range);

		if ($DMGs_impact)
		{
			$DMGDL_impact = $DMGDL_impact ? $DMGDL_impact : DMGDL_MISSILE;
			$attack = new Attack($spawner, $DMGDL_impact, $DMGs_impact, $TEQT);
			$attack->alwaysHit = true;
		}
		else $attack = null;

		if ($DMGs_OT)
		{
			$DMGDL_OT = $DMGDL_OT ? $DMGDL_OT : DMGDL_MISSILE;
			$duration_OT = $duration_OT ? $duration_OT : 5;
			$dbhv_takeDamageOverTime = new dbhv_takeDamageOverTime($spawner, $DMGDL_OT, $DMGs_OT, $duration_OT);
		}
		else $dbhv_takeDamageOverTime = null;



		$this->addBehaviour(new obhv_missileCollision($spawner, $attack, $dbhv_takeDamageOverTime, $status));
	}
}