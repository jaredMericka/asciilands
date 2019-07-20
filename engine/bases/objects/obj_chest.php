<?php

class obj_chest extends AsObject
{
	public $inventory;

	public function __construct($name, $spriteSet, $items = [], $keyItem = null)
	{
		$this->inventory = new Inventory($this);

		if ($items) foreach ($items as $item) { $this->inventory->add($item); }	// TERRIBLE
		$this->inventory->locked = true; // Chest has to be locked AFTER items are added.

		$this->addBehaviour(
			new obhv_lootable($keyItem, $spriteSet)
		);

		parent::__construct($name, $spriteSet, LAYER_CHEST);
	}
}