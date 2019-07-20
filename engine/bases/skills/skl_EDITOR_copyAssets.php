<?php

class skl_EDITOR_copyAssets extends a_skl_EDITOR
{
	public $layer;

	public function __construct($copyTile = true)
	{
		$sprite = new Sprite([
			0 => new SpriteElement('#77f', null, ' '),
			1 => new SpriteElement('#77f', '#aaa', '&#x2584;'),
			2 => new SpriteElement(null, '#aaa', '&#x2584;'),
			3 => new SpriteElement(null, '#77f', '&#x2580;'),
			4 => new SpriteElement('#aaa', null, ' '),
			5 => new SpriteElement('#aaa', '#444', ($copyTile ? 'T' : 'S')),
		]);

		$this->layer = $copyTile ? 'tiles' : 'scenery';

		parent::__construct("Copy {$this->layer}", $sprite);

		$this->key = get_class() . $this->layer;
	}

	public function getDescription()
	{
		return "Copies selected {$this->layer}.";
	}

	public function onUse($n_offset, $w_offset)
	{
		return $this->owner->copyAssets($this->layer);
	}
}

