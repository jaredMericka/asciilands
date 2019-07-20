<?php

class obhv_checkpoint extends ObjectBehaviour
{
	public function __construct()
	{
		$this->onReaction = true;
		$this->onIdle = true;
		parent::__construct('Checkpoint', null);
	}

	public function onIdle ()
	{
		global $player;

		$this->owner->invisible =
			$player->checkpoint_n_offset !== $this->owner->n_offset ||
			$player->checkpoint_w_offset !== $this->owner->w_offset ||
			$player->checkpoint_MAP !== $player->MAP;

	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		global $player;

		if ($instigator !== $player) return;

		if (
			$player->checkpoint_n_offset === $this->owner->n_offset
			&& $player->checkpoint_w_offset === $this->owner->w_offset
			&& $player->checkpoint_MAP === $player->MAP
		) return;



		$player->checkpoint_n_offset	= $this->owner->n_offset;
		$player->checkpoint_w_offset	= $this->owner->w_offset;
		$player->checkpoint_MAP			= $player->MAP;

		update_thoughts('Checkpoint reached!');
		update_sound(SND_CHECKPOINT);
	}
}
