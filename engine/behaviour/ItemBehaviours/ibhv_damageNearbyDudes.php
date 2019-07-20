<?php

class ibhv_damageNearbyDudes extends ItemBehaviour
{
	public $radius;

	public $DMGDL;
	public $DMGs;

	function __construct($DMGDL, $DMGs, $radius)
	{
		$this->onUse = true;

		$this->DMGDL = $DMGDL;
		$this->DMGs = $DMGs;
		$this->radius = $radius;

		$this->goldValue = (array_sum($DMGs) * $radius) * 0.2;

		parent::__construct('Damages nearby dudes over time', null, 5);
	}

	function onUse()
	{
		global $map;

		$objectsInRange = $map->getObjectsInArea(
			$this->owner->owner->n_offset - $this->radius,
			$this->owner->owner->w_offset - $this->radius,
			$this->owner->owner->n_offset + $this->radius,
			$this->owner->owner->w_offset + $this->radius
			);

		console_echo('Objects in range: ' . count($objectsInRange), '#f0f');

		foreach ($objectsInRange as $object)
		{
			console_echo($object->name);

			if ($object instanceof Dude)
			{
				$object->addBehaviour(new dbhv_takeDamageOverTime($this->owner->owner, $this->DMGDL, $this->DMGs, 5));
			}
		}
	}
}