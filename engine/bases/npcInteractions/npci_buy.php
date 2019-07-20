<?php

class npci_buy extends NPCInteraction
{
	public $mask;
	public $sa_rate;

	public function __construct(Mask $mask = null, $sa_rate = null)
	{
		$this->onGainItem = true;
		$this->onLoseItem = true;

		$name = 'Buy';
		$description = 'View items for sale.';

		$this->mask = $mask;

		// Keeping prices consistent is ideal but offering a bonus for having the right item for an esoteric purchaser makes sense, too.
		// This is why you can only change the rate if it has a mask.
		if ($mask && $sa_rate) $this->sa_rate = $sa_rate;

		parent::__construct($name, $description);

		$this->cooldown = 0;
	}

	public function onClick($UIN)
	{
		global $currencies;

		$CUR = $this->owner->CUR ? $this->owner->CUR : CUR_GOLD;

		$update = new stdClass();

		$update->type = $this->key;
		$update->CURsym = $currencies[$CUR]->symbol;
		$update->items = $this->owner->inventory->getAjaxObjects($CUR, $this->sa_rate, $this->mask);

		update(UPD_INTERACTIONS, $update);
	}

	public function onItemClick($UIN, $content)
	{
		global $player;

		$CUR = $this->owner->CUR ? $this->owner->CUR : CUR_GOLD;

		console_echo("{$UIN} {$content}");

		$item = $this->owner->inventory->getItemByIndex($content);

		$quantity = 1;

		switch ($UIN)
		{
			case UIN_CLICK:
				$item->inspect();
				break;
			case UIN_CTRL_RIGHT_CLICK:
				$quantity = $item->quantity;
			case UIN_RIGHT_CLICK:
				if($this->owner->inventory->sell($item, $player, $CUR, $quantity, $this->sa_rate))
				{
//					console_var_dump($item);
					$this->owner->speak(SPSI_SELLING, $item);
//					$this->updatePanel($item);
				}
				else
				{
					$this->owner->speak(SPSI_SELLING_NE, $item);
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

	public function onGainItem(Item $item)
	{
		console_echo('npci_buy onGainItem before updatePanel');
		$this->updatePanel($item);
		console_echo('npci_buy onGainItem after updatePanel');
	}

	public function onLoseItem(Item $item)
	{
		console_echo('npci_buy onLoseItem before updatePanel');
		$this->updatePanel($item);
		console_echo('npci_buy onLoseItem after updatePanel');
	}
}