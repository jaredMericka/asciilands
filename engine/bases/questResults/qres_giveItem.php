<?php

class qres_giveItem extends QuestResult
{
	public $item;

	public function __construct(Item $item)
	{
		$this->item = $item;

		$description = "Receive {$item->name}.";
		parent::__construct($description);
	}

	public function deliver(Player $recipient)
	{
		$recipient->inventory->add($this->item);
	}
}