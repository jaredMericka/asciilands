<?php

class ebhv_illuminate extends EquipmentBehaviour
{
	public $lightSource;

	public function __construct($distance, $colour = null, $opacity = 0, $absoluteOpacity = true)
	{
		$this->onEquip = true;
		$this->onUnequip = true;
		$this->onMapChange = true;

		$this->goldValue = ((1 - $opacity) + ($absoluteOpacity ? 2 : 0) * $distance);

		$this->lightSource = new lightSource($distance, $colour, $opacity, $absoluteOpacity);

		$description = "Extends view by {$distance} paces.";

		parent::__construct($description, BHVK_OVERLAY);
	}

	public function onEquip()
	{
		//console_echo('Equipping torch.');		//XXX
		if (!$GLOBALS['map']->isDark) return;
		global $view;

		$view->forceUpdate = true;
		$view->addLightSource($this->lightSource);
	}

	public function onUnequip()
	{
		//console_echo('Unequipping torch.');		//XXX
		if (!$GLOBALS['map']->isDark) return;

		global $view;

		$view->forceUpdate = true;
		$view->removeLightSource($this->lightSource);
	}

	public function onMapChange()
	{
		console_echo('Applying torch to new map.');		//XXX
		if (!$GLOBALS['map']->isDark) return;
		global $view;

		$view->forceUpdate = true;

		$view->addLightSource($this->lightSource);
	}
}