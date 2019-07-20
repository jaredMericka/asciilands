<?php

class nme_bird extends Enemy
{

	public $DSs = [
		DS_HANDICAP => 0.2,

		DS_HP_MAX => 20,
		DS_EP_MAX => 10,
		DS_SPEED => 0.4,
		DS_SPEED_FAST => 0.2,
	];

	public $TPL_passables = [
		TPL_OPENGROUND,
		TPL_LOWOBSTACLE,
		TPL_HIGHOBSTACLE,
		TPL_VERTICAL,
	];

	public $DMGDL = DMGDL_POINT;

	public $DMGs = [
		DMG_TRAUMA => 5,
		DMG_INFECTION => 10
		];

	public function __construct($level)
	{
		$this->level = $level;

		$gender = mt_rand(0, 1) ? GND_MALE : GND_FEMALE;

		$name = 'Bird';
		$spriteSet = $this->getSpriteSet();

		parent::__construct($name, $spriteSet, $gender);
	}

	function getLootArray()
	{
		return [

		];
	}

	public function getSpriteSet($flying = true)
	{
//		$col_feathers = '#000';
//		$col_eye = '#f00';
//		$col_beak = '#000';
//		$col_wingDown = '#555';

		$col_feathers = '#dd0';
		$col_eye = '#000';
		$col_beak = '#f80';
		$col_wingDown = null;

		if (!$col_wingDown) $col_wingDown = tint($col_feathers, -5);

		if ($flying)
		{
			$spriteSet = [
				SPRI_EAST => new Sprite([
					[
						0 => new SpriteElement(null,$col_feathers, '&#x25b2;'),
						3 => new SpriteElement($col_feathers,  null, '&nbsp;'),
						4 => new SpriteElement($col_feathers, $col_eye, '&bull;'),
						5 => new SpriteElement(null,$col_beak, '='),
					],
					[
						3 => new SpriteElement($col_feathers, $col_wingDown, 'V'),
						4 => new SpriteElement($col_feathers, $col_eye, '&bull;'),
						5 => new SpriteElement(null,$col_beak, '='),
					],
				]),

				SPRI_WEST => new Sprite([
					[
						2 => new SpriteElement(null,$col_feathers, '&#x25b2;'),
						3 => new SpriteElement(null,$col_beak, '='),
						4 => new SpriteElement($col_feathers, $col_eye, '&bull;'),
						5 => new SpriteElement($col_feathers,  null, '&nbsp;'),
					],
					[
						3 => new SpriteElement(null,$col_beak, '='),
						4 => new SpriteElement($col_feathers, $col_eye, '&bull;'),
						5 => new SpriteElement($col_feathers, $col_wingDown, 'V'),
					],
				])
			];
		}
		else
		{
			$spriteSet = [
				SPRI_EAST => new Sprite([
					[
						0 => new SpriteElement($col_feathers, $col_wingDown, 'V'),
						1 => new SpriteElement($col_feathers, $col_eye, '&bull;'),
						2 => new SpriteElement(null,$col_beak, '='),
						3 => new SpriteElement(null,$col_beak, '&#x2559;'),
					],
				]),

				SPRI_WEST => new Sprite([
					[
						0 => new SpriteElement(null,$col_beak, '='),
						1 => new SpriteElement($col_feathers, $col_eye, '&bull;'),
						2 => new SpriteElement($col_feathers, $col_wingDown, 'V'),
						5 => new SpriteElement(null,$col_beak, '&#x255c;'),
					],
				])
			];
		}

		return $spriteSet;
	}
}