<?php

class skl_EDITOR_select extends a_skl_EDITOR
{
	// No tiles are selected and selection is not in progress
	const MODE_NONE = 0;
	// Selection is in progress
	const MODE_SELECTING = 1;
	// Selection is complete
	const MODE_COMPLETE = 2;

	public $selectionMarkers;

	public $mode;

	public $markerSprite;
	public $markerPendingSprite;

	public function __construct()
	{
		global $view;

		$sprite = new Sprite([
			new SpriteElement(null, '#f0f', '&#x250c;'),
			new SpriteElement(null, '#f0f', '&#x2500;'),
			new SpriteElement(null, '#f0f', '&#x2510;'),
			new SpriteElement(null, '#f0f', '&#x2514;'),
			new SpriteElement(null, '#f0f', '&#x2500;'),
			new SpriteElement(null, '#fff', '&#x253c;'),
		]);

		$markerSprite = new Sprite([
			0 => new SpriteElement(null, '#f0f', '&#x250c;'),
			1 => new SpriteElement(null, '#f0f', '&#x2500;'),
			2 => new SpriteElement(null, '#f0f', '&#x2510;'),

			3 => new SpriteElement(null, '#f0f', '&#x2514;'),
			4 => new SpriteElement(null, '#f0f', '&#x2500;'),
			5 => new SpriteElement(null, '#f0f', '&#x2518;'),
		]);

		$markerPendingSprite = new Sprite([
			[
				0 => new SpriteElement(null, '#f0f', '&#x250c;'),
				1 => new SpriteElement(null, '#fff', '&#x2500;'),
				2 => new SpriteElement(null, '#f0f', '&#x2510;'),

				3 => new SpriteElement(null, '#fff', '&#x2514;'),
				4 => new SpriteElement(null, '#f0f', '&#x2500;'),
				5 => new SpriteElement(null, '#fff', '&#x2518;'),
			],
			[
				0 => new SpriteElement(null, '#fff', '&#x250c;'),
				1 => new SpriteElement(null, '#f0f', '&#x2500;'),
				2 => new SpriteElement(null, '#fff', '&#x2510;'),

				3 => new SpriteElement(null, '#f0f', '&#x2514;'),
				4 => new SpriteElement(null, '#fff', '&#x2500;'),
				5 => new SpriteElement(null, '#f0f', '&#x2518;'),
			],
		]);

		$this->markerSprite = $view->addClientSprite($markerSprite);
		$this->markerPendingSprite = $view->addClientSprite($markerPendingSprite);

		$this->mode = self::MODE_NONE;

		parent::__construct('Select', $sprite);
	}

	public function getDescription()
	{
		'Used to select areas of tiles or scenery.';
	}

	public function onUse($n_offset, $w_offset)
	{
		if (!isset($n_offset, $w_offset)) return;

		global $map;

		switch ($this->mode)
		{
			case self::MODE_NONE:
				$this->owner->selection = new editorSelection($this->markerSprite);
				$this->owner->selection->pointA = [$n_offset, $w_offset];
				$map->addObjects(new editorMark($this->markerPendingSprite, $n_offset, $w_offset));
				update_thoughts('Selection anchor placed...');
				$this->mode = self::MODE_SELECTING;
				break;

			case self::MODE_SELECTING:
				$this->owner->selection->pointB = [$n_offset, $w_offset];
				update_thoughts('Selection ready.');
				$this->owner->selection->drawSelection();

				$this->mode = self::MODE_COMPLETE;
				break;

			case self::MODE_COMPLETE:
				$this->owner->selection->clearSelection();
				update_thoughts('Selection cleared!');
				$this->mode = self::MODE_NONE;
				break;
		}

		return true;
	}
}