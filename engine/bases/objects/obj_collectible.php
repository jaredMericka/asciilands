<?php

class obj_collectible extends AsObject
{
	protected $unique = true;

    public function __construct($item)
    {
		global $player;

		console_echo('About to consolidate item from inside collectible object...', '#fff');
		$item->finish();

		if ($this->unique && $player->inventory->hasItem($item))
		{
			// code for removing duplicate unique items
		}

		$this->addBehaviour(
			new obhv_collectible($item)
		);

        parent::__construct($item->name, [$item->sprite], LAYER_COLLECTIBLE);
    }
}
