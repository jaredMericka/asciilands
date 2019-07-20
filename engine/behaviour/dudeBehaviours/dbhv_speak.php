<?php

class dbhv_speak extends DudeBehaviour
{

	static $oddsOfCombatSpeech = 50;

	public function __construct($SPSIs_exceptions = null)
	{
		if (!isset($SPSIs_exceptions)) $SPSIs_exceptions = [];

		$this->onReaction	= in_array(SPSI_CONVERSING,	$SPSIs_exceptions) === false;
		$this->onEngage		= in_array(SPSI_GREETING,	$SPSIs_exceptions) === false;
		$this->onDisengage	= in_array(SPSI_SAYING_BYE,	$SPSIs_exceptions) === false;

		$this->onAttack		= in_array(SPSI_ATTACKING,	$SPSIs_exceptions) === false;
		$this->onMiss		= in_array(SPSI_MISSING,	$SPSIs_exceptions) === false;
		$this->onStrike		= in_array(SPSI_STRIKING,	$SPSIs_exceptions) === false;
		$this->onKill		= in_array(SPSI_KILLING,	$SPSIs_exceptions) === false;

		$this->onDefend		= in_array(SPSI_DEFENDING,	$SPSIs_exceptions) === false;
		$this->onDeflect	= in_array(SPSI_DEFLECTING,	$SPSIs_exceptions) === false;
		$this->onTakeHit	= in_array(SPSI_TAKING_HIT,	$SPSIs_exceptions) === false;
		$this->onDeath		= in_array(SPSI_DYING,		$SPSIs_exceptions) === false;

		$description = "Greets, converses and dismisses when interacted with.";

		parent::__construct($description, BHVK_SPEAK);
	}

	public function onEngage(Player $player)
	{
		if (!$player->handleEventOfInterest(EOI_ENGAGE_NPC, $this->owner))
		{
			$this->owner->speak(SPSI_GREETING, null, true);
		}
	}

	public function onDisengage(Player $player)
	{
			$this->owner->speak(SPSI_SAYING_BYE, null, true);
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		if ($instigator instanceof Player) $this->owner->speak(SPSI_CONVERSING);
	}


	public function onAttack(Attack $attack)
	{
		if (percentageToBool(self::$oddsOfCombatSpeech)) $this->owner->speak(SPSI_ATTACKING, $attack);
	}

	public function onMiss(Attack $attack)
	{
		if (percentageToBool(self::$oddsOfCombatSpeech)) $this->owner->speak(SPSI_MISSING, $attack);
	}

	public function onStrike(Attack $attack)
	{
		if (percentageToBool(self::$oddsOfCombatSpeech)) $this->owner->speak(SPSI_STRIKING, $attack);
	}

	public function onKill(Attack $attack)
	{
		$this->owner->speak(SPSI_KILLING, $attack);
	}

	public function onDefend(Attack $attack)
	{
		if (percentageToBool(self::$oddsOfCombatSpeech)) $this->owner->speak(SPSI_DEFENDING, $attack);
	}

	public function onDeflect(Attack $attack)
	{
		if (percentageToBool(self::$oddsOfCombatSpeech)) $this->owner->speak(SPSI_DEFLECTING, $attack);
	}

	public function onTakeHit(Attack $attack)
	{
		if (percentageToBool(self::$oddsOfCombatSpeech)) $this->owner->speak(SPSI_TAKING_HIT, $attack);
	}

	public function onDeath(Attack $attack)
	{
		$this->owner->speak(SPSI_DYING, $attack);
	}
}