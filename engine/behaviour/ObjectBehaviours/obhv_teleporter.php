<?php

class obhv_teleporter extends ObjectBehaviour
{
	public $n_offset;
	public $w_offset;
	public $map;

	public function __construct($n_offset, $w_offset, $MAP = null)
	{
		$this->onReaction = true;

		$description = "Teleports to {$n_offset}:{$w_offset}"
		. ($MAP ? " - {$MAP}." : '.');

		$this->n_offset	= $n_offset;
		$this->w_offset	= $w_offset;
		$this->map		= $MAP;

		parent::__construct($description, BHVK_TELEPORT, 0);
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		global $view;
		global $player;

		if ($instigator === $player)
        {
			if ($this->owner->constituents)
			{
				$destination_n_offset = $player->n_offset - $this->owner->n_offset + $this->n_offset;
				$destination_w_offset = $player->w_offset - $this->owner->w_offset + $this->w_offset;

				switch ($DIR)
				{
					case DIR_NORTH:	$destination_n_offset --;
					case DIR_SOUTH:	$destination_n_offset ++;
					case DIR_WEST:	$destination_w_offset --;
					case DIR_EAST:	$destination_w_offset ++;
				}

				console_echo("<<#fff>>{$this->owner->name}<> has constituents!", '#ffa', CNS_BEHAVIOUR);
			}
			else
			{
				$destination_n_offset = $this->n_offset;
				$destination_w_offset = $this->w_offset;

				console_echo("<<#fff>>{$this->owner->name}<> has no constituents.", '#afa', CNS_BEHAVIOUR);
			}

            console_echo("Entering the {$this->owner->name} portal!");		//XXX

			$this->owner->permitEntry = false;
			$player->move($destination_n_offset, $destination_w_offset, $this->map);

            $view->forceUpdate = true;
            return false;
        }
	}
}