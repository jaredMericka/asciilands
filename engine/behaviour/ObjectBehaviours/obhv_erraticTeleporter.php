<?php

class obhv_erraticTeleporter extends ObjectBehaviour
{
	public $possibilities;
	public $final_n_offset;
	public $final_w_offset;
	public $remainingIterations;

    public function __construct($teleport_possibilities, $teleport_final_n_offset = null, $teleport_final_w_offset = null, $iterations = null)
    {
        $this->onReaction = true;

        $description = "Erratically teleports to places";

        $this->possibilities			= $teleport_possibilities;
        $this->final_n_offset			= $teleport_final_n_offset;
        $this->final_w_offset			= $teleport_final_w_offset;
		$this->remainingIteration		= (isset($teleport_final_n_offset, $teleport_final_w_offset) ? $iterations : null);

        parent::__construct($description, BHVK_TELEPORT, 0);
    }

    public function onReaction(AsObject $instigator, $DIR)
    {
        global $view;
        global $player;

        if ($instigator instanceof Player)
        {
            console_echo("Entering the {$this->owner->name} erraticPortal!");		//XXX

			if ($this->remainingIterations == null || $this->remainingIterations > 0)
			{
				$coOrds = $this->possibilities[array_rand($this->possibilities)];

				$instigator->n_offset = $coOrds[0];
				$instigator->w_offset = $coOrds[1];

				$this->remainingIterations --;

				console_echo("ErAtIc iterations left: {$this->remainingIterations}");
			}
			else
			{
				$instigator->n_offset = $this->final_n_offset;
				$instigator->w_offset = $this->final_w_offset;
			}

            $player->lastMoved = $_SERVER['REQUEST_TIME_FLOAT']; // To prevent a quick double-move since we're not triggering the actual move event.
            console_update_location();


            $view->forceUpdate = true;
            return false;
        }
    }
}