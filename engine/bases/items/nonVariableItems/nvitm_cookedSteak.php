<?php

class nvitm_cookedSteak extends Item
{
	public function __construct($quantity = 1)
	{
		$name = 'Cooked Steak';
		$description = 'Juicy and delicious.';

		$sprite = new Sprite([
			0 => new SpriteElement(null, '#964', '&#x2584;'),
			1 => new SpriteElement('#964', '#ea7', '&#x252c;'),
			2 => new SpriteElement('#964', null, ' '),
			3 => new SpriteElement(null, '#964', '&#x2580;'),
			4 => new SpriteElement('#964', '#ea7', '|'),
			]);

		$behaviours = null;

		parent::__construct($name, $description, [$sprite], $behaviours);

		$this->quantity = $quantity;

		$this->goldValue = 0.01;
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