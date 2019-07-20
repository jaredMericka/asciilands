<?php

class dbhv_dropConstituentsOnDeath extends DudeBehaviour
{
	public $dropees;

	public function __construct($dropees)
	{
		$this->onDeath = true;

		$this->dropees = $dropees;

		parent::__construct('Drops some constituents when the owner dies.', null);
	}

	public function onDeath(Attack $attack)
	{
		console_echo('Dropping some constituents!');
		$this->owner->constituentClear();
		foreach ($this->dropees as $n_offset => $w_offsets)
		{
			foreach ($w_offsets as $w_offset => $null)
			{
				unset($this->owner->constituents[$n_offset][$w_offset]);
			}
		}
		$this->owner->constituentPlace();

		$this->delete();
	}
}