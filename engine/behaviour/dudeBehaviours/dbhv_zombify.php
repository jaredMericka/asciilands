<?php

class dbhv_zombify extends DudeBehaviour
{

	public function __construct($spriteSet, $cooldown = 1)
	{
		$this->onCollision = true;

		$description = "Contact with a dude will turn the dude into a clone.";

		if (isset($spriteSet[SPRI_MALE]))
		{
			$this->spriteSet[SPRI_MALE] = $spriteSet[SPRI_MALE];
			if (isset($spriteSet[SPRI_MALE_CORPSE]))
			{
				$this->spriteSet[SPRI_MALE_CORPSE] = $spriteSet[SPRI_MALE_CORPSE];
			}
			else
			{
				$this->spriteSet[SPRI_MALE_CORPSE] = Dude::getCorpseSprite($spriteSet[SPRI_MALE]);
			}

		}

		if (isset($spriteSet[SPRI_FEMALE]))
		{
			$this->spriteSet[SPRI_FEMALE] = $spriteSet[SPRI_FEMALE];
			if (isset($spriteSet[SPRI_FEMALE_CORPSE]))
			{
				$this->spriteSet[SPRI_FEMALE_CORPSE] = $spriteSet[SPRI_FEMALE_CORPSE];
			}
			else
			{
				$this->spriteSet[SPRI_FEMALE_CORPSE] = Dude::getCorpseSprite($spriteSet[SPRI_FEMALE]);
			}
		}

		parent::__construct($description, BHVK_PRIMARY, $cooldown);
	}

	public function onCollision(AsObject $receiver, $DIR)
	{
		if ($receiver instanceof Dude && !($receiver instanceof Player) && !($receiver instanceof nme_zombie))
		{
			$zombie = clone $this->owner;

			$zombie->name = "Zombified {$receiver->name}";

			if ($receiver->gender === GND_MALE && isset($this->spriteSet[SPRI_MALE]))
			{
				$zombie->spriteSet[SPRI_DEFAULT] = $this->spriteSet[SPRI_MALE];
				$zombie->spriteSet[SPRI_CORPSE] = $this->spriteSet[SPRI_MALE_CORPSE];
			}

			if ($receiver->gender === GND_FEMALE && isset($this->spriteSet[SPRI_FEMALE]))
			{
				$zombie->spriteSet[SPRI_DEFAULT] = $this->spriteSet[SPRI_FEMALE];
				$zombie->spriteSet[SPRI_CORPSE] = $this->spriteSet[SPRI_FEMALE_CORPSE];
			}

			$zombie->sprite = $zombie->spriteSet[SPRI_DEFAULT];

			// AHH SHIT WHAT IS ALL THIS CODE DOING? WHY?

			$receiver->changeTo($zombie);
		}
	}
}