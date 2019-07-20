<?php

class Effect
{
	public $sprite;
	public $n_offset;
	public $w_offset;
	public $frames;
	public $layer;

	public function __construct($sprite, $n_offset, $w_offset, $frames = null, $LAYER = null)
	{
		global $view;

		$this->sprite = $view->addClientSprite($sprite);
		$this->n_offset = $n_offset;
		$this->w_offset = $w_offset;

		$this->frames = $frames ? $frames : count($sprite->frames);
		$this->layer = isset($LAYER) ? $LAYER : LAYER_PROJECTILE;
	}

	public function tick()
	{
		if ($this->frames-- < 0) $this->delete();
	}

	public function delete()
	{
		global $map;

		if (isset($map->effects[$this->n_offset][$this->w_offset]) &&
			$map->effects[$this->n_offset][$this->w_offset] === $this)
		{
			unset($map->effects[$this->n_offset][$this->w_offset]);
		}
	}
}