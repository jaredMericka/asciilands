<?php

class Inventory
{
	private	$owner;

	public $contents	= [];
	public $locked		= false;

	public $lootable = false;
	public $CUR = null;

	public $TFI = null;

	public function __construct(AsObject &$owner)
	{
		$this->owner = $owner;
	}

	public function getItemCount($matchItem = null, $matchProperties = null)
	{
		if (!isset($matchItem)) return count($this->contents);

		$count = 0;

		if ($matchItem instanceof Item)
		{
			$matchItem = new Mask($matchItem, $matchProperties);
		}

		foreach ($this->contents as $item)
		{
			if ($matchItem->compare($item)) $count += $item->quantity;
		}
		return $count;
	}

	public function hasItem ($itemOrMask)
	{
		if ($itemOrMask instanceof Item)
		{
			$itemOrMask = new Mask($itemOrMask);
		}

		foreach ($this->contents as $item)
		{
			if ($itemOrMask->compare($item))
			{
				return true;
			}
		}

		return false;
	}

	public function getItemByIndex($index)
	{
//		console_echo('getting item by index');		//XXX
		if (isset($this->contents[$index]))
		{
			return $this->contents[$index];
		}
		else
		{
			console_echo("Attempting to access item at index {$index} of {$this->owner->name}'s inventory but this index does not exist.", '#faa', CNS_ITEMS);		//XXX
			return false;
		}
	}

	public function add($item, $forceMoneyItem = false)
	{
		if (!($item instanceof Item))
		{
			console_echo('Trying to add non-item to an inventory.', '#faa', CNS_ITEMS);
			return;
		}

		global $player;

		$item->finish();

		console_echo("Adding <<#fff>>{$item->name}<> to inventory of <<#fff>>{$this->owner->name}<>.", '#aaf', CNS_ITEMS);


		if ($this->locked)
		{
			console_echo("Attempting add to locked inventory of {$this->owner->name}.", '#faa', CNS_ITEMS);
			return;
		}

		if ($this->owner instanceof Player)
		{
			update_thoughts("Found {$item->name}!");
		}

		// Does this inventory transform stuff?
		if ($this->TFI)
		{
			console_echo('Looks like this inveotry is going to transform some stuff.', '#ffa', CNS_ITEMS);
			// Transform the item in the appropriate way
			$transformedItems = $item->onTransform($this->TFI);
			if (!is_array($transformedItems)) $transformedItems = [$transformedItems];

			// Remove the transformation function of this inventory so that we
			// can add the new items without having them transform again.
			$heldTFI = $this->TFI;
			$this->TFI = null;

			foreach ($transformedItems as $tItem)
			{
				console_echo("We got a <<#fff>>{$tItem->name}<> from that <<#fff>>{$item->name}<>.", '#afa', CNS_ITEMS);
				$this->add($tItem);
			}

			// Reinstate the transformation property
			$this->TFI = $heldTFI;
			return;
		}

		if (isset($this->owner->wallet) && $item instanceof itm_money && !$forceMoneyItem)
		{
			console_echo('Sending money item to the wallet.', '#ccc', CNS_ITEMS);
			$this->owner->wallet->addmoney($item);
			return;
		}

		console_echo("{$this->owner->name} collected {$item->name} [{$item->quantity}].", '#fca', CNS_ITEMS);

        $itemLocation = $this->findItem($item);

        if ($itemLocation !== false)
        {
            $this->contents[$itemLocation]->quantity += $item->quantity;
			$item = $this->contents[$itemLocation];
            if ($this->owner instanceof Player) console_echo("Duplicate item: {$item->name}");
        }
        else
        {
            $this->contents[$item->id] = $item;
			$item->owner = $this->owner;
        }

        $item->onCollect();
		console_echo('Before onGainItem');
		$this->owner->onGainItem($item);
		console_echo('After onGainItem');

		if ($this->owner === $player)
		{
//			update_items($item, $item->quantity < 1);
			update_items($item);
		}
		elseif ($this->lootable && $this->owner === $player->engagement)
		{
			update_available($item);
		}

		return $item;
	}

    public function pullItem($item = null, $quantity = null)
    {
		global $player;

		if ($this->locked)
		{
			console_echo("Attempting to pull from locked inventory of {$this->owner->name}.", '#faa', CNS_ITEMS);
			return;
		}

		$itemLocation = false;

		if (isset($this->contents[$item->id]))
		{
			$itemLocation = $item->id;
		}
		elseif (empty($item))
		{
			$itemLocation = array_rand($this->contents);
		}
		elseif (is_int($item))
		{
			$itemLocation = (array_key_exists($item, $this->contents) ? $item : false);
		}
		elseif ($item instanceof Item)
		{
			$itemLocation = $this->findItem($item);
		}


		if ($itemLocation !== false && isset($this->contents[$itemLocation]))
		{
			$item = $this->contents[$itemLocation];
			if ($this->contents[$itemLocation]->cantLose)
			{
				$item = $this->contents[$itemLocation];
				console_echo("Attempting to remove an unlosable item <<#fff>>\"{$item->name}\"<> from the inventory of <<#fff>>{$this->owner->name}<>", null, CNS_ITEMS);
				update_thoughts("I'd better hold onto this {$item->name} for now.");
				return false;
			}

            if (isset($this->contents[$itemLocation]->isEquipped) && $this->contents[$itemLocation]->isEquipped)
			{
				$this->contents[$itemLocation]->unequip();
			}

			$pulledItem = clone $this->contents[$itemLocation];

			if (isset($quantity) && $quantity <= $this->contents[$itemLocation]->quantity)
			{
				console_echo("Pulling {$quantity}x {$pulledItem->name} from {$this->owner->name}'s inventory.", null, CNS_ITEMS);
				$this->contents[$itemLocation]->quantity -= $quantity;
				$pulledItem->quantity = $quantity;
			}
			else
			{
				$pulledItem->quantity = $this->contents[$itemLocation]->quantity;
				$this->contents[$itemLocation]->quantity = 0;
			}

			console_echo('Before onLoseItem');
			$this->owner->onLoseItem($item);
			console_echo('After onLoseItem');

			if ($this->owner === $player)
			{
//				update_items($item, $item->quantity < 1);
				update_items($item);
			}
			elseif ($this->owner === $player->engagement)
			{
//				update_available($item, $item->quantity < 1);
				update_available($item);
			}

			if ($this->contents[$itemLocation]->quantity < 1)
			{
				console_echo("Pulling {$pulledItem->name} from {$this->owner->name}'s inventory.", null, CNS_ITEMS);
				console_echo("Item binding slot: {$pulledItem->SKLS}", '#aaf', CNS_ITEMS);
				if ($this->owner === $player && $this->contents[$itemLocation]->SKLS !== null)
				{
					console_echo('Unbinding a bound inventory item before it gets removed.', '#faf', CNS_ITEMS);
					$player->bindings[$this->contents[$itemLocation]->SKLS]->unbind();
					update_bindings();
				}
				unset($this->contents[$itemLocation]);
			}

			return $pulledItem;
		}
		console_echo('Trying to remove a ' . get_class($item) . " from the inventory of {$this->owner->name} but it doesn't have one. Look into that.", '#faa', CNS_ITEMS);
//		console_var_dump($this->contents);
		return false;
    }

	public function findItem(Item $item)
	{
		foreach ($this->contents as $key => $invItem)
		{
			if ($item->equals($invItem))
			{
				return $key;
			}
		}
		return false;
	}

	public function findItemByType($type)
	{
		foreach ($this->contents as $key => $item)
		{
			if (strtolower(get_class($item)) == strtolower($type))
			{
				return $key;
			}
		}
		return false;
	}

	public function sell($item, Dude $buyer, $CUR, $quantity = 1, $sa_priceMod = null)
	{
		global $player;

		if (is_integer($item)) $item = $this->getItemByIndex($item);

		if (!($item instanceof Item)) return false;

		if ($item->cantLose)
		{
			update_thoughts("I really shouldn't sell this {$item->name} right now.");
			return false;
		}

//		$price = $item->getPrice($this->CUR) * $quantity;
		$price = $item->getPrice($CUR, $sa_priceMod) * $quantity;

		if ($buyer === $player)
		{
//			if (!isset($buyer->wallet->contents[$this->CUR]) || $buyer->wallet->contents[$this->CUR] < $price) return false;
//			$buyer->wallet->contents[$this->CUR] -= $price;
			if (!isset($buyer->wallet->contents[$CUR]) || $buyer->wallet->contents[$CUR] < $price) return false;
			$buyer->wallet->contents[$CUR] -= $price;
		}

		if ($item = $this->pullItem($item, $quantity))
		{
			$buyer->inventory->add($item);
		}
		else
		{
			return false;
		}

		if ($this->owner === $player)
		{
//			if (isset($player->wallet->contents[$this->CUR]))
			if (isset($player->wallet->contents[$CUR]))
//				$player->wallet->contents[$this->CUR] += $price;
				$player->wallet->contents[$CUR] += $price;
			else
//				$player->wallet->contents[$this->CUR] = $price;
				$player->wallet->contents[$CUR] = $price;
		}

		update_money();

		return true;
	}

	public function getAjaxObjects($CUR = null, $sa = null, Mask $mask = null)
	{
		$ajaxObjects = [];

		foreach ($this->contents as $item)
		{
			if (isset($mask) && !($mask->compare($item))) continue;
			$ajaxObjects[] = $item->getAjaxObject($CUR, $sa);
		}

		return $ajaxObjects;
	}

	public function initialiseLoot ($lootArray)
	{
		console_echo("Initialising loot for <<#fff>>{$this->owner->name}<>!", '#0f0', CNS_ITEMS);

		foreach ($lootArray as $odds => $stuff)
		{
			$vars = explode(':', "{$odds}");

			$odds = $vars[0];
			$max = isset($vars[1]) ? $vars[1] : 1;

			shuffle($stuff);

			$got = 0;

			foreach ($stuff as $thing)
			{
				if (percentageToBool($odds))
				{
					if (!is_array($thing)) $thing = [$thing];

					foreach ($thing as $item)
					{
						console_echo($item->id, null, CNS_ITEMS);
						$item->finished = false;
						$this->add(clone $item, true);
					}

					$got++;
				}

				if ($got >= $max) break;
			}
		}

		console_echo('Intialise loot complete.', '#0f0', CNS_ITEMS);
	}

	function __debugInfo ()
	{
		return [
			'owner' => $this->owner ? $this->owner->name : '### NO OWNER ###',
			'contents' => $this->contents,
			'CUR'	=> $this->CUR ? $GLOBALS['currencies'][$this->CUR]->name : '## NONE ##',
			'TFI'	=> $this->TFI,
		];
	}
}

class Wallet
{
	private $owner;
	public $contents = [];

	public function __construct(AsObject &$owner)
	{
		$this->owner = $owner;
	}

	public function add($amount, $CUR)
	{
		$amount = floor($amount);

		if ($amount < 0) return false;

		if (isset($this->contents[$CUR]))
		{
			$this->contents[$CUR] += $amount;
		}
		else
		{
			$this->contents[$CUR] = $amount;
		}
		if ($this->owner === $GLOBALS['player']) update_money();

		return true;
	}

	public function addmoney(itm_money $money)
	{
		$this->add($money->amount, $money->CUR);
	}

	public function remove($amount, $CUR)
	{
		if (!isset($this->contents[$CUR]) || $this->contents[$CUR] < $amount)
		{
//			console_echo("Attempting to take {$amount} {$GLOBALS['currencies'][$CUR]} from {$this->owner->name} but it only has {$this->contents[$CUR]}.", '#faa', CNS_ITEMS);
			return false;
		}
		$this->contents[$CUR] -= $amount;
		if ($this->contents[$CUR] < 1) unset($this->contents[$CUR]);
		return true;
	}

	public function getAmount($CUR, $formatted = false)
	{
		$amount = isset($this->contents[$CUR]) ? $this->contents[$CUR] : 0;

		if ($formatted)
		{
			return getFormattedAmount($amount, $CUR);
		}
		else
		{
			return $amount;
		}
	}

	public function getWalletString()
	{
		if (empty($this->contents)) return;
		$walletString = '';

		foreach ($this->contents as $CUR => $amount)
		{
			if ($amount == 0) continue;
			if (empty($walletString)) $walletString = getFormattedAmount($amount, $CUR, true);
			else $walletString .= '<br>' . getFormattedAmount($amount, $CUR, true);
		}
		return $walletString;
	}

	public function dumpIntoInventory()
	{
		if (!isset($this->owner->inventory)) return false;

		console_echo('Dumping wallet into inventory.', null, CNS_ITEMS);

		foreach ($this->contents as $CUR => $amount)
		{
			$this->owner->inventory->add(new itm_money($CUR, $amount, true), true);
		}

		return true;
	}
}
