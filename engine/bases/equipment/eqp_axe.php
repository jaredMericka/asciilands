<?php

class eqp_axe extends a_eqp_weapon
{
	const MAT_BLADE	= 0;
	const MAT_HILT	= 1;

	public $DS_base_choices = [
		DS_STRENGTH,
		DS_AGILITY
	];

	public $technique = [
		TEQT_MELEE =>
		[
			TEQ_DAMAGE		=> [DS_STRENGTH => 1, DS_FORCE => 1],
			TEQ_HIT_CHANCE	=> [DS_FINESSE => 0.7, DS_INERTIA => 0.5],
			TEQ_CRIT_DAMAGE	=> [DS_CONTROL => 1],
			TEQ_CRIT_CHANCE	=> [DS_BALANCE => 0.3, DS_CONTROL => 0.3, DS_KNOWLEDGE => 0.4],
			TEQ_DEFENCE		=> [DS_AGILITY => 1],
			TEQ_DODGE_CHANCE	=> [DS_EVASIVENESS => 1],

			TEQ_ATTACK_SPEED	=> [DS_CONTROL => 0.5, DS_INERTIA => -1],
			TEQ_CONSISTENCY	=> [DS_CONTROL => 1],
		]
	];

	public function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->ICATs[] = ICAT_WEAPON;

		$this->DMGDL = mt_rand(0, 1) ? DMGDL_BLUNT : DMGDL_CUT;

		parent::__construct($level, $name, $description, $spriteSet);
	}

	public function getShoppingLists()
	{
		return [
			'axe' => [
				self::MAT_HILT => 'mat_metal',
				self::MAT_BLADE => 'mat_metal',
			],
			'axe1' => [
				self::MAT_HILT => 'mat_wood',
				self::MAT_BLADE => 'mat_metal',
			]
		];
	}

	public function getSpriteSet()
	{
		$bladeChars	= ['()', '[]', '(>', '[>', '{}', '{>'];
		$bladeChars = $bladeChars[array_rand($bladeChars)];
		$midChars	= ['x', 'U', 'V'];

		$sprite = new Sprite([
			0 => new SpriteElement(null, $this->materials[self::MAT_BLADE]->colour, $bladeChars[0]),
			1 => new SpriteElement(null, $this->materials[self::MAT_BLADE]->colour, $midChars[array_rand($midChars)]),
			2 => new SpriteElement(null, $this->materials[self::MAT_BLADE]->colour, $bladeChars[1]),

			4 => new SpriteElement(null, $this->materials[self::MAT_HILT]->colour, '|')
		]);

		$overSprite = new Sprite([
			0 => new SpriteElement(null, $this->materials[self::MAT_BLADE]->colour, '&#x2660;')
		]);

		return [
			SPRI_DEFAULT => $sprite,
			SPRI_OVERSPRITE => $overSprite,
		];
	}

	function getDescription()
	{
		return "{$this->materials[self::MAT_HILT]->name} handled {$this->noun} of {$this->materials[self::MAT_BLADE]->name}.";
	}

	function getName()
	{
		return "{$this->materials[self::MAT_BLADE]->name} {$this->noun}";
	}
}