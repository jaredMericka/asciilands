<?php

class Attack
{
	public $attacker;
	public $target = null;

	protected $base_DMGDL;
	protected $base_DMGs = [];

	public $TEQT;

	public $cooldown;
	public $readyTime;

	public $isBaseAttack = true;
	public $noVariation	= false;

	// The difficulty stat is used to calculate biases on teqnique variables that
	// don't have an opposite (e.g., attack speed). I imagine that the way in which
	// this stat is calculated will chnage a lot during the balancing process.
	public $difficulty;

	// For these values: TRUE = always, FALSE = never, NULL = no change
	public $alwaysHit = null;
	public $alwaysCrit = null;
	public $alwaysReady = null;

	// TRANSIENT VARIABLES:
	// These get reset with every execution.

	public $damage	= 0;

	// What needs to be here?

	// See and edit total damage
	public $damage_modifiers = [];
	// See and edit specific damage types
	public $DMGs_modifiers = [];
	public $DMGs_def_modifiers = [];
	// See and edit delivery method
	public $DMGDL;
	public $DMGs = [];
	public $DMGs_def = [];
	// See and edit the chance to hit
	public $hitChance_modifiers = [];
	public $dodgeChance_modifiers = [];
	// See and edit whether or not the attack is a crit
	public $isCrit; // Boolean
	// See and edit chance to crit
	public $critChance_modifiers = [];
	// See and edit dude stats of both parties
	public $attackerDSs_modifers = [];
	public $targetDSs_modifers = [];
	// See and edit attack config of both parties

	// What needs to NOT be in here?
	// Direct access to the calculated bias variables

	// Other read-only attributes for use in quest determiners
	public $hit;		// Did the attack hit?
	public $kill;		// Did the attack kill?
	public $leveled;	// Did the attack cause a level up?

	public function __construct(Dude $attacker, $DMGDL = null, $DMGs = null, $TEQT = null)
	{
		global $DMG_DMGDL_names;

		$this->attacker	= $attacker;

		if (!isset($TEQT)) $TEQT = $attacker->TEQT;
		$this->TEQT = $TEQT;

		if (isset($DMGDL) && isset($DMGs))
		{
			$this->isBaseAttack = false;
			$this->base_DMGDL	= $DMGDL;
			$this->base_DMGs	= $DMGs;
		}
		else
		{
			$this->isBaseAttack = true;
			$this->base_DMGDL	= $attacker->DMGDL;
			$this->base_DMGs	= $attacker->DMGs;
		}

		$DMGDL_mod = $attacker->{"DMGDL_{$DMG_DMGDL_names[$this->base_DMGDL]}"} . '%';

		foreach ($this->base_DMGs as $DMG => $value)
		{
			$this->base_DMGs[$DMG] = sa($this->base_DMGs[$DMG], $attacker->{"DMG_{$DMG_DMGDL_names[$DMG]}"} . '%'); // Terrible
			$this->base_DMGs[$DMG] = sa($this->base_DMGs[$DMG], $DMGDL_mod);
		}

		if ($weapon = $attacker->getItemByEQP(EQP_HAND)) // Forget the warning, this is on purpose.
			$this->difficulty = $weapon->level * 10;		// Oosenupt - this hasn't be thought through properly and balancing may require additional attention here.
		else
			$this->difficulty = 100 + $attacker->level;

		$this->readyTime = $_SERVER['REQUEST_TIME_FLOAT'];

		global $DMGDL_names;	//XXX
		console_echo('Attack details:', '#faf', CNS_ATTACK);
		console_echo("Attacker: {$this->attacker->name}", '#fff', CNS_ATTACK);
		console_echo('Total damage: ' . array_sum($this->base_DMGs), '#fff', CNS_ATTACK);
		console_echo("cooldown: {$this->cooldown}", '#fff', CNS_ATTACK);
		console_echo("Damage delivery: {$DMGDL_names[$this->base_DMGDL]}", '#fff', CNS_ATTACK);
	}

	function __get($name)
	{
		return $this->$name;
	}

	public function execute(AsObject $target)
	{
		global $player;

		if (!$this->alwaysReady === true && ($this->alwaysReady === false || $_SERVER['REQUEST_TIME_FLOAT'] < $this->readyTime))
		{
			console_echo('Attack not ready.', null, CNS_ATTACK);
			return;
		}

		console_echo("{$this->attacker->name} is attacking {$target->name}!", '#aaf', CNS_ATTACK);

		// Identify target
		$this->target = $target;
		$this->DMGDL = $this->base_DMGDL;
		$this->DMGs = $this->base_DMGs;

		// Determine method of attack
		if ($target instanceof Dude)
		{
			$this->attackDude();
		}

		if ($this->attacker === $player)
		{
			$usedDSs = array_merge(
				array_keys($this->attacker->technique[$this->TEQT][TEQ_DAMAGE]),
				array_keys($this->attacker->technique[$this->TEQT][TEQ_HIT_CHANCE]),
				array_keys($this->attacker->technique[$this->TEQT][TEQ_CRIT_DAMAGE]),
				array_keys($this->attacker->technique[$this->TEQT][TEQ_CRIT_CHANCE])
			);
		}
		elseif ($this->target === $player)
		{
			$usedDSs = array_merge(
				array_keys($this->target->technique[$this->TEQT][TEQ_DEFENCE]),
				array_keys($this->target->technique[$this->TEQT][TEQ_DODGE_CHANCE])
			);
		}


		if (isset($usedDSs))
		{
			global $DS_names; //XXX
			console_echo("DSs used by <<#fff>>{$player->name}<> in this attack:", '#faf', CNS_ATTACK);

			foreach ($usedDSs as $DS)
			{
				console_echo("{$DS_names[$DS]}", '#ffa', CNS_ATTACK);

				if (isset($player->used_DSs[$DS])) $player->used_DSs[$DS] += 1;
				else $player->used_DSs[$DS] = 1;
			}
		}

		console_echo("cooldown: <<#fff>>{$this->cooldown}<>", '#afa', CNS_ATTACK);
		$this->readyTime = $_SERVER['REQUEST_TIME_FLOAT'] + $this->cooldown;
		if ($this->cooldown === null) console_echo("<<#ffa>>NULL<> cooldown for attack of {$this->attacker->name}", '#faa');

		// Clear target
		$this->target = null;
		$this->cooldown = null;

		$this->damage					= 0;
		$this->DMGDL					= null;
		$this->DMGs						= [];
		$this->DMGs_def					= [];

		$this->isCrit					= null;
		$this->damage_modifiers			= [];
		$this->DMGs_modifiers			= [];
		$this->DMGs_def_modifiers		= [];
		$this->hitChance_modifiers		= [];
		$this->dodgeChance_modifiers	= [];
		$this->critChance_modifiers		= [];

		$this->kill						= null;
		$this->hit						= null;
	}

	function getcooldown($speedBias)
	{
		// The difficulty is the FOR variable because that's what makes the cooldown HIGHER.
		$speedBias = getBiasCalculation($this->difficulty, $speedBias, GBC_DECIMAL);
		// cooldown is set here but actually handled in the execute() function.
		return 0.5 + (3 * $speedBias);
	}

	function attackDude()
	{
		global $player;
		global $DMG_names; //XXX
		global $DMG_colours; //XXX

		global $FAC_standing;

		$event = new attackEvent();

		$event->isBaseAttack = $this->isBaseAttack;

		$event->attackerName = $this->attacker->name;
		$event->targetName = $this->target->name;

		$event->DMGDL = $this->DMGDL;
		$event->TEQT = $this->TEQT;

		$event->rawDamage = array_sum($this->DMGs);

		$event->towardsPlayer = $this->target === $player;

		$attackerLevelAdvantage = $this->attacker->level - $this->target->level;

		if ($this->attacker === $player)
		{
			console_echo("Setting opponent to {$this->target->name}", null, CNS_ATTACK);
			$player->opponent = $this->target;
		}
		elseif ($this->target === $player && !$player->opponent)
		{
			console_echo("Setting opponent to {$this->attacker->name}", null, CNS_ATTACK);
			$player->opponent = $this->attacker;
		}

		///////////////////////////////////////
		//
		//	PART I: Setting up
		//
		///////////////////////////////////////

		$attackerBiases = $this->attacker->getTEQbiases($this->TEQT);
		foreach($attackerBiases as &$value) $value += $value * (($this->attacker->level - $this->target->level) * 0.2);

		$targetBiases = $this->target->getTEQbiases($this->TEQT);
//		foreach($targetBiases as &$value) $value *= $this->target->level / $this->attacker->level;

		// Get the cooldown early so that behaviours can modify it with ease.
		$this->cooldown = $this->getcooldown($attackerBiases[TEQ_ATTACK_SPEED]);

//		$targetDefence = $this->target->DMGs_def;
		$this->DMGs_def = $this->target->DMGs_def;

		$report = $this->attacker === $GLOBALS['player'] || $this->target === $GLOBALS['player'];
		console_echo('Attack reporting is ' . ($report ? '<<#afa>>ON<>': '<<#faa>>OFF<>'), '#aaf', CNS_ATTACK);

		console_echo('Total damage before defence reduction: <<#fff>>' . array_sum($this->base_DMGs) . '<>', '#ffa', CNS_ATTACK);

		///////////////////////////////////////
		//
		//	PART II: First lot of behaviours
		//
		///////////////////////////////////////

		// Execute behaviours
		$this->attacker->attack($this);
		$this->target->defend($this);

		if ($this->isBaseAttack)
		{
			switch ($this->TEQT)
			{
				case TEQT_MELEE:
					update_sound(SND_SWING);
					break;
			}
		}

		///////////////////////////////////////
		//
		//	PART III: Did it hit?
		//
		///////////////////////////////////////

		if ($this->alwaysHit === null)
		{
			// Calculate odds of successful hit
			$hitChance = getBiasCalculation($attackerBiases[TEQ_HIT_CHANCE], $targetBiases[TEQ_DODGE_CHANCE], GBC_PERCENTAGE);

			console_echo("Chance to hit (beofre mods): <<#fff>>{$hitChance}%<>.", '#aaf', CNS_ATTACK);

			$this->hitChance_modifiers[] = $hitChance;
			$hitChance = valueListToValue($this->hitChance_modifiers);

			console_echo("Chance to hit (after mods): <<#fff>>{$hitChance}%<>.", '#aaf', CNS_ATTACK);

			// Get result
			$this->hit = percentageToBool($hitChance);
		}
		else
		{
			$this->hit = $this->alwaysHit;
			console_echo('This attack will ' . ($this->alwaysHit ? '<<#afa>>ALWAYS<>' : '<<#faa>>NEVER<>') . ' hit.', '#aaf', CNS_ATTACK);
		}

		console_echo('The attack will ' . ($this->hit ? '<<#afa>>HIT<>' : '<<#faa>>MISS<>'), '#aaf', CNS_ATTACK);

		///////////////////////////////////////
		//
		//	PART IV: Will it crit?
		//
		///////////////////////////////////////

		if ($this->alwaysCrit === null)
		{
			$critResistBias = 10 * ($targetBiases[TEQ_DODGE_CHANCE] + $targetBiases[TEQ_DEFENCE]);

			$critChance = getBiasCalculation($attackerBiases[TEQ_CRIT_CHANCE], $critResistBias, GBC_PERCENTAGE);

			console_echo("Crit chance (before mods): <<#fff>>{$critChance}%<>", '#ffa', CNS_ATTACK);

			$this->critChance_modifiers[] = $critChance;
			$critChance = valueListToValue($this->critChance_modifiers);

			console_echo("Crit chance (after mods): <<#fff>>{$critChance}%<>", '#ffa', CNS_ATTACK);
		}
		else
		{
			$critChance = $this->alwaysCrit ? 999 : 0;
			console_echo('This attack will ' . ($this->alwaysCrit ? '<<#afa>>ALWAYS<>' : '<<#faa>>NEVER<>') . ' crit.', '#ffa', CNS_ATTACK);
		}

		if (percentageToBool($critChance) && $this->hit)
		{
			console_echo('Critical hit has transpired', '#ffa', CNS_ATTACK);

			$this->isCrit = true;
			$critAmp = getBiasCalculation($attackerBiases[TEQ_CRIT_DAMAGE], $targetBiases[TEQ_DEFENCE], GBC_PERCENTAGE);

//			if ($this->isBaseAttack) update_sound(SND_WOOSHDING); // Oosenupt - make a better crit sound; this one is irritating as hell
			console_echo("Crit amp: <<#fff>>{$critAmp}%<>", '#ffa', CNS_ATTACK);
		}
		else
		{
			$critAmp = 0;
		}

		$event->critPercentage = $critAmp;

		///////////////////////////////////////
		//
		//	PART V: Hit or go home
		//
		///////////////////////////////////////

		// Did we get 'em?
		if ($this->hit)
		{
			// Execute behaviours related to a successful strike
			$this->attacker->strike($this);
			$this->target->takeHit($this);

			$event->hit = true;

			if ($this->isBaseAttack)
			{
				switch ($this->TEQT)
				{
					case TEQT_MELEE:
						switch ($this->DMGDL)
						{
							case DMGDL_BLUNT:	update_sound(SND_DMGDL_BLUNT);	break;
							case DMGDL_POINT:	update_sound(SND_DMGDL_POINT);	break;
							case DMGDL_CUT:		update_sound(SND_DMGDL_CUT);	break;
						}
						break;
				}
			}
		}
		else
		{
			$event->hit = false;

			// execute behaviours related to a failed attack
			$this->attacker->miss($this);
			$this->target->deflect($this);

			update_combat($event);
			return;
		}

		///////////////////////////////////////
		//
		//	PART VI: How much damage as actually done?
		//
		///////////////////////////////////////

		// Apply random aspect
		$randomisation = getBiasCalculation($this->difficulty, $attackerBiases[TEQ_CONSISTENCY], GBC_DECIMAL);
		$randomisation = mt_rand(
			ATTACK_RAND_MIN * $randomisation,
			ATTACK_RAND_MAX * $randomisation
		) . '%';

		console_echo("Randomisation value: <<#fff>>{$randomisation}<>", '#aff');

//		$this->damage_modifiers[] = $randomisation;

		$event->mainDMG = array_search(max($this->DMGs), $this->DMGs);

		foreach ($this->DMGs as $DMG => &$amount)
		{
			$consoleDMG = "<<{$DMG_colours[$DMG]}>>{$DMG_names[$DMG]}<>"; //XXX
			console_echo("{$consoleDMG} damage (before crit amp): <<#fff>>$amount<>", '#faa');
			// Aplly crit amp first (since it should be one of the more significant multipliers).
			$amount = sa($amount, "{$critAmp}%");

			console_echo("{$consoleDMG} damage (after crit amp): <<#fff>>$amount<>", '#faa');

			// Apply bias here? Not sure.

			// Apply damage modifiers (these should be affected by behaviours)
			if (isset($this->DMGs_modifiers[$DMG]))
			{
				console_echo("{$consoleDMG} damage (before mods): <<#fff>>$amount<>", '#faa');

				$this->DMGs_modifiers[$DMG][] = $amount;
				$amount = valueListToValue($this->DMGs_modifiers[$DMG]);

				console_echo("{$consoleDMG} damage (after mods): <<#fff>>$amount<>", '#faa');
			}

			// Sort out which defence types are relevant and apply those
			if (isset($this->DMGs_def[$DMG]))
			{
				console_echo("{$consoleDMG} damage (before {$DMG_names[$DMG]} defence): <<#fff>>$amount<>", '#faa');

				if (isset($this->DMGs_def_modifiers[$DMG]))
				{
					console_echo("{$consoleDMG} defence (before mods): <<#fff>>{$this->DMGs_def[$DMG]}<>", '#afa');

					$this->DMGs_def_modifiers[$DMG][] = $this->DMGs_def[$DMG];
					$this->DMGs_def[$DMG] = valueListToValue($this->DMGs_def_modifiers[$DMG]);
				}

				$amount = getBiasCalculation($amount, $this->DMGs_def[$DMG]);

				console_echo("{$consoleDMG} damage (after {$DMG_names[$DMG]} defence): <<#fff>>$amount<>", '#faa');
			}
		}

		$this->damage = array_sum($this->DMGs);

		console_echo("Total damage (before delivery defence): <<#fff>>{$this->damage}<>", '#faa');

		if (isset($this->DMGs_def[$this->DMGDL]))
		{
			$this->damage = getBiasCalculation($this->damage, $this->DMGs_def[$this->DMGDL]);

			console_echo("Total damage (after delivery defence): <<#fff>>{$this->damage}<>", '#faa');
		}
		else { console_echo('No delivery defence!', '#fda'); }

		$damageBias = getBiasCalculation($attackerBiases[TEQ_DAMAGE], $targetBiases[TEQ_DEFENCE], GBC_PERCENTAGE) . '%';

		$this->damage_modifiers[] = $damageBias;
		$this->damage_modifiers[] = $this->damage;

//		$damageBiasBoost = $this->damage * getBiasCalculation($attackerBiases[TEQ_DAMAGE], $targetBiases[TEQ_DEFENCE], GBC_DECIMAL);
		$event->rawDamage = sa($event->rawDamage, $damageBias);

//		$this->damage_modifiers[] = $this->damage + $damageBiasBoost;

		$this->damage = valueListToValue($this->damage_modifiers);

		$event->damage = round($this->damage, 1);

		$this->damage = sa($this->damage, $randomisation); // Randomisation is only factored in after the event has its value to make sure its always giving consistent and valuable feedback.

		$event->dealtDamage = $this->damage;

		console_echo("Total damage (after mods): <<#fff>>{$this->damage}<>", '#faa', CNS_ATTACK);

		///////////////////////////////////////////
		//                                      //
		//	PART VII: Do the damage            //
		//                                    //
		///////////////////////////////////////

		$this->kill = !$this->target->alterHp(0 - $this->damage);



		// Are they dead?
		if ($this->kill)
		{
//			if ($report) update_combat("<<#fff>>{$this->target->name}<> was <<#faa>>killed<> by <<#fff>>{$this->attacker->name}<>!");

			$event->killed = true;

			// Execute behaviours related to the killing of a dude
			$this->target->death($this);
			$this->attacker->kill($this);
		}

		if ($this->attacker === $player || $this->target === $player)
		{
			$player->handleEventOfInterest(EOI_ATTACK, $this);
			update_combat($event);
		}


	}

	function defer($seconds)
	{
		$this->readyTime += $seconds;
	}
}

class attackEvent
{
	public $isBaseAttack;
	public $attackerName;
	public $targetName;
	public $critPercentage;
	public $rawDamage;
	public $damage;
	public $DMGDL;
	public $dealtDamage;
	public $hit;
	public $killed = false;
	public $mainDMG;
	public $TEQT;
	public $towardsPlayer;

	public function __toString()
	{
		global $DMG_DMGDL_names;
		global $DMG_colours;
		global $TEQT_names;


		$this->rawDamage = round($this->rawDamage, 1);
		$this->dealtDamage = round($this->dealtDamage, 1);
		$rating = round(($this->damage / $this->rawDamage) * 100);

		if ($this->isBaseAttack)
		{
			if (!$this->hit) return "<<#fff>>{$this->attackerName}<> missed <<#fff>>{$this->targetName}<>.";

			$hitType = $this->killed ? 'killed' : 'hit';

			if ($this->critPercentage > 0)
			{
				$crit = 'critical <span class="fade">(+' . round($this->critPercentage) . '%)</span> ';
				$damageColour = '#f22';
			}
			else
			{
				$crit = '';
				$damageColour = '#f88';
			}

			$damageType = "<<{$DMG_colours[$this->mainDMG]}>>{$DMG_DMGDL_names[$this->mainDMG]}<>";


			$string = "<<#fff>>{$this->attackerName}<> {$hitType} <<#fff>>{$this->targetName}<> with <<{$damageColour}>>{$this->dealtDamage}<> {$crit}{$damageType} damage via a {$TEQT_names[$this->TEQT]} <<#fff>>{$DMG_DMGDL_names[$this->DMGDL]}<> attack.<br><span class=\"fade\">{$rating}% effective.</span>";
		}
		else
		{
			$direction = $this->towardsPlayer ? '<<#a00>>&#x25bc;<>' : '<<#0a0>>&#x25b2;<>';
			$string = "{$direction} +<<{$DMG_colours[$this->mainDMG]}>>{$this->dealtDamage}<> <span class=\"fade\">{$rating}%</span>";
		}

		return "{$string}";
	}

}