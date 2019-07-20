<?php

class nme_ghost extends Enemy
{
	public $FAC = FAC_MONSTER;

	public $DSs = [

	];

	public $DMGs_def = [
		DMG_WATER => 500,
		DMG_POISON => 500,
		DMG_FIRE => -10,

		DMGDL_CUT => 15,
		DMGDL_BLUNT => 20,
		DMGDL_MISSILE => 15,
		DMGDL_PLASMA => -10,
	];

	public $TEQT		= TEQT_MAGIC;
	public $TEQT_def	= TEQT_MAGIC;

	public function __construct($DMG = null)
	{
		if ($DMG)
		{
			global $DMG_colours;
			global $DMG_names;

			$spriteSet = self::getSprite($DMG_colours[$DMG], '#fff');
			$name = "{$DMG_names[$DMG]} ghost";
		}
		else
		{
			$spriteSet = self::getSprite('#fff', '#fff');
			$name = 'ghost';
		}

		parent::__construct($name, $spriteSet);

		if ($DMG)
		{
			$this->DMGs = [
				DMG_TRAUMA => 5,
				$DMG => 15
			];
		}
		else
		{
			$this->DMGs = [
				DMG_TRAUMA => 15
			];
		}

		$this->addBehaviour(new dbhv_alterAttackerHealthOnDefend('-50%', TRG_DEATH, 100));
	}

	function getLootArray()
	{
		return [

		];
	}

	static function getSprite($bodyColour, $eyeColour = null)
	{
		$bodyColour = tint($bodyColour, 5);
		if (!isset($eyeColour))$eyeColour = tint($bodyColour, -3);

		$sprite = new Sprite([
			[
				0 => new SpriteElement(null, $bodyColour, '('),
				1 => new SpriteElement(null, $eyeColour, '&#x221e;'),
				2 => new SpriteElement(null, $bodyColour, ')'),
				3 => new SpriteElement(null, $bodyColour, '/'),
				4 => new SpriteElement(null, $bodyColour, '/'),
			],
			[
				0 => new SpriteElement(null, $bodyColour, '('),
				1 => new SpriteElement(null, $eyeColour, '&#x221e;'),
				2 => new SpriteElement(null, $bodyColour, ')'),
				3 => new SpriteElement(null, $bodyColour, '('),
				4 => new SpriteElement(null, $bodyColour, '('),
			],
			[
				0 => new SpriteElement(null, $bodyColour, '('),
				1 => new SpriteElement(null, $eyeColour, '&#x221e;'),
				2 => new SpriteElement(null, $bodyColour, ')'),
				4 => new SpriteElement(null, $bodyColour, ')'),
				5 => new SpriteElement(null, $bodyColour, ')'),
			],
			[
				0 => new SpriteElement(null, $bodyColour, '('),
				1 => new SpriteElement(null, $eyeColour, '&#x221e;'),
				2 => new SpriteElement(null, $bodyColour, ')'),
				4 => new SpriteElement(null, $bodyColour, '\\'),
				5 => new SpriteElement(null, $bodyColour, '\\'),
			],
			[
				0 => new SpriteElement(null, $bodyColour, '('),
				1 => new SpriteElement(null, $eyeColour, '&#x221e;'),
				2 => new SpriteElement(null, $bodyColour, ')'),
				4 => new SpriteElement(null, $bodyColour, ')'),
				5 => new SpriteElement(null, $bodyColour, ')'),
			],
			[
				0 => new SpriteElement(null, $bodyColour, '('),
				1 => new SpriteElement(null, $eyeColour, '&#x221e;'),
				2 => new SpriteElement(null, $bodyColour, ')'),
				3 => new SpriteElement(null, $bodyColour, '('),
				4 => new SpriteElement(null, $bodyColour, '('),
			],
		]);

		$corpse = new Sprite([[]]);

		return [
			SPRI_DEFAULT => $sprite,
			SPRI_CORPSE => $corpse
		];
	}
}