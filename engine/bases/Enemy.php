<?php

abstract class Enemy extends Dude
{
	public $obhv_awarenessRange;
	public $obhv_chaseRange;

	public function __construct($name, $spriteSet, $gender = null, $speechFile = null, $dudeStats = null)
	{
		global $player;

		$this->FAC		= FAC_MONSTER;
		$this->canPush	= false;

		parent::__construct($name, $spriteSet, $gender, $speechFile, $dudeStats);

		$this->initialiseLoot();

		$obhv_chase = new obhv_chase($player, 1);

		$this->addBehaviour(
			new obhv_wander(),
			new obhv_addBehaviourOnProximity(null, $obhv_chase, $this->obhv_awarenessRange, $this->obhv_chaseRange),
			new dbhv_leaveLootableCorpse($this->spriteSet)
		);

		unset($this->obhv_awarenessRange);
		unset($this->obhv_chaseRange);
	}

	public function __clone()
	{
		parent::__clone();

		$this->initialiseLoot();
	}

	/**
	* A loot array should look like this(-ish):
	*	$lootArray => [
	*		$oddsOfAppearing => [
	*			$exclusiveItem,
	*			$exclusiveItem,
	*			[
	*				$getAllTheseItems,
	*				$getAllTheseItems,
	*				$getAllTheseItems,
	*			],
	*			$exclusiveItem,
	*		],
	*		$oddsOfAppearing => [
	*			$exclusiveItem,
	*			$exclusiveItem,
	*		]
	*	];
	*
	* Where the odds of appearing a rolled separately for each item but if it's an
	* array, the whole array of items is given together.
	*/
	abstract function getLootArray();

	public function initialiseLoot ()
	{
		$lootArray = $this->getLootArray();

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
						$item->finish();
						console_echo($item->id, null, CNS_ITEMS);
						$this->inventory->add(clone $item, true);
					}

					$got++;
				}

				if ($got >= $max) break;
			}
		}
	}
}