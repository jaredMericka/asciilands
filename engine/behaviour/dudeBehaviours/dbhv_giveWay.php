<?php

class dbhv_giveWay extends DudeBehaviour
{
	public function __construct()
	{
		$this->onReaction = true;

		parent::__construct('Jumps out of the way of a player with a weapon', 'giveWay', 0);
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		console_echo('Give way?');
		if ($instigator instanceof Player &&
			isset($instigator->equipped[EQP_HAND]) &&
			$instigator->inventory->contents[$instigator->equipped[EQP_HAND]] instanceof a_eqp_weapon)
		{
			console_echo('Way given!');
			$this->owner->permitEntry = true;
			$this->owner->move($instigator->n_offset, $instigator->w_offset, true);
		}
		else //XXX
		{ //XXX
			console_echo('Nope!');
		}//XXX
	}
}