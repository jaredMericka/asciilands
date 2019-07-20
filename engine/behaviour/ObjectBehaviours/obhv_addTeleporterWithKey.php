<?php

class obhv_addTeleporterWithKey extends ObjectBehaviour
{
	public $redirectKey;

	public $dest_n_offset;
	public $dest_w_offset;
	public $dest_map;

	public function __construct($dest_n_offset, $dest_w_offset, $dest_map, Item $redirectKey, Sprite $activeSprite)
	{
		$this->onReaction = true;

		if (isset($activeSprite) && $activeSprite instanceof Sprite)
		{
			$this->spriteSet[0] = $activeSprite;
		}

		$this->dest_n_offset = $dest_n_offset;
		$this->dest_w_offset = $dest_w_offset;
		$this->dest_map = (isset($dest_map) ? $dest_map : $GLOBALS['map']->mapPath);

		$this->redirectKey = $redirectKey;

		$description = 'Changes the exit location of a teleport behaviour.';

		parent::__construct($description, BHVK_TELEPORT_MOD, 0);
	}

	public function onReaction(AsObject $instigator, $DIR)
	{
		$change = false;

		if ($instigator instanceof Dude && $this->redirectKey instanceof Item)
		{
			if ($instigator->inventory->hasItem($this->redirectKey) !== false)
			{
				$change = true;
			}
		}
		elseif ($instigator instanceof AsObject && $this->redirectKey instanceof AsObject)
		{
			if ($this->redirectKey->equals($instigator))
			{
				$change = true;
			}
		}



		if ($change)
		{
			$this->owner->addBehaviour(new obhv_teleporter($this->dest_n_offset, $this->dest_w_offset, $this->dest_map));
			if (isset($this->spriteSet[0]))
			{
				$this->owner->sprite = $this->spriteSet[0];
			}
		}

	}
}