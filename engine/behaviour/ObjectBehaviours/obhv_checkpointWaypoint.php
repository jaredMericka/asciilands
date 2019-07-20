<?php

class obhv_checkpointWaypoint extends ObjectBehaviour
{
	public function __construct()
	{
		$this->onReaction = true;

		parent::__construct('Checkpoint Waypoint', BHVK_TELEPORT);
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		global $player;

		if ($instigator !== $player) return;

		$this->owner->permitEntry = false;
		update_sound(SND_TELEPORT);
		$player->move($player->checkpoint_n_offset, $player->checkpoint_w_offset, $player->checkpoint_MAP);
	}
}
