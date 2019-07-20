<?php

class nvitm_burntSteak extends Item
{
	public function __construct($quantity = 1)
	{
		$name = 'Burnt Steak';
		$description = 'Burnt beyond edibility.';

		$sprite = new Sprite([
			0 => new SpriteElement(null, '#543', '&#x2584;'),
			1 => new SpriteElement('#543', '#999', '&#x252c;'),
			2 => new SpriteElement('#543', null, ' '),
			3 => new SpriteElement(null, '#543', '&#x2580;'),
			4 => new SpriteElement('#543', '#999', '|'),
			]);

		$behaviours = null;

		parent::__construct($name, $description, [$sprite], $behaviours);

		$this->quantity = $quantity;
	}

	public function onTransform($TFI)
	{
		$newItem = $this;
		switch ($TFI)
		{
			case TFI_FIRE:		$newItem = new nvitm_burntSteak(); break;
			case TFI_FURNACE:	$newItem = new nvitm_burntSteak(); break;
		}

		$newItem->quantity = $this->quantity;
		return $newItem;
	}
}