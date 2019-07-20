<?php

class obj_corpse extends AsObject
{
	public $inventory;

	public function __construct($name, $spriteSet, $items = [])
	{
		$this->inventory = new Inventory($this);

		foreach ($items as $item) { $this->inventory->add($item, true); }
		$this->inventory->locked = true; // Chest has to be locked AFTER items are added.

		$this->addBehaviour(
			new obhv_lootable()
		);

		$this->permitEntryDefault = true;


		parent::__construct($name, $spriteSet, LAYER_COLLECTIBLE);
	}
}