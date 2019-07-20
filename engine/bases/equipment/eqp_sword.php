<?php

class eqp_sword extends a_eqp_weapon
{
	public $DMGDL = DMGDL_CUT;

	const MAT_BLADE	= 0;
	const MAT_HILT	= 1;

	public $DS_base_choices = [
		DS_STRENGTH,
		DS_AGILITY,
		DS_INTELLECT
	];

	public $technique = [
		TEQT_MELEE =>
		[
			TEQ_DAMAGE		=> [DS_STRENGTH => 1],
			TEQ_HIT_CHANCE	=> [DS_FINESSE => 0.7, DS_INERTIA => 0.5],
			TEQ_CRIT_DAMAGE	=> [DS_CONTROL => 1],
			TEQ_CRIT_CHANCE	=> [DS_BALANCE => 1],
			TEQ_DEFENCE		=> [DS_AGILITY => 1],
			TEQ_DODGE_CHANCE	=> [DS_EVASIVENESS => 1],

			TEQ_ATTACK_SPEED	=> [DS_DEXTERITY => 0.5, DS_FINESSE => 0.5, DS_INERTIA => -0.2],
			TEQ_CONSISTENCY	=> [DS_CONTROL => 1],
		]
	];

	public function __construct($level = null, $name = null, $description = null, $spriteSet = null)
	{
		$this->ICATs[] = ICAT_WEAPON;

		parent::__construct($level, $name, $description, $spriteSet);
	}

	public function getShoppingLists()
	{
		return [
			'sword' => [
				self::MAT_HILT => 'mat_metal',
				self::MAT_BLADE => 'mat_metal',
			],
			'sword1' => [
				self::MAT_HILT => 'mat_wood',
				self::MAT_BLADE => 'mat_metal',
			]
		];
	}

	public function getSpriteSet()
	{
		$bladeChars = ['&#x007c;', '&#x2502;', '&#x2320;', '&#x2193;', ')'];
		$hiltChars	= ['T', 'I', '&#x0166;', '&#x2020;'];
		$guardChars = [null, null, '('];

		$bladeChar	= $bladeChars[array_rand($bladeChars)];
		$hiltChar	= $hiltChars[array_rand($hiltChars)];
		$guardChar	= $guardChars[array_rand($guardChars)];

		if ($guardChar) $guardColour = (mt_rand(0, 1) ? $this->materials[self::MAT_BLADE]->colour : $this->materials[self::MAT_HILT]->colour);

		$spe_blade = new SpriteElement(null, $this->materials[self::MAT_BLADE]->colour, $bladeChar);

		$sprite = new Sprite([
			1 => $spe_blade,
			3 => ($guardChar ? new SpriteElement(null, $guardColour, $guardChar) : null),
			4 => new SpriteElement(null, $this->materials[self::MAT_HILT]->colour, $hiltChar)
		]);

		$overSprite = new Sprite([
			0 => $spe_blade,
			3 => ($guardChar ? new SpriteElement(null, $guardColour, '@') : null),
		]);

		return [
			SPRI_DEFAULT => $sprite,
			SPRI_OVERSPRITE => $overSprite
		];
	}

	function getName()
	{
		return "{$this->materials[self::MAT_BLADE]->name} {$this->noun}";
	}

	function getDescription()
	{
		return "{$this->materials[self::MAT_HILT]->name} hilted, {$this->materials[self::MAT_BLADE]->name} bladed {$this->noun}.";
	}

	protected function applyQuirks()
	{
		global $DMG_names;

//		if (count($this->DMGs) <= 2 && percentageToBool(20))
		if (count($this->DMGs) <= 2) // A chance to split one damage type into two.
		{
			$maxDMG = array_search(max($this->DMGs), $this->DMGs);
			$newDamage = getNuancedValue($this->DMGs[$maxDMG] / 2, 20);
			$this->DMGs[$maxDMG] *= 0.5;

			$newDMG = array_rand(array_diff_key($DMG_names, $this->DMGs));

			$this->DMGs[$newDMG] = $newDamage;
		}

		// Fix up all this shit; make it make more sense.

		$maxDMG = array_search(max($this->DMGs), $this->DMGs); // gotta renew this in case it got changed in the above if.
		$relativeDamage = $this->DMGs[$maxDMG] / ($this->level * $this->DMGs_mod);

		console_echo("Relative damage coefficient for <<#afa>>{$this->name}<> : <<#faf>>{$relativeDamage}<>", '#fff');

//		if ($this->DMGs && max($this->DMGs) >= $highBase)


		if ($relativeDamage > 1.5)
		{
			global $DMG_colours;

			foreach($this->spriteSet[SPRI_DEFAULT]->frames as &$frame)
			{
				$frame[1]->fg = getBetweenColour($frame[1]->fg, $DMG_colours[$maxDMG]);
			}
		}

		if ($relativeDamage > 2)
		{
			global $DMG_osprs;

			$this->spriteSet[SPRI_DEFAULT] = $this->spriteSet[SPRI_DEFAULT]->augment($DMG_osprs[$maxDMG]);
		}

		parent::applyQuirks();
	}
}