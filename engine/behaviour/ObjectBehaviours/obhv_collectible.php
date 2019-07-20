<?php

class obhv_collectible extends ObjectBehaviour
{
	public $item;

	public function __construct(Item $item)
	{
		$name	= "Collect {$item->name}.";
		$this->item = $item;

		$this->onReaction = true;

		parent::__construct($name, BHVK_PRIMARY);
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		if ($instigator instanceof Dude)
		{
			$instigator->inventory->add($this->item);
			$this->owner->destroy();
		}
		$this->owner->permitEntry = true;
//		parent::onReaction($instigator);
	}
}