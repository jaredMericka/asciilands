<?php

class obhv_lootable extends ObjectBehaviour
{
	public $keyMask;

	public function __construct($keyItem = null, $spriteSet = null)
	{
		$this->onEngage	= true;
		$this->onDisengage	= true;
		$this->onRegister = true;

		if ($keyItem) $this->keyMask = new Mask($keyItem, ['name', 'description', 'class']);

		if (isset($spriteSet) && isset($spriteSet[SPRI_OPEN]))
		{
			$this->spriteSet[SPRI_OPEN] = $spriteSet[SPRI_OPEN];
			$this->spriteSet[SPRI_CLOSED] = $spriteSet[SPRI_CLOSED];
		}

		$description = 'Allows access to inventory while engaged.';

		parent::__construct($description, BHVK_PRIMARY);
	}

	public function onRegister()
	{
		$this->owner->inventory->lootable = true;
	}

	public function onEngage(Player $player)
	{
		if (isset($this->keyMask) && $player->inventory->hasItem($this->keyMask) === false && $this->owner->inventory->hasItem($this->keyMask) === false)
		{
			update_thoughts("I'll need a {$this->keyMask->name} to open this {$this->owner->name}.");
			return;
		}

		$this->owner->inventory->locked = false;

		update_available();

		if (isset($this->spriteSet[SPRI_OPEN]))
		{
			$this->owner->sprite = $this->spriteSet[SPRI_OPEN];
		}
	}

	public function onDisengage(Player $player)
	{
		$this->owner->inventory->locked = true;
		$player->showItemPrices = false;

		clearPanel(UPD_AVAILABLE);
//		if (isset($this->owner->inventory->CUR))
//		{
//			update_items();
//			$player->inventory->CUR = null;
//		}

		if (isset($this->spriteSet[SPRI_CLOSED]))
		{
			$this->owner->sprite = $this->owner->spriteSet[SPRI_CLOSED];
		}
	}
}