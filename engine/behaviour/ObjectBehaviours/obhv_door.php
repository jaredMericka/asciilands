<?php

class obhv_door extends ObjectBehaviour
{
	public $isOpen;
	public $keyMask;

	public function __construct(Sprite $openSprite = null, Item $keyItem = null)
	{
		$this->onReaction = true;
		if ($keyItem) $this->keyMask = new Mask($keyItem, ['name', 'description', 'class']);
		$this->spriteSet[SPRI_OPEN] = $openSprite;
		$description = 'Opens like a door.';
		parent::__construct($description, BHVK_PRIMARY);
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		if ($this->isOpen)
		{
			$this->owner->permitEntry = true;
			return;
		}
		if (!($instigator instanceof Dude && $instigator->canOpenDoors)) return;

		// Door opening dudes only beyond this point.
		if ($this->keyMask && $instigator->inventory->hasItem($this->keyMask) === false)
		{
			update_thoughts("I'll need a {$this->keyMask->name} to get through this {$this->owner->name}.");
			return;
		}

		if (isset($this->spriteSet[SPRI_OPEN]))
		{
			$this->owner->setSPRI(SPRI_OPEN);// = $this->spriteSet[SPRI_OPEN];
			$this->isOpen = true;
			$this->owner->changeLayer(LAYER_DOOR_OPEN);
		}
		else
		{
			// We have no way of showing that this door is open so we'll just
			// have to get rid of it.
			$this->owner->destroy();
		}
	}
}