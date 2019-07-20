<?php

class ibhv_healingItem extends ItemBehaviour
{
	public $amount;
	public $duration;

	public $status;

	function __construct($amount, $duration)
	{
		$this->onRegister = true;
		$this->onUse = true;

		$this->goldValue = ($amount / $duration) * 0.2;

		$this->ICATs[] = ICAT_CONSUMABLE;
		$this->ICATs[] = ICAT_REMEDY;

		$description = "Heals {$amount} hp in {$duration} seconds";

		$this->amount = $amount;
		$this->duration = $duration;

		parent::__construct($description, 'heal', 5);
	}

	function onRegister()
	{
		// This needs to be done on register because it's accessing owner properties.

		$this->status = new Status(
			$this->owner->name,
			$this->owner->description,
			$this->owner->sprite,
			$this->duration, false,
			[DS_REGENERATION => round($this->amount / $this->duration, 1)]
		);
	}

	function onUse()
	{
		$this->owner->owner->addStatus($this->status);

		if ($this->owner->quantity > 1)
		{
			$this->owner->delete(1);
		}
		else
		{
			$this->owner->delete();
		}
	}
}