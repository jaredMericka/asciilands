<?php

class dbhv_leaveLootableCorpse extends DudeBehaviour
{
	function __construct($spriteSet)
	{
		$this->onDeath = true;

		if (isset($spriteSet[SPRI_CORPSE]))
		{
			$this->spriteSet[SPRI_CORPSE] = $spriteSet[SPRI_CORPSE];
		}
		else
		{
			$this->spriteSet[SPRI_CORPSE] = Dude::getCorpseSprite($spriteSet[SPRI_DEFAULT]);
		}

		$description  = 'Leaves a lootable corpse.';
		parent::__construct($description, BHVK_CORPSE, 1);
	}

	function onDeath(Attack $attack)
	{
		global $map;

		console_echo("Leaving corpse of {$this->owner->name}", '#faf');

		if (isset($this->owner->spriteSet[SPRI_CORPSE]))
		{
			$this->spriteSet[SPRI_CORPSE] = $this->owner->spriteSet[SPRI_CORPSE];
			$this->owner->changeLayer(LAYER_CHEST);
			$this->owner->setSPRI(SPRI_CORPSE);
		}

		$obj_corpse = new obj_corpse(
			"Corpse of {$this->owner->name}",
			[$this->spriteSet[SPRI_CORPSE]],
			$this->owner->inventory->contents);

		$obj_corpse->n_offset = $this->owner->n_offset;
		$obj_corpse->w_offset = $this->owner->w_offset;

		if (isset($map->objects[$this->owner->n_offset][$this->owner->w_offset][LAYER_COLLECTIBLE]))
		{
			$existingObject = $map->objects[$this->owner->n_offset][$this->owner->w_offset][LAYER_COLLECTIBLE];

			$obj_corpse->inventory->locked = false;
			if (isset($existingObject->item))
			{
				$obj_corpse->inventory->add($existingObject->item);
			}
			elseif (isset($existingObject->inventory))
			{
				$existingObject->inventory->locked = false;

				foreach ($existingObject->inventory->contents as $item)
				{
					$obj_corpse->inventory->add($existingObject->inventory->pullItem($item));
				}

				$existingObject->inventory->locked = true;
			}
			$obj_corpse->inventory->locked = true;
		}
		console_echo("<<#fff>>\"{$this->owner->name}\"<> is about to be a corpse.");
		$this->owner->changeTo($obj_corpse);
		console_echo("<<#fff>>\"{$this->owner->name}\"<> should now be a corpse.");
	}


}