<?php

class npci_sell extends NPCInteraction
{
	public $mask;
	public $sa_rate;

	public function __construct(Mask $mask = null, $sa_rate = null)
	{
//		$this->onGainItem = true;
//		$this->onLoseItem = true;

		$name = 'Sell';
		$description = 'View saleable items.';

		$this->mask = $mask;

		// Keeping prices consistent is ideal but offering a bonus for having the right item for an esoteric purchaser makes sense, too.
		// This is why you can only change the rate if it has a mask.
		if ($mask && $sa_rate) $this->sa_rate = $sa_rate;

		parent::__construct($name, $description);

		$this->cooldown = 0;
	}

	public function onClick($UIN)
	{
		global $player;
		global $currencies;

		$CUR = $this->owner->CUR ? $this->owner->CUR : CUR_GOLD;

		$update = new stdClass();

		$update->type = $this->key;
		$update->CURsym = $currencies[$CUR]->symbol;
		$update->items = $player->inventory->getAjaxObjects($CUR, $this->sa_rate, $this->mask);

		update(UPD_INTERACTIONS, $update);
	}

	public function onItemClick($UIN, $content)
	{
		global $player;

		$CUR = $this->owner->CUR ? $this->owner->CUR : CUR_GOLD;

		console_echo("{$UIN} {$content}");

		$item = $player->inventory->getItemByIndex($content);

		$quantity = 1;

		switch ($UIN)
		{
			case UIN_CLICK:
				$item->inspect();
				break;
			case UIN_CTRL_RIGHT_CLICK:
				$quantity = $item->quantity;
			case UIN_RIGHT_CLICK:
				if ($player->inventory->sell($item, $this->owner, $CUR, $quantity, $this->sa_rate))
				{
//					console_var_dump($item);	// This is what you hoped
					$player->engagement->speak(SPSI_BUYING, $item);
//					$this->updatePanel($item);
				}
				else
				{
					$player->engagement->speak('I can\'t buy that.');
				}
				break;
		}
	}

	public function updatePanel(Item $item)
	{
		global $currencies;

		$update = new stdClass();

		$update->gentle	= true;
		$update->type	= $this->key;
		$update->CURsym	= $currencies[$this->owner->CUR]->symbol;
		$update->items	= [$item->getAjaxObject($this->owner->CUR)];

		update(UPD_INTERACTIONS, $update);
	}

//	public function onGainItem(Item $item) { $this->updatePanel($item); }
//	public function onLoseItem(Item $item) { $this->updatePanel($item); }
}