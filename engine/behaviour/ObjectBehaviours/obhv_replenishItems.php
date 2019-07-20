<?php

class obhv_replenishItems extends ObjectBehaviour
{
	public $masks;
	public $target;

	const MAX = 10;
	const MIN = 1;
	const DEF = 8;

	public function __construct($masks = null, $target = null)
	{
		$this->onGainItem	= true;
		$this->onLoseItem	= true;
		$this->onIdle		= true;

		if (isset($masks))
		{
			if (!is_array($masks)) $masks = [$masks];

			$this->masks = serialize($masks);
		}

		if ($target)	$this->target = max([self::MIN, min([self::MAX, $target])]);
		else			$this->target = self::DEF;

		parent::__construct('Replenishes items over time', null);
	}

	public function onLoseItem(Item $item)
	{
		if ($this->masks)
		{
			console_echo('Using in-house masks for replenished item', '#aaa', CNS_BEHAVIOUR);
			$masks = unserialize($this->masks);
			$mask = $masks[array_rand($masks)];

			if (!isset($mask->class))
			{
				$mask->class = get_class($item);
			}
		}
		else
		{
			console_echo('Using class-only mask for replenished item', '#aaa', CNS_BEHAVIOUR);
			$mask = new Mask($item,
				[
					'class',
				]);
		}

		$equipment = Equipment::constructFromMask($mask);

		console_echo('Resulting item:', '#aaa');

		$this->owner->inventory->add($equipment);
	}

	public function onGainItem(Item $item)
	{
		$this->onLoseItem = false;

		console_echo('Does this npc have too many items?', '#faa');
		if (count($this->owner->inventory->contents) > $this->target)
		{
			console_echo('TOO MANY ITEMS!', '#ffa');
			$firstItem = reset($this->owner->inventory->contents);
			if ($firstItem instanceof Item)
			{
				console_var_dump($firstItem, '#faf');
				$this->owner->inventory->pullItem($firstItem);
				console_echo('Well we pulled it, now what?', '#faa', CNS_ITEMS);
			}
		}
		else console_echo('SUFFICIENTLY FEW ITEMS!', '#afa');

		$this->onLoseItem = true;
	}

	public function onIdle()
	{
//		if ($this->masks)
//		{
//			console_echo('Using in-house masks for replenished item', '#aaa', CNS_BEHAVIOUR);
//			$masks = unserialize($this->masks);
//			$mask = $masks[array_rand($masks)];
//
//			if (!isset($mask->class))
//			{
//				$mask->class = get_class($item);
//			}
//		}
//		else
//		{
//			console_echo('Using class-only mask for replenished item', '#aaa', CNS_BEHAVIOUR);
//			$mask = new Mask($item,
//				[
//					'class',
//				]);
//		}
//
//		$equipment = Equipment::constructFromMask($mask);
//
//		console_echo('Resulting item:', '#aaa');
//		console_var_dump($equipment, '#aaa');
//
//		$this->owner->inventory->add($equipment);
	}
}