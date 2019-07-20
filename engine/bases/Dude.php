<?php

abstract class Dude extends AsObject
{
	use			SkillCapability;
	use			StatusCapability;

    public		$hp			= 100;
	public		$ep			= 100;

	public		$level		= 1;

	public		$FAC;

    public		$gender		= null;
	public		$speechFile;
	public		$nextSpeak	= 0;

//    private		$inventory;
    public		$inventory;
//	public		$SRZ_inventory;
	public		$wallet;

	public		$equipped	= [];

	protected	$DSs;
	protected	$DMGs			= [DMG_TRAUMA => 5];
	protected	$DMGs_def		= [];

	public		$lvl_DSs		;
	public		$lvl_DMGs		;
	public		$lvl_DMG_defs	;

	public		$default_DMGDL	= DMGDL_BLUNT;
	public		$DMGDL			= DMGDL_BLUNT;

	public		$attack;

	public		$canPush		= false;
	public		$canOpenDoors	= false;

	public		$speechColour;

	// HANDLE WITH CARE!
	// In order to empower the SPE files, this variable was added to house objects that
	// might be needed during interactions.
	// Exmaples of use: Stick a quest in here so that the quest object is ready to be assigned.
	// The quest might involve recieving an item. Put that in here, too then grab it out in the SPE.
	// Basically, if you need something to exist in the SPE file but don't know where to put it, put it
	// in here with a unique and descriptive key (e.g., "spiderScourgeQuest").
	// If this ends up being a giant fuckup, I'll get rid of this variable.
	public		$speAnnex		= [];

	public		$TEQT		= TEQT_MELEE;
	public		$TEQT_def	= TEQT_MELEE;

	public		$technique = [];

	public		$TPL_passables =
	[
		TPL_OPENGROUND,
		TPL_STAIRS
	];


	private $default_technique = [
		TEQT_MELEE => [
			TEQ_DAMAGE		=> [DS_STRENGTH => 0.6, DS_FORCE => 0.5],
			TEQ_HIT_CHANCE	=> [DS_CONTROL => 1],
			TEQ_CRIT_DAMAGE	=> [DS_FORCE => 1, DS_FINESSE => 1],
			TEQ_CRIT_CHANCE	=> [DS_DEXTERITY => 0.2],
			TEQ_DEFENCE		=> [DS_RESILIENCE => 1],
			TEQ_DODGE_CHANCE	=> [DS_EVASIVENESS => 0.2, DS_AGILITY => 0.2],

			TEQ_ATTACK_SPEED	=> [DS_STRENGTH => 0.7, DS_FINESSE => 0.3, DS_INERTIA => -0.3],
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

	private $TEQbiasCache = [];

	public function __construct($name, $spriteSet, $GND = null, $speechFile = null, $dudeStats = null)
	{
		global $DS_defaults;

		// This DS code looks like shit but I can't think how to improve it right now.
		if (!is_array($dudeStats)) $dudeStats = [];

		if (isset($this->DSs)) $dudeStats = $dudeStats + $this->DSs;

		$this->DSs = $dudeStats + $DS_defaults;

		if ($this->handicap !== null)
		{
			$handicap = $this->handicap;
			$randomiser = $this->randomiser / 100;

			$extraDSs = [
				DS_HP_MAX,
				DS_EP_MAX,
				DS_EXPERIENCE
			];

			foreach ($this->DSs as $DS => &$value)
			{
				if (($DS >= 100 || in_array($DS, $extraDSs)) && !isset($dudeStats[$DS]))
				{
					$value = ($value * $handicap) + mt_rand(0 - $value * $randomiser, $value * $randomiser);
				}
			}
		}

		$this->technique = $this->technique + $this->default_technique;
		foreach ($this->default_technique as $TEQT => $technique)
		{
			$this->technique[$TEQT] = $this->technique[$TEQT] + $technique;
		}

		unset($this->default_technique);

		$this->gender		= isset($GND) ? $GND : GND_MALE;

		$this->hp			= $this->DSs[DS_HP_MAX];

		$speechFileName = strtolower(str_replace(' ', '_', $name));
		if (isset($speechFile))
		{
			$this->speechFile = str_replace('.spe', '', $speechFile);
			console_echo("<<#afa>>{$name}<> is using the speech file <<#aaf>>\"{$this->speechFile}.spe\"<> on account of explicit assignment.", '#fff');
		}
		elseif(file_exists("{$GLOBALS['rootPath']}content/speech/{$speechFileName}.spe"))
		{
			$this->speechFile = $speechFileName;
			console_echo("<<#afa>>{$name}<> is using the speech file <<#aaf>>\"{$this->speechFile}.spe\"<> on account of name alloction.", '#fff');
		}
		else
		{
			$this->speechFile = '_default';
			console_echo("<<#afa>>{$name}<> is using the speech file <<#aaf>>\"{$this->speechFile}.spe\"<> because it wasn't given anything else.", '#fff');
		}

		$this->inventory	= new Inventory($this);
		$this->SRZs[]		= 'inventory';

		$this->wallet		= new Wallet($this);

		$this->attack		= new Attack($this);

		$this->default_DMGDL = $this->DMGDL;


		if ($spriteSet instanceof Sprite)
		{
			$spriteSet = [$spriteSet];
		}
		if (!is_array($spriteSet)) $spriteSet = [];


//		if (!isset($spriteSet[SPRI_DEFAULT]))	$spriteSet[SPRI_DEFAULT]	= self::getDudeSprite($GND);
		if (!$spriteSet)
		{
			$spriteSet = [];
			$spriteSet[SPRI_DEFAULT]	= self::getDudeSprite($GND);
		}
		if (!isset($spriteSet[SPRI_DEFAULT]))
		{
			$spriteSet[SPRI_DEFAULT] = reset($spriteSet);
		}
		if (!isset($spriteSet[SPRI_CORPSE]))	$spriteSet[SPRI_CORPSE]		= self::getCorpseSprite($spriteSet[SPRI_DEFAULT]);

		parent::__construct($name, $spriteSet, LAYER_DUDE);


		$this->speechColour	= tintByMax($this->spriteSet[SPRI_DEFAULT]->getBodyColour(), 10); // This function doesn't even fucking work
	}


	public function __get($name)
	{
		if (isset($this->$name))
		{
			if (is_array($this->$name))
			{
				return $this->getDeepArray($name);
			}
			else
			{
				return parent::__get($name);
			}
		}
		else
		{
			return $this->getDS($name);
		}
	}

	public function __set($name, $value)
	{
		console_echo("Attepmting SET on a <<#fff>>{$this->name}'s {$name}<>.", '#afd');

		if (isset($this->$name))
		{
			$this->$name = $value;
		}
		else
		{
			$upname = str_replace(' ', '_', strtoupper($name));
			$DS_name = "DS_{$upname}";

			console_echo("constant: {$DS_name}");

			if (defined($DS_name))
			{
				$constant = constant($DS_name);

				$this->DSs[$constant] = $value;
				global $DS_names; console_echo("Setting <<#fff>>{$this->name}'s {$DS_names[$constant]}<> to {$value}.", '#afd');
			}
			else	//XXX
			{		//XXX
				console_echo("There's no dude stat bound to the constant <<#fff>>\"{$DS_name}\"<>!", '#faa');
				console_echo("Setting <<#fff>>\"{$name}\"<> as a normal variable.", '#fda');
				$this->$name = $value;
			}		//XXX
		}
	}

	public function __clone()
	{
		parent::__clone();
	}

	public function getDS ($DS_name, $shallow = false)
	{
		console_echo("Attempting GET on a dude ({$DS_name})", '#fda', CNS_DSs);

		if (is_numeric($DS_name))
		{
			$constant = $DS_name;
			if (!isset($this->DSs[$constant]))
			{
				console_echo("<<#f55>>There is no DS assigned to number {$constant}!<>");
				return null;
			}
		}
		else
		{
			$upname = str_replace(' ', '_', strtoupper($DS_name));
			$constant = constant("DS_{$upname}");
		}

		console_echo("constant: {$constant}", null, CNS_DSs);

		if (isset($constant) && isset($this->DSs[$constant]))
		{

			$value = $this->DSs[$constant];
			$values = [$value];

			if (!$shallow)
			{
				if (isset($this->lvl_DSs[$constant])) $values[] = $this->lvl_DSs[$constant] * $this->level;

				foreach($this->equipped as $index)
				{
					$equipment = $this->inventory->getItemByIndex($index);
					if (isset($equipment->DSs[$constant]))
					{
						$values[] = $equipment->DSs[$constant];
					}
				}

				foreach ($this->statuses as $arrayKey => $status)
				{
					if (isset($status->DSs[$constant]))
					{
						console_echo("Sourcing <<#fff>>{$DS_name}<> from a status.", '#faf', CNS_DSs);
						$values[] = $status->DSs[$constant];
					}
				}

				return valueListToValue($values);
			}

			return $value;
		}
		else
		{
			console_echo("<<#f55>>There is no DS_{$upname}!<>");
			return null;
		}

	}

	public function getDeepArray($name)
	{
		$shallowArray = $this->$name;

			$deepArrays = [];

			$lvl_array = "lvl_{$name}";
			if (isset($this->$lvl_array))
			{
				$lvl_array = $this->$lvl_array;
			}
			else
			{
				$lvl_array = [];
			}

			foreach ($shallowArray as $key => $val)
			{
				if (isset($lvl_array[$key])) $val += $lvl_array[$key] * $this->level;

				$deepArrays[$key][] = $val;
			}

			foreach($this->equipped as $index)
			{
				$equipment = $this->inventory->getItemByIndex($index);

				// Make sure it exists before you try to use it.
				if (isset($equipment->$name))
				{
					$eqpArray = $equipment->$name;

					foreach ($eqpArray as $key => $val)
					{
						$deepArrays[$key][] = $val;
					}
				}
			}


			foreach ($this->statuses as $arrayKey => $status)
			{
				foreach ($status->$name as $key => $val)
				{
					$deepArrays[$key][] = $val;
				}
			}

			$aggregatedArray = [];
			foreach ($deepArrays as $key => $val)
			{
				$aggregatedArray[$key] = valueListToValue($deepArrays[$key]);
			}

			console_echo($name, '#fda', CNS_DSs);

			return $aggregatedArray;
	}

	public function getShallowArray($name)
	{
		if (isset($this->$name) && is_array($this->$name)) return $this->$name;
	}

	public function collide(AsObject $target, $DIR)
	{
		global $FAC_standing;

		if (
			$target instanceof Dude &&
			isset($FAC_standing[$this->FAC][$target->FAC]) &&
			$FAC_standing[$this->FAC][$target->FAC] < 0
		)
		{
			$this->doAttack($target);
		}

		return parent::collide($target, $DIR);
	}


	public function hasItem($item)
	{
		return $this->inventory->hasItem($item) !== false;
	}

	function equip($equipment)
	{
		global $DS_names;

		if ($equipment instanceof Equipment)
		{
			$inv_slot = $this->inventory->findItem($equipment);
		}
		elseif (is_integer($equipment))
		{
			$inv_slot = $equipment;
			$equipment = $this->inventory->getItemByIndex($inv_slot);
		}

		$notMet = [];

		foreach ($equipment->DSs_req as $DS => $min)
		{
			if ($this->$DS_names[$DS] < $min) $notMet[] = $DS_names[$DS];
		}

		if (!empty($notMet))
		{
			update_thoughts('Insufficient '. implode(', ', $notMet). " to equip {$equipment->name}!");
			return false;
		}

		if (isset($this->equipped[$equipment->EQP])) $this->unequip($equipment->EQP);

		$this->equipped[$equipment->EQP] = $inv_slot;

		$equipment->isEquipped = true;

		if (isset($equipment->spriteSet[SPRI_OVERSPRITE]))
		{
			$this->updateEquipmentSprite();
			$this->setSPRI(SPRI_GEAR);
		}
		else	//XXX
		{		//XXX
			console_echo("No oversprite for {$equipment->name}", '#fda');
		}		//XXX

		$updateStats		= false;
		$updateReadiness	= false;

		if (isset($equipment->DMGDL))
		{
			$this->DMGDL = $equipment->DMGDL;
			$updateReadiness = true;
		}

		$equipment->onEquip();

//		update_stats(); // Oosenupt - fix this; trim down the number of stats updated


		update_thoughts("{$equipment->name} equipped!");

		$this->attack = new Attack($this);

		if ($this === $GLOBALS['player'])
		{
			console_echo('Equipment change on player detected.', '#afa');
			update_items($equipment);
			update_stats(array_keys($equipment->DSs));
			update_readiness();
			update_playerHp();
			update_playerEp();

			if ($equipment->EQP === EQP_HAND || $equipment->EQP === EQP_OFFHAND)
			{
				console_echo('update_technique');
				update_technique();
			}
		}
		console_echo('Equipment change complete.', '#aff');
	}


	function unequip($EQP)
	{
		if ($EQP instanceof Equipment) $EQP = $EQP->EQP;
		$equipment = $this->getItemByEQP($EQP);

		unset($this->equipped[$EQP]);

		$equipment->isEquipped = false;
		if (isset($equipment->spriteSet[SPRI_OVERSPRITE]))
		{
			$this->updateEquipmentSprite();
			$this->setSPRI(SPRI_GEAR);
		}

		$updateReadiness = false;

		if (isset($equipment->DMGDL))
		{
			$this->DMGDL = $this->default_DMGDL;
			$updateReadiness = true;
		}

		$equipment->onUnequip();

		if ($updateReadiness) update_readiness();

		update_thoughts("{$equipment->name} unequipped.");

		$this->attack = new Attack($this);

		if ($this === $GLOBALS['player'])
		{
			update_items($equipment);
			update_stats(array_keys($equipment->DSs));
			update_readiness();
			update_playerHp();
			update_playerEp();

			if ($equipment->EQP === EQP_HAND || $equipment->EQP === EQP_OFFHAND)
			{
				update_technique();
			}
		}
	}

	function getItemByEQP($EQP)
	{
		if (isset($this->equipped[$EQP]))
		{
			return $this->inventory->getItemByIndex($this->equipped[$EQP]);
		}
		else return false;
	}

	function updateEquipmentSprite ()
	{
		global $view;

		console_echo('Updating EQP sprite', null, CNS_SPRITE);		//XXX
		$sprite = new Sprite($this->spriteSet[SPRI_DEFAULT]->frames);
		foreach ($this->equipped as $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);
			if (isset($equipment->spriteSet[SPRI_OVERSPRITE]))
			{
				$sprite = $sprite->augment($equipment->spriteSet[SPRI_OVERSPRITE]);
				console_echo('Applying this overSprite: ' . console_sprite($equipment->spriteSet[SPRI_OVERSPRITE]), null, CNS_SPRITE);
			}
			else	//XXX
			{		//XXX
				console_echo("No oversprite for {$equipment->name}", null, CNS_SPRITE);
			}		//XXX
		}

		if (isset($this->spriteSet[SPRI_GEAR]) && $this->spriteSet[SPRI_GEAR]->equals($sprite))
		{
			console_echo('Old gear sprite is identical to new gear sprite. Pretend nothing happened.', null, CNS_SPRITE);
			console_echo(console_sprite($sprite) . ' === ' . console_sprite($this->spriteSet[SPRI_GEAR]), null, CNS_SPRITE);
			return;
		}

		console_var_dump($sprite);

		unset($this->spriteSet[SPRI_GEAR]);
		$this->spriteSet[SPRI_GEAR] = $view->addClientSprite($sprite);


		console_var_dump($this->spriteSet[SPRI_GEAR]);

		$this->currentSPRI = null;

//		return $this->spriteSet[SPRI_GEAR];
//		return $sprite;
	}

	function alterHp($amount)
	{
		$hpMax = $this->__get('hp_max'); // Annoying

//		$this->hp += $amount;
		$this->hp = sa($this->hp, $amount);
		if ($this->hp > $hpMax) $this->hp = $hpMax;
		if ($this->hp <= 0) $this->hp = 0;

		return $this->hp > 0;
	}

	function alterEp($amount, $reduceToZero = false)
	{
		$epMax = $this->__get('ep_max'); // Annoying
		$amount = $amount;

		$this->ep += $amount;
		if ($this->ep > $epMax) $this->ep = $epMax;
		if ($this->ep <= 0)
		{
			if (!$reduceToZero) $this->ep -= $amount;
			return false;
		}
		return true;
	}

	function regenerate ()
	{
		$hp = $this->__get('regeneration') * ($_SERVER['REQUEST_TIME_FLOAT'] - $this->lastUpdated);
		$this->alterHp($hp);

		$ep = $this->__get('recharge') * ($_SERVER['REQUEST_TIME_FLOAT'] - $this->lastUpdated);
		$this->alterEp($ep);
	}




	function doAttack (Dude $target)
	{
		$this->attack->attacker = $this;
		$this->attack->execute($target);
	}

	function speak ($speech_or_SPSI, $context = null, $force = false)
	{
		global $rootPath;
		global $player;
		global $map;

		if ($this->speAnnex)
		{
			console_echo('What\'s in the speAnnex?', '#faf');
			console_class_list($this->speAnnex, '#faa');
			console_echo('Let\'s hope it\'s all in there with good reason, ey?', '#faf');
		}

		if ($_SERVER['REQUEST_TIME_FLOAT'] < $this->nextSpeak && !$force) return;

		if (is_numeric($speech_or_SPSI))
		{
			$SPSI = $speech_or_SPSI;

			if (is_array($context))
			{
				foreach ($context as $name => $val)
				{
					console_echo("{$name}: <<#fff>>{$val}<>");
					$$name = $val;
				}
			}

			$speech = [];

			include "{$rootPath}content/speech/{$this->speechFile}.spe";

			if (empty($speech)) include "{$GLOBALS['rootPath']}content/speech/_default.spe";

			$compiledSpeech = '';
			// $speech is set inside the .spe file
			// The nomclature in this section sucks ass. My apologies.
			if (!empty($speech))
			{
				$phrase = $speech[array_rand($speech)];

				foreach ($phrase as $line)
				{
					$compiledSpeech .= (is_array($line) ? $line[array_rand($line)] : $line);
				}
			}
			else
			{
				$compiledSpeech = 'Oh dear, I\'ve completely forgotten what I was going to say...';
				console_echo("Missing speech file \"{$this->speechFile}\" for {$this->name}", '#faa');		//XXX
			}
		}
		else
		{
			$compiledSpeech = $speech_or_SPSI;
		}

		$displayName = '';
		if ($this->gender !== null)
		{
			$displayName = ($this->gender == GND_MALE ? '&#x2642; ' : '&#x2640; ');
		}
		$displayName .= $this->name;

		update_conversation($displayName, $compiledSpeech, $this->speechColour);
		$this->nextSpeak = $_SERVER['REQUEST_TIME_FLOAT'] + SPEECH_DELAY;
	}




	public function getTEQbiases($TEQT = null, $forUpdate = false)
	{
		global $DS_names;
		global $TEQ_names;

		$values = [];

		if (!isset($TEQT)) $TEQT = TEQT_MELEE;

		if (!$forUpdate && isset($this->TEQbiasCache[$_SERVER['REQUEST_TIME_FLOAT']]))
		{
			if (isset($this->TEQbiasCache[$_SERVER['REQUEST_TIME_FLOAT']][$TEQT]))
			{
				console_echo('Returning cached TEQ biases!', '#afa');
				return $this->TEQbiasCache[$_SERVER['REQUEST_TIME_FLOAT']][$TEQT];
			}
		}
		else
		{
			console_echo('No TEQ biases have been cached <<#faa>>:(<>', '#fda');
			$this->TEQbiasCache = [];
		}

		$TEQTs = ['default' => $this->technique[$TEQT]];

		$hand = $this->getItemByEQP(EQP_HAND);
		$offHand = $this->getItemByEQP(EQP_OFFHAND);

		if ($hand && isset($hand->technique[$TEQT]))		$TEQTs[get_class($hand)]	= $hand->technique[$TEQT];
		if ($offHand && isset($offHand->technique[$TEQT]))	$TEQTs[get_class($offHand)] = $offHand->technique[$TEQT];

		// For every aspect of a technique (damage, accuracy etc.), we need to
		// search for how to source its values and then get the values themselves.
		foreach ($TEQ_names as $TEQ => $name)
		{
			if (!$forUpdate) $values[$TEQ] = 0;

			// There are three (and a half) potential sources of technique: item in hand, item
			// in off hand and the base dude. A dude can also have its own technique points that only
			// work for specific item classes.
			foreach ($TEQTs as $className => $TEQTarray)
			{
				// If the TechniqueType that we're examining contribues to this Technique aspect,
				// get all its values out.
				if (isset($TEQTarray[$TEQ]))
				{
					// For every stat inside the Technique aspect that is used to source its bias value,
					// pull it out, pmultiply it by the relevant dude stat and add it to the finished array.
					foreach ($TEQTarray[$TEQ] as $DS => $multiplier)
					{
						if ($forUpdate)
						{
							if (!isset($values[$DS])) $values[$DS] = [];
							$values[$DS][$TEQ] = isset($values[$DS][$TEQ]) ? $values[$DS][$TEQ] + $multiplier : $multiplier;
						}
						else
						{
							$values[$TEQ] += ($this->__get($DS_names[$DS]) * $multiplier);
						}
					}

					// This is where we check if the dude we're currently evaluating has an item-specific
					// set of technique contributors.
					if (isset($this->technique[$className]) && isset($this->technique[$className][$TEQT]))
					{
						$classTEQT = $this->technique[$className][$TEQT];
					}

					// If they do, treat them in much the same way.
					if (isset($classTEQT))
					{
						foreach ($classTEQT[$TEQ] as $DS => $multiplier)
						{
							if ($forUpdate)
							{
								if (!isset($values[$DS])) $values[$DS] = [];
								$values[$DS][$TEQ] = isset($values[$DS][$TEQ]) ? $values[$DS][$TEQ] + $multiplier : $multiplier;
							}
							else
							{
								$values[$TEQ] += ($this->__get($DS_names[$DS]) * $multiplier);
							}
						}
					}

					if (isset($values[$TEQ]))
					{
						$finalMultiplier = $this->__get($TEQ_names[$TEQ]);

						if ($finalMultiplier)
						{
							$values[$TEQ] = sa($values[$TEQ], "{$finalMultiplier}%");
						}

//						console_echo("Oosenupt - <<#fff>>{$finalMultiplier}<>", '#aaf');
					}
				}
			}
		}
		// Fuck me, what an abortion of a loop.

		$this->TEQbiasCache[$_SERVER['REQUEST_TIME_FLOAT']][$TEQT] = $values;

		return $values;
	}

	////////////////////////////
	//
	//		STATIC SPRITE FUNCTIONS
	//
	////////////////////////////

	public static function getDudeSprite($GND = null, $char_head = null, $char_body = null, $col_head = null, $col_body = null)
	{
		if (!$GND === null)
		{
			$genders = [GND_MALE, GND_FEMALE];
			$GND = $genders[array_rand($genders)];
		}

		if (!$char_head)
		{
			if ($GND === GND_MALE)
			{
				$headChars = ['o', 'c', 'e', 'Q', 'b', 'd', 'O', '6', '&#x03b4;', '&#x03c3;'];
			}
			else
			{
				$headChars = ['g', 'p', 'q', '9'];
			}

			$char_head = $headChars[array_rand($headChars)];
		}

		if (!$char_body)
		{
			if ($GND === GND_MALE)
			{
				$bodyChars = ['X', 'n', '&Pi;', '&Omega;', '&#x2229;', '&#x041f;', '&#x039b;', '&#x005e;'];
			}
			else
			{
				$bodyChars = ['&#x0434;', '&#x0414;', 'A', '&#x0394;'];
			}

			$char_body = $bodyChars[array_rand($bodyChars)];
		}

		if (!$col_head)
		{
			$skinReducer = rand(1, 5);
			$r = (dechex(15 - $skinReducer));
			$g = (dechex(15 - (2 * $skinReducer)));
			$b = (dechex(15 - (3 * $skinReducer)));
			$col_head = "#{$r}{$g}{$b}";
		}

		if (!$col_body)
		{
			if ($GND === GND_MALE)
			{
				$col_body = '#' . dechex(rand(0, 6)) . dechex(rand(0, 6)) . dechex(rand(0, 10));
			}
			else
			{
				$col_body = '#' . dechex(rand(5, 15)) . dechex(rand(5, 15)) . dechex(rand(0, 9));
			}
		}

		return new Sprite(
			array(
				1 => new SpriteElement(null, $col_head, $char_head),

				3 => new SpriteElement(null, $col_head, '&deg;'),
				4 =>new SpriteElement(null, $col_body, $char_body),
				5 =>new SpriteElement(null, $col_head, '&deg;')
			)
		);
	}

	public static function getCorpseSprite(Sprite $sprite = null, $col_blood = null)
	{
		if (!$col_blood)	$col_blood = '#f00';

		if ($sprite)
		{
			$spr_person = clone $sprite;
		}
		else
		{
			$spr_person = self::getDudeSprite();
		}

		if (isset($spr_person->frames[0][1]))
		{
			$head = $spr_person->frames[0][1];
		}
		else
		{
			$index = array_rand($spr_person->frames[0]);

			$head = $spr_person->frames[0][$index];
			unset($spr_person->frames[0][$index]);
		}

		if (isset($spr_person->frames[0][4]))
		{
			$body = $spr_person->frames[0][4];
		}
		else
		{
			$index = array_rand($spr_person->frames[0]);

			$body = $spr_person->frames[0][$index];
			unset($spr_person->frames[0][$index]);
		}

		$entrailsChars = [
			'~',
			'&#x03c9;',
			'&#x03be;',
			'&#x03b6;',
			'&#x2665;',
		];

		$blood = [
			'.',
			':',
			'&bull;',
			'&#x0387;',
		];

		$headPos = rand(0,1);

		return new Sprite([
			new SpriteElement(null, $col_blood, $blood[array_rand($blood)]),
			new SpriteElement(null, $col_blood, $blood[array_rand($blood)]),
			new SpriteElement(null, $col_blood, $blood[array_rand($blood)]),

			($headPos ? $head : $body ),
			new SpriteElement(null, $col_blood, $entrailsChars[array_rand($entrailsChars)]),
			($headPos ? $body : $head ),
		]);
	}

	////////////////////////////
	// These are the events as they will be called from outside. These can be
	// altered but at this time, I can't think why they should.
	////////////////////////////

	function attack(Attack $attack)
	{
		$this->onAttack($attack);

		foreach ($this->equipped as $EQP => $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);
			$equipment->onAttack($attack);
		}
	}

	function miss(Attack $attack)
	{
		$this->onMiss($attack);

		foreach ($this->equipped as $EQP => $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);
			$equipment->onMiss($attack);
		}
	}

	function strike(Attack $attack)
	{
		$this->onStrike($attack);

		foreach ($this->equipped as $EQP => $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);
			$equipment->onStrike($attack);
		}
	}

	function kill(Attack $attack)
	{
		$this->onKill($attack);

		foreach ($this->equipped as $EQP => $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);
			$equipment->onKill($attack);
		}
	}

	function defend(Attack $attack)
	{
		$this->onDefend($attack);

		foreach ($this->equipped as $EQP => $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);
			$equipment->onDefend($attack);
		}
	}

	function deflect(Attack $attack)
	{
		$this->onDeflect($attack);

		foreach ($this->equipped as $EQP => $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);
			$equipment->onDeflect($attack);
		}
	}

	function takeHit(Attack $attack)
	{
		$this->onTakeHit($attack);

		foreach ($this->equipped as $EQP => $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);
			$equipment->onTakeHit($attack);
		}
	}

    function death(Attack $attack)
	{
		if ($this instanceof Player)
		{
			return; // Oosenupt - This whole function will probably be overridden in Player class anyway
		}
		else
		{
			$this->wallet->dumpIntoInventory();
		}

		console_echo('Before onDeath');
		$this->onDeath($attack);
		console_echo('After onDeath');

		foreach ($this->equipped as $EQP => $index)
		{
			$equipment = $this->inventory->getItemByIndex($index);
			$equipment->onDeath($attack);
		}

		console_echo("Before death's destroy. ID:{$this->id}");
		$this->destroy();
		console_echo('After death\'s destroy');
	}

	///////////////////////////////////////////////////////
	// If anything needs to intercept any of these events, it should happen in
	// the functions above, not the "on" functions. They should all be the same
	// as each other.
	///////////////////////////////////////////////////////

	final function onAttack		(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	final function onMiss		(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	final function onStrike		(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	final function onKill		(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }

	final function onDefend		(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	final function onDeflect	(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	final function onTakeHit	(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
	final function onDeath		(Attack $attack)	{ $this->executeBehaviours(__FUNCTION__, $attack); }
}

abstract class DudeBehaviour extends ObjectBehaviour
{
	public $onAttack			= null;
	public $onMiss				= null;
	public $onStrike			= null;
	public $onKill				= null;

	public $onDefend			= null;
	public $onDeflect			= null;
	public $onTakeHit			= null;
	public $onDeath				= null;

	public function onAttack	(Attack $attack) { }
	public function onMiss		(Attack $attack) { }
	public function onStrike	(Attack $attack) { }
	public function onKill		(Attack $attack) { }

	public function onDefend	(Attack $attack) { }
	public function onDeflect	(Attack $attack) { }
	public function onTakeHit	(Attack $attack) { }
	public function onDeath		(Attack $attack) { }
}