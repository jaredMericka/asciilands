<?php

class dbhv_giveRandomItem extends DudeBehaviour
{
	public $maxOccurrences;

	public function __construct($maxOccurrences = null)
	{
		$this->onReaction = true;
		$this->onIdle = true;

		$this->maxOccurrences = $maxOccurrences;

		$description = 'Gives a random item from the inventory to the instigator.';

		parent::__construct($description, BHVK_TRANSACTION, 3);
	}

	public function onIdle()
	{
		if (isset($this->owner->CUR) && $this->owner->CUR)
		{
			console_echo("Looks like <<#fff>>{$this->owner->name}<> is some kind of vendor. Removing <<#faa>>dbhv_giveRandomItem<>.", '#aff', CNS_BEHAVIOUR);
			$this->delete();
		}
		else
		{
			console_echo("<<#fff>>{$this->owner->name}<> isn't a vendor so <<#faa>>dbhv_giveRandomItem<> can stay.", '#aaf', CNS_BEHAVIOUR);
			$this->onIdle = false;
		}
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		if ($instigator instanceof Dude)
		{
			if ($this->owner->inventory->getItemCount())
			{
				if ($this->maxOccurrences !== null)
				{
					if (!$this->maxOccurrences > 0)
					{
						if ($instigator instanceof Player) $this->owner->speak("I found this {$item->name} but I think I'll hold onto it.");
						return;
					}
					else
					{
						$this->maxOccurrences --;
					}
				}

				$item = $this->owner->inventory->pullItem();
				$instigator->inventory->add($item);
				if ($instigator instanceof Player) $this->owner->speak(SPSI_GIVING, $item, true);
				console_echo("{$this->owner->name} has given $item->name to $instigator->name.", '#aaa');		//XXX
			}
		}
		return false;
	}
}
