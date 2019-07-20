<?php

class nvitm_rawSteak extends Item
{
	public function __construct($quantity = 1)
	{
		$name = 'Raw Steak';
		$description = 'I should cook this thing.';

		$sprite = new Sprite([
			0 => new SpriteElement(null, '#f55', '&#x2584;'),
			1 => new SpriteElement('#f55', '#fff', '&#x252c;'),
			2 => new SpriteElement('#f55', null, ' '),
			3 => new SpriteElement(null, '#f55', '&#x2580;'),
			4 => new SpriteElement('#f55', '#fff', '|'),
			]);

		$behaviours = null;

		parent::__construct($name, $description, [$sprite], $behaviours);

		$this->quantity = $quantity;

		$this->goldValue = 0.02;
	}

	public function onTransform($TFI)
	{
		$newItem = $this;
		switch ($TFI)
		{
			case TFI_FIRE:		$newItem = new nvitm_cookedSteak(); break;
			case TFI_FURNACE:	$newItem = new nvitm_burntSteak(); break;
			case TFI_CRUSHER:	$newItem = new nvitm_minceMeat(); break;
		}
		$newItem->quantity = $this->quantity;
		return $newItem;
	}
}