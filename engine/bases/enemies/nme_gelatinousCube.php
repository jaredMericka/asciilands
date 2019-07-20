<?php

class nme_gelatinousCube extends Enemy
{

	public function __construct($level)
	{
		$name= 'Gelatinous Cube';
		$spriteSet = $this->getSpriteSet();

		$this->level = $level;

		parent::__construct($name, $spriteSet);
	}

	function getLootArray()
	{
		return [

		];
	}

	public function getSpriteSet($colour = '#6d6')
	{
		$colourDark = tint($colour, -2, true);
		$colourLight = tint($colour, 2, true);

		$spr_default = new Sprite([
			[
				0 => new SpriteElement($colour, $colourLight, '.'),
				1 => new SpriteElement($colour, $colourLight, '&bull;'),
				2 => new SpriteElement($colour, $colourLight, ':'),
				3 => new SpriteElement($colourDark, $colour, '&bull;'),
				4 => new SpriteElement($colourDark, $colour, '.'),
				5 => new SpriteElement($colourDark, $colour, '&deg;'),
			],
			[
				0 => new SpriteElement($colour, $colourLight, '&bull;'),
				1 => new SpriteElement($colour, $colourLight, '&deg;'),
				2 => new SpriteElement($colour, $colourLight, '&#x00b7;'),
				3 => new SpriteElement($colourDark, $colour, '&deg;'),
				4 => new SpriteElement($colourDark, $colour, '&bull;'),
				5 => new SpriteElement($colourDark, $colour, '.'),
			],
		]);

		$corpseChars = [
			'&bull;',
			'&bull;',
			'.',
			'.',
			':',
			':',
		];

		shuffle($corpseChars);

		$spr_corpse = new Sprite([
			[
				0 => new SpriteElement(null, $colour, $corpseChars[0]),
				1 => new SpriteElement(null, $colour, $corpseChars[1]),
				2 => new SpriteElement(null, $colour, $corpseChars[2]),
				3 => new SpriteElement(null, $colour, $corpseChars[3]),
				4 => new SpriteElement(null, $colour, $corpseChars[4]),
				5 => new SpriteElement(null, $colour, $corpseChars[5]),
			]
		]);

		return [
			SPRI_DEFAULT	=> $spr_default,
			SPRI_CORPSE		=> $spr_corpse,
		];
	}

}