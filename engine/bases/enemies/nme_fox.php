<?php

class nme_fox extends Enemy
{
	public $DMGDL = DMGDL_POINT;

	public $DMGs = [
		DMG_TRAUMA	=> 10
	];

	public $DSs = [
		DS_HP_MAX		=> 65,
		DS_SPEED		=> 0.4,
		DS_SPEED_FAST	=> 0.2,
	];

	public function __construct($level, $colour = null)
	{
		$spriteSet = self::getSpriteSet($colour);

		$gender = mt_rand(0, 1) ? GND_MALE : GND_FEMALE;

		$this->level = $level;

		parent::__construct('Fox', $spriteSet, $gender);
	}

	static function getSpriteSet($colour = null)
	{
		if (!$colour) $colour = '#f90';

		return [
			SPRI_DEFAULT => new Sprite([
				[
					0 => new SpriteElement(null,$colour, '&#x201e;'),
					2 => new SpriteElement(null,'#fff', '&#x2510;'),
					3 => new SpriteElement(null,'#fff', '&#x25bc;'),
					4 => new SpriteElement(null,$colour, '&#x2552;'),
					5 => new SpriteElement(null,$colour, '&#x2561;'),
				],
			]),

			SPRI_WEST => new Sprite([
				[
					0 => new SpriteElement(null,$colour, '&#x201e;'),
					2 => new SpriteElement(null,'#fff', '&#x2510;'),
					3 => new SpriteElement(null,'#fff', '&#x25bc;'),
					4 => new SpriteElement(null,$colour, '&#x2552;'),
					5 => new SpriteElement(null,$colour, '&#x2561;'),
				],
			]),

			SPRI_EAST => new Sprite([
				[
					0 => new SpriteElement(null,'#fff', '&#x250c;'),
					2 => new SpriteElement(null,$colour, '&#x201e;'),
					3 => new SpriteElement(null,$colour, '&#x255e;'),
					4 => new SpriteElement(null,$colour, '&#x2555;'),
					5 => new SpriteElement(null,'#fff', '&#x25bc;'),
				],
			])
		];
	}

	function __clone()
	{
		$this->gender = mt_rand(0, 1) ? GND_MALE : GND_FEMALE;
		parent::__clone();
	}

	function getLootArray()
	{
		global $rootPath;

		require "{$rootPath}content/materials/uncommon_fabrics.mat";

		return [
			80 => [
				new itm_material($mat_foxPelt)
			],
		];
	}
}