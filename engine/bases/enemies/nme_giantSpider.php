<?php

class nme_giantSpider extends Enemy
{
	public $FAC = FAC_ANIMAL;

	protected $DSs = [
		DS_HANDICAP		=> 0.7,
		DS_HP_MAX		=> 200,
		DS_EP_MAX		=> 40,
		DS_LUCK			=> 10,
		DS_SPEED		=> 0.8,
		DS_SPEED_FAST	=> 0.4,
		DS_STRENGTH		=> 110,
		DS_FORCE		=> 90,
		DS_RESILIENCE	=> 75,
		DS_CONTROL		=> 200,
		DS_INERTIA		=> 60,
		DS_RECOVERY		=> 190,
		DS_AGILITY		=> 200,
		DS_DEXTERITY	=> 240,
		DS_EVASIVENESS	=> 215,
		DS_FINESSE		=> 190,
		DS_BALANCE		=> 300,
		DS_REACH		=> 300,
		DS_MAGIC		=> 20,
		DS_DISRUPTION	=> 10,
		DS_DISCIPLINE	=> 5,
		DS_FOCUS		=> 100,
		DS_INSANITY		=> 300,
		DS_CHARISMA		=> 1,
		DS_REPUTATION	=> 1,
		DS_NOTORIETY	=> 200,
		DS_FAME			=> 1,
		DS_DISCOUNT		=> 1,
		DS_BARGAINING	=> 1,
		DS_LEADERSHIP	=> 1,
		DS_INTELLECT	=> 12,
		DS_TENACITY		=> 150,
		DS_KNOWLEDGE	=> 1,
		DS_HEURISTICS	=> 70,
		DS_JUDGEMENT	=> 130,
	];

	public $technique = [
		TEQT_MELEE => [
			TEQ_DAMAGE		=> [DS_INSANITY => 0.6, DS_FORCE => 0.4, DS_STRENGTH => 0.4],
			TEQ_HIT_CHANCE	=> [DS_REACH => 0.2, DS_DEXTERITY => 0.5],
			TEQ_CRIT_DAMAGE	=> [DS_FINESSE => 1],
			TEQ_CRIT_CHANCE	=> [DS_FINESSE => 1],
			TEQ_DEFENCE		=> [DS_RESILIENCE => 1],
			TEQ_DODGE_CHANCE	=> [DS_EVASIVENESS => 1],

			TEQ_ATTACK_SPEED	=> [DS_DEXTERITY => 0.7, DS_FINESSE => 0.3, DS_INERTIA => -0.3],
			TEQ_CONSISTENCY	=> [DS_INTELLECT => 1, DS_HEURISTICS => 0.5],
		]
	];

	public $DMGs = [DMG_TRAUMA => 3];

	public $DMGDL = DMGDL_POINT;

	public function __construct($level = 1)
	{
		$this->level = $level;

		$name = 'Giant spider';
		$spriteSet = $this->getSpriteSet();

		$this->constituents[0][1] = new ObjectConstituent($this->getSpriteSet(true));

		$this->addBehaviour(new dbhv_dealDamageOverTime(DMGDL_LIQUID, [DMG_POISON => 10], 6, 0));

		parent::__construct($name, $spriteSet);
	}

	function getLootArray()
	{
		return [

		];
	}

	public function getSpriteSet($rightSide = false)
	{
		if ($rightSide)
		{
			return [
				SPRI_DEFAULT => new Sprite([
					0 => new SpriteElement('#888', '#f00', ':'),
					1 => new SpriteElement(null, '#fff', '_'),
					3 => new SpriteElement(null, '#ff0', '&#x25bc;'),
					4 => new SpriteElement(null, '#fff', '\\'),
					5 => new SpriteElement(null, '#fff', '\\'),
					]),

				SPRI_CORPSE => new Sprite([
					0 => new SpriteElement(null, '#ff0', '&#x25b2;'),
					1 => new SpriteElement(null, '#fff', '\\'),
					2 => new SpriteElement(null, '#fff', '\\'),
					3 => new SpriteElement('#888', '#000', '='),
					4 => new SpriteElement(null, '#fff', '/'),
					5 => new SpriteElement(null, '#fff', '/'),
					])
			];
		}
		else
		{
			return [
				SPRI_DEFAULT => new Sprite([
					1 => new SpriteElement(null, '#fff', '_'),
					2 => new SpriteElement('#888', '#f00', ':'),
					3 => new SpriteElement(null, '#fff', '/'),
					4 => new SpriteElement(null, '#fff', '/'),
					5 => new SpriteElement(null, '#ff0', '&#x25bc;'),
					]),

				SPRI_CORPSE => new Sprite([
					0 => new SpriteElement(null, '#fff', '/'),
					1 => new SpriteElement(null, '#fff', '/'),
					2 => new SpriteElement(null, '#ff0', '&#x25b2;'),
					3 => new SpriteElement(null, '#fff', '\\'),
					4 => new SpriteElement(null, '#fff', '\\'),
					5 => new SpriteElement('#888', '#000', '='),
					])
			];
		}
	}
}