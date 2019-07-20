<?php

class skl_teleport extends Skill
{
	public $epCost		= 20;
	public $hpCost		= 6;
	public $cooldown	= 2;

	public $range = 10;

	public function __construct ($level = 1)
	{
		$this->level = $level;

		$name = 'Teleport';

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
		return "Teleport up to {$this->range} paces.";
	}

	function onUse($n_offset, $w_offset)
	{
		if (!isset($n_offset, $w_offset))
		{
			update_thoughts('I can\'t see my end point!');
			return;
		}

		global $map;

		$start_n_offset = $this->owner->n_offset;
		$start_w_offset = $this->owner->w_offset;

		if ($this->owner->move($n_offset, $w_offset))
		{
			$effectSpriteFrames = [];

			$sprite = isset($this->owner->spriteSet[SPRI_GEAR]) ? clone $this->owner->spriteSet[SPRI_GEAR] : clone $this->owner->sprite;

			$spr_white = paintSprite($sprite, '#fff');
			$spr_cyan = paintSprite($sprite, '#0ff');

			$effectSpriteFrames[] = $spr_white->frames[0];
			$effectSpriteFrames[] = $spr_cyan->frames[0];

			$effectSprite = new Sprite($effectSpriteFrames);

			console_echo('Effect sprite: ' . console_sprite($effectSprite), '#aff');

			$effect = new Effect($effectSprite, $start_n_offset, $start_w_offset, 2, LAYER_PLAYER);

			$map->addEffects($effect);


			global $view;
			$view->forceUpdate = true;

			return true;
		}

		return false;
	}

	function onChangeLevel()
	{
		$this->epCost = max(30 - $this->level, 5);
		$this->range = min(15, 5 + $this->level);
	}

	function getRelatedSkills()
	{
		return [];
	}
}
