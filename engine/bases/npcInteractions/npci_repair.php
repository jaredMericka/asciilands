<?php

class npci_repair extends NPCInteraction
{
	public $efficiency;
	public $mask;

	public function __construct($efficiency = null, Mask $mask = null)
	{
		$efficiency = $efficiency ? $efficiency : 80;
		if ($efficiency <= 1) $efficiency *= 100;

		$this->efficiency = min([max([$efficiency, 50]), 100]);
		$this->mask = $mask;

		parent::__construct("Repair ({$efficiency}%)", "Repair items to {$this->efficiency}% of their original quality.");
	}

	public function onClick($UIN)
	{
		global $player;
		global $currencies;

		$CUR = $this->owner->CUR ? $this->owner->CUR : CUR_GOLD;

		$update = new stdClass();
		$update->type = $this->key;
		$update->items = [];
		$update->curSym = $currencies[$CUR]->symbol;
		$update->eff = $this->efficiency;

		foreach ($player->inventory->contents as $key => $item)
		{
			if ($this->mask && !$this->mask->compare($item)) continue;

			if ($item->durability < $item->durabilityMax)
			{
				$update_item = new stdClass();

				$update_item->name = $item->name;
				$update_item->id = $item->id;
				$update_item->dur = $item->durability;
				$update_item->durMax = $item->durabilityMax;

				$update_item->price = number_format(convertCurrency($this->getRepairPrice($item), CUR_GOLD, $CUR));

				$update->items[] = $update_item;
			}
		}

		update(UPD_INTERACTIONS, $update);
	}

	public function onItemClick($UIN, $content)
	{
		global $player;
		$item = $player->inventory->getItemByIndex($content);


		if (!$item)
		{
			update_thoughts('Hey, where\'d it go?');
			return;
		}

		if ($this->mask && !$this->mask->compare($item))
		{
			// Packet hacking detected
			return;
		}

		switch ($UIN)
		{
			case UIN_CLICK:
				$item->inspect();
				break;
			case UIN_RIGHT_CLICK:
				if ($player->wallet->remove($this->getRepairPrice($item), $this->owner->CUR))
				{
					$this->repairItem($item);

					$update = new stdClass();
					$update->type = $this->key;

					$itemUpdate = new stdClass();
					$itemUpdate->id = $item->id;
					$itemUpdate->delete = true;

					$update->items = [$itemUpdate];

					update(UPD_INTERACTIONS, $update);
				}
				else
				{
					$this->owner->speak(SPSI_REPAIRING_NE, $item);
				}

				break;
		}
	}

	public function getRepairPrice (Item $item)
	{
		$repairPrice = $item->durabilityMax - $item->durability;

		$repairPrice += ($repairPrice * ($item->level / 6));

		$repairPrice *= $this->efficiency / 10;

		if ($item->isBroken) $repairPrice += 2;

		return $repairPrice;
	}

	public function repairItem (Item $item)
	{
		$recovery = ceil(($item->durabilityMax - $item->durability) * ($this->efficiency / 100));

		console_echo("Current durability: <<#fff>>({$item->durability}/{$item->durabilityMax})<>", '#aaf', CNS_ITEMS);
		console_echo("Recovery: <<#fff>>$recovery<>", '#aaf', CNS_ITEMS);

		$this->owner->speak(SPSI_REPAIRING, $item);

		$item->durability += $recovery;
		$item->durabilityMax = $item->durability;
	}
}