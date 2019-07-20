<?php

class obj_projectile extends AsObject
{
	public $permitEntryDefault = true;

	public $spawner;

	public $TPL_passables = [TPL_OPENGROUND, TPL_LOWOBSTACLE];

	public function __construct($name, $spriteSet, $spawner, $DIR, $range = null)
	{
		parent::__construct($name, $spriteSet, LAYER_PROJECTILE);
		$this->addBehaviour(new obhv_moveInDirection($DIR, 0.2, $range, true));
	}

	static function getSpriteSet($speArray)
	{
		$trailColour = '#aaa';

		$north_frames = [];
		$south_frames = [];
		$east_frames = [];
		$west_frames = [];

		foreach ($speArray as $key => $spe)
		{
			$altTrail = $key % 2 === 0 ? true : false;

			$north_frames[] = [
				1 => $spe,
				4 => new SpriteElement(null, $trailColour, $altTrail ? '!' : '&#x203c;')
			];

			$south_frames[] = [
				1 => new SpriteElement(null, $trailColour, $altTrail ? '!' : '&#x203c;'),
				4 => $spe
			];

			$east_frames[] = [
				0 => new SpriteElement(null, $trailColour, $altTrail ? '-' : '='),
				1 => new SpriteElement(null, $trailColour, $altTrail ? '=' : '-'),
				2 => $spe
			];

			$west_frames[] = [
				0 => $spe,
				1 => new SpriteElement(null, $trailColour, $altTrail ? '-' : '='),
				2 => new SpriteElement(null, $trailColour, $altTrail ? '=' : '-')
			];
		}

		// Only making this one so I don't have to make it twice when it goes into the array twice.
		$spr_east = new Sprite($east_frames);

		return [
			SPRI_DEFAULT	=> $spr_east,
			SPRI_EAST		=> $spr_east,
			SPRI_WEST		=> new Sprite($west_frames),
			SPRI_NORTH		=> new Sprite($north_frames),
			SPRI_SOUTH		=> new Sprite($south_frames)
		];
	}
}