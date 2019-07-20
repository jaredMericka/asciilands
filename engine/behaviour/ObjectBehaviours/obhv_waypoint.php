<?php

class obhv_waypoint extends ObjectBehaviour
{
	public $WP;

	public $n_offset;
	public $w_offset;
	public $MAP;

	public $attainable;

	public function __construct($WP, $n_offset, $w_offset, $MAP, $attainable = false)
	{
		$this->onReaction = true;
		$this->onIdle = true;

		$this->WP = $WP;
		$this->n_offset = $n_offset;
		$this->w_offset = $w_offset;
		$this->MAP = $MAP;
		$this->attainable = $attainable;

		parent::__construct('Waypoint', BHVK_TELEPORT);
	}

	public function onIdle () { global $player; $this->owner->invisible = !isset($player->WPs[$this->WP]); }

	public function onReaction(AsObject $instigator, $DIR)
	{
		global $player;
		global $view;

		if ($instigator !== $player) return true;

		console_echo("Hitting a waypoint with destination {$this->n_offset}:{$this->w_offset}:{$this->MAP}", '#fff');

		if (isset($instigator->WPs[$this->WP]))
		{
			console_echo('Player has waypoint! Let\'s go!', '#afa');

			$this->owner->permitEntry = false;
			$player->move($this->n_offset, $this->w_offset, $this->MAP);
			$view->forceUpdate = true;
			update_sound(SND_TELEPORT);
			return false;
		}
		elseif ($this->attainable)
		{
			console_echo('Player has just gotten waypoint! Let\'s go!', '#ffa');
			$player->WPs[$this->WP] = time();

			$this->owner->permitEntry = false;
			$player->move($this->n_offset, $this->w_offset, $this->MAP);
			$view->forceUpdate = true;
			update_sound(SND_TELEPORT);
			update_sound(SND_NEWWAYPOINT);
			return false;
		}
		else { console_echo('Player hasn\'t got this waypoint!', '#faa'); }
		return true;
	}
}
