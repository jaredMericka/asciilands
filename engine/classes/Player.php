<?php

class Player extends Dude
{
	use QuestCapability;

    public $name;

	public $xp			= 0;
	public $nextLevel	= 100;

    public $MAP;
	public $mapId;

	public $slm_head;
	public $slm_hand;
	public $slm_legs;

	public $opponent = null;

	public $lastMoved;

	public $canPush = true;
	public $canOpenDoors = true;

	public $showItemPrices = false;

	public $bindings = [];

	public $lvl_DSs = [
		DS_HP_MAX => 1,
		DS_EP_MAX => 1,
	];

	public $used_DSs = [];
	public $observed_skills = [];
	// note: $used_skills is in the skillsCapability trait. - I don't know what's best but that's where it is.

	public $pendingBoons = [];
	public $pendingBoonCount = 0;

	public $TPL_passables = [
		TPL_OPENGROUND,
		TPL_LADDER,
		TPL_STAIRS,
	];

	public $WPs = [];

	public $checkpoint_n_offset;
	public $checkpoint_w_offset;
	public $checkpoint_MAP;

	public $technique = [
		TEQT_MELEE => [	// This has been tuned; the others ahave not.
			TEQ_DAMAGE		=> [DS_STRENGTH => 0.4, DS_FORCE => 0.4],
			TEQ_HIT_CHANCE	=> [DS_CONTROL => 0.8],
			TEQ_CRIT_DAMAGE	=> [DS_FORCE => 0.4, DS_FINESSE => 0.4],
			TEQ_CRIT_CHANCE	=> [DS_DEXTERITY => 0.4],
			TEQ_DEFENCE		=> [DS_RESILIENCE => 0.8],
			TEQ_DODGE_CHANCE	=> [DS_EVASIVENESS => 0.4, DS_AGILITY => 0.4],

			TEQ_ATTACK_SPEED	=> [DS_STRENGTH => 0.4, DS_FINESSE => 0.4],
			TEQ_CONSISTENCY	=> [DS_FINESSE => 0.4, DS_CONTROL => 0.4],
		],
		TEQT_MAGIC => [
			TEQ_DAMAGE		=> [DS_MAGIC => 0.8, DS_DISRUPTION => 0.5],
			TEQ_HIT_CHANCE	=> [DS_DISCIPLINE => 1],
			TEQ_CRIT_DAMAGE	=> [DS_FOCUS => 0.5],
			TEQ_CRIT_CHANCE	=> [DS_DISCIPLINE => 0.5, DS_DISRUPTION => 0.7],
			TEQ_DEFENCE		=> [DS_FOCUS => 1],
			TEQ_DODGE_CHANCE	=> [DS_FOCUS => 1],

			TEQ_ATTACK_SPEED	=> [DS_MAGIC => 1],
			TEQ_CONSISTENCY	=> [DS_FOCUS => 0.8],
		],
		TEQT_RANGED => [
			TEQ_DAMAGE		=> [DS_DEXTERITY => 0.8, DS_AGILITY => 0.5, DS_CONTROL => 0.4],
			TEQ_HIT_CHANCE	=> [DS_BALANCE => 0.5, DS_DEXTERITY => 0.5, DS_HEURISTICS => 0.2],
			TEQ_CRIT_DAMAGE	=> [DS_FINESSE => 0.5, DS_KNOWLEDGE => 0.4],
			TEQ_CRIT_CHANCE	=> [DS_INTELLECT => 0.5, DS_FINESSE => 0.7],
			TEQ_DEFENCE		=> [DS_AGILITY => 0.5, DS_RESILIENCE => 0.5],
			TEQ_DODGE_CHANCE	=> [DS_AGILITY => 1],

			TEQ_ATTACK_SPEED	=> [DS_FINESSE => 0.3, DS_CONTROL => 0.4],
			TEQ_CONSISTENCY	=> [DS_CONTROL => 0.5],
		]
	];

    function __construct($name, $MAP, $n_offset, $w_offset, $skinColour, $pantsColour, $headChar, $legsChar, $gender = GND_MALE)
    {
		$this->name = $name;

		$this->FAC	= FAC_PLAYER;

        $this->slm_head =   new SpriteElement(null, $skinColour, $headChar);
        $this->slm_hand =	new SpriteElement(null, $skinColour, '&deg;');
        $this->slm_legs =	new SpriteElement(null, $pantsColour, $legsChar);

        $this->sprite = new Sprite(
            array(
                1 => $this->slm_head,
                3 => $this->slm_hand,$this->slm_legs,$this->slm_hand)
            );

//        $this->sprite->key = 'player';

        $this->lastMoved = $_SERVER['REQUEST_TIME_FLOAT'];

        $this->MAP = $MAP;

		$this->n_offset = $n_offset;
		$this->w_offset = $w_offset;
		$this->layer	= LAYER_PLAYER;

		$this->checkpoint_n_offset	= $n_offset;
		$this->checkpoint_w_offset	= $w_offset;
		$this->checkpoint_MAP		= $MAP;

		$DSs = [
			DS_RANDOMISER => 0,
			DS_SPEED => 0.2,
			DS_SPEED_FAST => 0.2
		];

        parent::__construct($this->name, [$this->sprite], $gender, null, $DSs);

		$this->layer = LAYER_PLAYER;
    }

	function move($n_offset, $w_offset, $MAP = null)
    {
		global $map;
		global $view;

//		console_echo("Moving player to <<#fff>>{$n_offset}<>:<<#fff>>{$w_offset}<>:<<#fff>>{$MAP}<>", '#aaf');

		$requiredDelay = MIN_COOLDOWN - ($_SERVER['REQUEST_TIME_FLOAT'] - $this->lastMoved);
        if ($requiredDelay > 0)
        {
            $_SERVER['REQUEST_TIME_FLOAT'] += $requiredDelay;
            usleep($requiredDelay * 1000000);
        }

		if ($this->lastMoved + $this->__get('SPEED') > $_SERVER['REQUEST_TIME_FLOAT']) return false;

		if ($MAP && $this->MAP !== $MAP)
		{
			$this->disengage();

			$map->destroyObject($this);

			$this->MAP = $MAP;
			$this->n_offset = $n_offset;
			$this->w_offset = $w_offset;

			Map::mountPlayerMap();
		}
		else
		{

			if ($map->moveObject($this, $n_offset, $w_offset))
			{
				// Fix this - OOSENUPT!!!!
				if ($this->engagement !== null && !$this->isAdjacentTo($this->engagement))
				{
					console_echo("Disengaging {$this->engagement->name}", '#faf');
					$this->engagement->disengage();
				}

				$this->lastMoved = $_SERVER['REQUEST_TIME_FLOAT'];

				console_update_location();
			}
			else return false;
		}

		$view->forceUpdate = true;
		return true;
    }

	function alterHp ($amount)
	{
		$before = $this->hp;
		$stillAlive = parent::alterHp($amount);
		if ($before !== $this->hp) update_playerHp();
		return $stillAlive;
	}

	function alterEp ($amount, $reduceToZero = false)
	{
		$before = $this->ep;
		$successful = parent::alterEp($amount, $reduceToZero);
		if ($before !== $this->ep) update_playerEp();
		return $successful;
	}

	function alterXp ($amount)
	{
		$before = $this->xp;

		$this->xp += round($amount);
		if ($this->xp > $this->nextLevel)
		{
			$this->levelUp();
			return true;
		}
		update_playerXp();
		return false;
	}

	function levelUp ()
	{
		$this->xp -= $this->nextLevel;
		$this->nextLevel *= 1.8;
		$this->level++;

		update_playerXp(null, $this->level);
		update_thoughts("I've grown to level {$this->level}!");

		$this->getBoons();
	}

	/**
	 * Gets the Javascript associated with the player object for use in external
	 * javascript files.
	 *
	 * @return string (JavaScript)
	 */
    function getPlayerJS()
    {
        return $this->sprite->getJS();
    }

	function getWalletUpdateString()
	{
		return $this->wallet->getWalletString();
	}

	public function collide(AsObject $target, $DIR)
	{
		if ($this->engagement !== $target)
		{
			console_echo("Engaging {$target->name}", '#afa');
			$target->engage($this);
		}

		return parent::collide($target, $DIR);
	}

	function onMapChange()
	{
		$this->engagement = null;

		foreach ($this->equipped as $equiptmentIndex)
		{
			$equiptment = $this->inventory->getItemByIndex($equiptmentIndex);
			$equiptment->onMapChange();
		}
	}



	function addStatus($status)
	{
		parent::addStatus($status);
		update_statuses();
	}


	public function selectBoon ($index)
	{
		if (!isset($this->pendingBoons[$index]))
		{
			console_echo("<<#f55>>Trying to select a boon at index <<#fff>>{$index}<> but there is no boon there.<>");
			return;
		}

		$this->pendingBoons[$index]->deliver($this);

		$this->pendingBoonCount -= 1;
		if ($this->pendingBoonCount < 1)
		{
			$this->pendingBoons = [];
		}
		else
		{
			unset ($this->pendingBoons[$index]);
		}

		update_boons();
	}


	public function getBoons ()
	{
		if (count($this->used_DSs) < 3)
		{
			global $DS_typed;

			$this->used_DSs = array_keys($DS_typed);
			shuffle($this->used_DSs);
			$this->used_DSs = array_flip($this->used_DSs);
		}

		global $DS_names; console_echo("DSs use during <<#fff>>level {$this->level}<> experience gathering period:", '#afa');
		foreach ($this->used_DSs as $DS => $amount) //XXX
		{	//XXX
			console_echo("{$DS_names[$DS]}: <<#fff>>{$amount}<>", '#ffa');
		}	//XXX

		console_echo("Skills used during <<#fff>>level {$this->level}<> experience gathering period:", '#aff');
		foreach ($this->used_skills as $skill => $amount) //XXX
		{	//XXX
			console_echo("{$skill}: <<#fff>>{$amount}<>", '#faf');
		}	//XXX

		console_echo("Skills observed during <<#fff>>level {$this->level}<> experience gathering period:", '#afa');
		foreach ($this->observed_skills as $skill => $amount) //XXX
		{	//XXX
			console_echo("{$skill}: <<#fff>>{$amount}<>", '#faf');
		}	//XXX

		///////////////////////////////////
		//
		// STAT BOONS
		//
		///////////////////////////////////

		console_echo('Forming stat boons');

		arsort($this->used_DSs);
		$DS_useOrder		= array_keys($this->used_DSs);
		$DS_reverseUseOrder	= array_reverse($DS_useOrder);
		$DS_shuffledUse		= $DS_useOrder; shuffle($DS_shuffledUse);

//		foreach ($this->pendingBoons as $index => $boon)
//		{
//			if ($boon instanceof boon_stats)
//			{
//				// Get rid of all the HP and EP boosting boons.
//				if (isset($boon->DSs[DS_HP_MAX]) || isset($boon->DSs[DS_EP_MAX]))
//				{
//					unset($this->pendingBoons[$index]);
//				}
//			}
//		}

		$this->pendingBoons[$DS_useOrder[0].$DS_useOrder[1]] = new boon_stats([
			$DS_useOrder[0] => mt_rand(3, 8),
			$DS_useOrder[1] => mt_rand(3, 8),
		]);

		$this->pendingBoons[$DS_reverseUseOrder[0].$DS_reverseUseOrder[1]] = new boon_stats([
			$DS_reverseUseOrder[0] => mt_rand(10, 15),
			$DS_reverseUseOrder[1] => mt_rand(10, 15),
		]);

		$this->pendingBoons[$DS_shuffledUse[0].$DS_shuffledUse[1]] = new boon_stats([
			$DS_shuffledUse[0] => mt_rand(10, 15),
			$DS_shuffledUse[1] => mt_rand(10, 15),
		]);

		$this->pendingBoons[DS_HP_MAX] = new boon_stats([
			DS_HP_MAX => mt_rand(10, 15)
		]);

		$this->pendingBoons[DS_EP_MAX] = new boon_stats([
			DS_EP_MAX => mt_rand(10, 15)
		]);

		///////////////////////////////////
		//
		// USED SKILL BOONS
		//
		///////////////////////////////////

		console_echo('Forming skill boons');

		arsort($this->used_skills);

		$potentialBoonSkills = [];

		$all_skills = [];
		foreach ($this->skills as $skill)
		{
			$all_skills[] = $skill->key;
		}
		shuffle($all_skills);

		$all_skills = array_diff_key(array_flip($all_skills), $this->used_skills);


		if ($this->used_skills)
		{
			if (count($this->used_skills) <= 3)
			{
				foreach ($this->used_skills as $skillName => $amount)
				{
					$potentialBoonSkills[] = new $skillName (mt_rand(1, 3));
				}

			}
			else
			{
				$usedSkills = array_keys($this->used_skills);
				$most = array_shift($usedSkills);
				$least = array_pop($usedSkills);
				$other = $usedSkills(array_rand($usedSkills));

				$potentialBoonSkills[] = new $most	(mt_rand(1, 3));
				$potentialBoonSkills[] = new $least	(mt_rand(1, 3));
				$potentialBoonSkills[] = new $other	(mt_rand(1, 3));
			}
		}

		$count = 0;

		foreach ($all_skills as $skillName => $amount)
		{
//			$this->pendingBoons[$skillName] = new boon_skill(
//				new $skillName (mt_rand(1, 3))
//			);
			$potentialBoonSkills[] = new $skillName (mt_rand(1, 3));

			if (++$count >= 3) break;
		}

		foreach ($potentialBoonSkills as &$sklObject)
		{
			if ($relatedSkill = $sklObject->getRelatedSkills()) // Intentional assignment in an IF
			{
				console_echo("Checking for related skills for {$sklObject->name}.");

				foreach ($relatedSkill as $skillName => $requiredLevel)
				{
					console_echo("consider {$skillName} - level {$requiredLevel} {$sklObject->name} required");

					if (($this->skills[$sklObject->key]->level >= $requiredLevel) && percentageToBool(30))
					{
						console_echo("{$skillName} has been chosen.");
						$newSklObject = new $skillName ($sklObject->level);

						if ($this->level >= $newSklObject->requiredLevel)
						{
							console_echo("Changing {$sklObject->name} boon to a {$skillName} boon.");
							$sklObject = new $skillName ($sklObject->level);
							break;
						}
						else console_echo("Player level too low ({$newSklObject->requiredLevel} required but only have {$this->level})");
					}
					else console_echo("Insufficient skill level or bad boolean luck. Level found: {$this->skills[$sklObject->key]->level} but required: {$requiredLevel}");
				}
			}
			else console_echo("No related skills for {$sklObject->name}.");

			$this->pendingBoons[$sklObject->key] = new boon_skill($sklObject);
		}

		///////////////////////////////////
		//
		// OBSERVED SKILL BOONS
		//
		///////////////////////////////////

		console_echo('Forming observed skill boons');

		arsort($this->observed_skills);

		// Oosenupt - finish this bit.

		$this->pendingBoonCount += 2;

		$this->used_DSs		= [];
		$this->used_skills	= [];

		update_boons();
	}

	public function damageGear ($class)
	{
		$chanceToDamage = 10;

		foreach ($this->equipped as $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);

			if (is_a($equipment, $class))
			{
				if (percentageToBool($chanceToDamage)) // LUCK?
				{
					$equipment->damageItem();

					if ($equipment->durability)
					{
						update_combat("{$equipment->name} was damaged! <span class=\"fade\">({$equipment->durability}/{$equipment->durabilityMax})</span>");
					}
					else
					{
						update_combat("{$equipment->name} was <<#faa>>broken<>!");
					}
				}
			}
		}
	}

	//////////////////////////////////////////////////
	//
	//	Over-written core dude functions. Keep these at the bottom of the class
	//	so that we always know where to look for them.
	//
	//////////////////////////////////////////////////

	function takeHit (Attack $attack)
	{
		// Oosenupt - potential problematic if you have takeHit behaviours mitigating some of the damage.
		update_playerHp($this->hp - $attack->damage);

		$this->damageGear('a_eqp_apparel');

		parent::takeHit	($attack);
	}

	function strike(Attack $attack)
	{
		$this->damageGear('a_eqp_weapon');

		parent::strike($attack);
	}

	function deflect(Attack $attack)
	{
		$this->damageGear('a_eqp_shield');

		parent::deflect($attack);
	}

	function death (Attack $attack)
	{
		global $view;

//		$view->setOverlay('#f00', 0.4);

		parent::death($attack);

		$this->alterHp(floor($this->__get('HP_MAX') / 3));

		$respawnPoints = [
			[100,	93],
			[100,	107],
			[93,	100],
			[107,	100],
		];

		$chosenPoint = $respawnPoints[array_rand($respawnPoints)];

		$this->move($chosenPoint[0], $chosenPoint[1], MAP_DS_GRUBREGION);
		$view->forceUpdate = true;
	}

	function kill(Attack $attack)
	{
		parent::kill($attack);

		$levelBefore = $this->level;
		$this->alterXp($attack->target->experience * ($attack->target->level / $this->level));
		if ($this->level > $levelBefore) $attack->leveled = true;
	}

	public function __debugInfo()
	{
		return parent::__debugInfo() + [
			'skills' => $this->skills,
			'passives' => $this->passives,
		];
	}

	public function onLoseItem(Item $item)
	{
		parent::onLoseItem($item);

		if ($this->engagement && $this->engagement instanceof NPC && isset($this->engagement->NPCIs['npci_sell']))
		{
			$this->engagement->NPCIs['npci_sell']->updatePanel($item);
		}
	}

	public function onGainItem(Item $item)
	{
		parent::onGainItem($item);

//		if ($this->engagement && $this->engagement instanceof NPC && isset($this->engagement->NPCIs['npci_sell']))
//		{
//			if (!isset($this->engagement->NPCIs['npci_sell']->mask) || $this->engagement->NPCIs['npci_sell']->mask->compare($item))
//			{
//				$this->engagement->NPCIs['npci_sell']->updatePanel($item);
//			}
//		}
	}
}

/*

$fallingSprite = new Sprite([
	[
		1 => new SpriteElement(null, '#c94', 'o'),
		3 => new SpriteElement(null, '#c94', '&deg;'),
		4 => new SpriteElement(null, '#c94', '&#x039b;'),
		5 => new SpriteElement(null, '#c94', '&deg;'),
	],
	[
		0 => new SpriteElement(null, '#c94', '&deg;'),
		1 => new SpriteElement(null, '#c94', 'o'),
		3 => new SpriteElement(null, '#c94', '7'),
		5 => new SpriteElement(null, '#c94', '&deg;'),
	],
	[
		0 => new SpriteElement(null, '#c94', '&#x221a;'),
		1 => new SpriteElement(null, '#c94', '&deg;'),
		3 => new SpriteElement(null, '#c94', '&deg;'),
		4 => new SpriteElement(null, '#c94', 'o'),
	],
	[
		1 => new SpriteElement(null, '#c94', 'V'),
		3 => new SpriteElement(null, '#c94', '&deg;'),
		4 => new SpriteElement(null, '#c94', 'o'),
		5 => new SpriteElement(null, '#c94', '&deg;'),
	],
	[
		1 => new SpriteElement(null, '#c94', '&deg;'),
		2 => new SpriteElement(null, '#c94', 'L'),
		4 => new SpriteElement(null, '#c94', 'o'),
		5 => new SpriteElement(null, '#c94', '&deg;'),
	],
	[
		0 => new SpriteElement(null, '#c94', '&deg;'),
		1 => new SpriteElement(null, '#c94', 'o'),
		4 => new SpriteElement(null, '#c94', '&deg'),
		5 => new SpriteElement(null, '#c94', '&#x0490;'),
	],
]);


*/