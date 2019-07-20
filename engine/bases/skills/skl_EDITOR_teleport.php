<?php

class skl_EDITOR_teleport extends a_skl_EDITOR
{
	public function __construct()
	{
		$name = 'Move to';

		$sprite = new Sprite([
			0 => new SpriteElement(null, '#0ff', '\\'),
			1 => new SpriteElement(null, '#fff', '\\'),
			2 => new SpriteElement(null, '#0ff', '\\'),
			3 => new SpriteElement(null, '#0ff', '/'),
			4 => new SpriteElement(null, '#fff', '/'),
			5 => new SpriteElement(null, '#0ff', '/'),
			]);

		parent::__construct($name, $sprite);
	}

	public function getDescription()
	{
		return 'Go wherever';
	}

	function onUse($n_offset, $w_offset)
	{
		if (!isset($n_offset, $w_offset))
		{
			update_thoughts('I can\'t see my end point!');
			return;
		}

		if ($this->owner->move($n_offset, $w_offset))
		{
			global $view;
			$view->forceUpdate = true;

			return true;
		}

		return false;
	}
}
