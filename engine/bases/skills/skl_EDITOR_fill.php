<?php

class skl_EDITOR_fill extends a_skl_EDITOR
{
	public function __construct()
	{
		$sprite = new Sprite([
			[
				0 => null,
				1 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				2 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				3 => new SpriteElement(null, '#00f', '&#x2590;'),
				4 => new SpriteElement(null, '#666', '&#x2580;'),
				5 => new SpriteElement(null, '#666', '&#x2580;'),
			],
			[
				1 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				2 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				3 => new SpriteElement(null, '#f0f', '&#x2590;'),
				4 => new SpriteElement(null, '#666', '&#x2580;'),
				5 => new SpriteElement(null, '#666', '&#x2580;'),
			],
			[
				1 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				2 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				3 => new SpriteElement(null, '#f00', '&#x2590;'),
				4 => new SpriteElement(null, '#666', '&#x2580;'),
				5 => new SpriteElement(null, '#666', '&#x2580;'),
			],
			[
				1 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				2 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				3 => new SpriteElement(null, '#ff0', '&#x2590;'),
				4 => new SpriteElement(null, '#666', '&#x2580;'),
				5 => new SpriteElement(null, '#666', '&#x2580;'),
			],
			[
				1 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				2 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				3 => new SpriteElement(null, '#0f0', '&#x2590;'),
				4 => new SpriteElement(null, '#666', '&#x2580;'),
				5 => new SpriteElement(null, '#666', '&#x2580;'),
			],
			[
				1 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				2 => new SpriteElement('#888', '#aaa', '&#x2580;'),
				3 => new SpriteElement(null, '#0ff', '&#x2590;'),
				4 => new SpriteElement(null, '#666', '&#x2580;'),
				5 => new SpriteElement(null, '#666', '&#x2580;'),
			],
		]);

		parent::__construct('Tile fill', $sprite);
	}

	public function getDescription()
	{
		return 'Next tile placement fills the area';
	}

	public function onRegister(Dude $owner)
	{
//		global $view;

//		$owner->spriteSet[SPRI_ACTIVE] = $view->addClientSprite($this->sprite);
		parent::onRegister($owner);
	}

	public function onUse($n_offset, $w_offset)
	{
		if ($this->owner->fillMode)
		{
			$this->owner->fillMode = false;
			$this->owner->removeSpriteEffect($this->sprite);

			update_thoughts('Single placement has resumed.');
		}
		else
		{
			$this->owner->fillMode = true;
			$this->owner->addSpriteEffect($this->sprite);

			update_thoughts('Next placement will fill.');
		}
	}
}
