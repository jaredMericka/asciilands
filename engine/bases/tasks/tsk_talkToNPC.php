<?php

class tsk_talkToNPC extends Task
{
	public $EOI = EOI_ENGAGE_NPC;

	public $mask;
	public $speech;

	public function __construct(NPC $NPC, $speech = null)
	{
		$this->mask = new Mask($NPC, ['name', 'class']);

		$this->description = "Talk to {$NPC->name}.";
		$this->speech = $speech;
	}

	public function check($args)
	{
		list($NPC) = $args;

		if ($this->mask->compare($NPC))
		{
			if ($this->speech) $NPC->speak($this->speech);
			$this->complete();
		}
	}
}