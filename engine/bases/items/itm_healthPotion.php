<?php

class itm_healthPotion extends Item
{
	function __construct($name, Sprite $sprite, $amount, $duration, $description = null)
	{
		$this->addBehaviour(
			new ibhv_healingItem($amount, $duration)
		);

		$this->INV = INV_POTIONS;

		$description = $description ? $description : 'Doesn\'t taste great but it works.';

		parent::__construct($name, $description, $sprite);
	}


}