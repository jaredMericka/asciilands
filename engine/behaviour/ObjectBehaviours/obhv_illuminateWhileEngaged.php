<?php

class obhv_illuminateWhileEngaged extends ObjectBehaviour
{
	public $lightSource;

	public function __construct($distance, $colour = null, $opacity = 0, $absoluteOpacity = true, $illuminatedSprite = null)
	{
		$this->onEngage		= true;
		$this->onDisengage	= true;

		$this->lightSource = new lightSource($distance, $colour, $opacity, $absoluteOpacity);
		$this->spriteSet[0] = $illuminatedSprite;

		$description = "Illuminates when engaged extending view by {$distance} paces.";

		parent::__construct($description, BHVK_OVERLAY);
	}

	function onEngage(Player $player)
	{
		global $view;

		$view->addLightSource($this->lightSource);

		if (isset($this->spriteSet[0]))
		{
			$this->owner->sprite = $this->spriteSet[0];
		}
	}

	function onDisengage(Player $player)
	{
		$this->isActive = false;
		global $view;

		$view->removeLightSource($this->lightSource);

		if (isset($this->spriteSet[0]))
		{
			$this->owner->sprite = $this->owner->spriteSet[0];
		}
	}
}