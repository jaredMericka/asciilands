<?php

class tsk_itemCount extends Task
{
	public $EOI = EOI_INVENTORY;

	public $descriptionBase;

	public $item;
	public $requiredCount;
	public $existingCount;

	public $mask;

	public $includeExisting;
	public $matchProperties;

	public $currentCount = 0;

	public function __construct(Mask $mask, $requiredCount, $includeExisting = false)
	{
		$this->mask = $mask;
		$this->requiredCount = $requiredCount;
		$this->includeExisting = $includeExisting;


		if (isset($mask->name)) $name = $mask->name;
		elseif (isset($mask->class)) $name = getReadableClass($mask->class, $requiredCount > 1);

		$this->descriptionBase = "Acquire {$this->requiredCount} {$name}" . ($this->matchProperties ? ' (or similar).' : '.');

		$this->description = $this->descriptionBase;
	}

	/**
	 *
	 * @param type $args
	 * @return type
	 */
	public function check($args)
	{
		$ownerCount = $this->quest->owner->inventory->getItemCount($this->mask);

		if ($this->currentCount !== $ownerCount)
		{
			$this->currentCount = $ownerCount;

			$remaining = $this->requiredCount - $ownerCount;// - ($this->includeExisting ? 0 : $this->existingCount);
			$this->description = $this->descriptionBase . " ({$remaining} to go)";
			update_task($this);
		}

		if ($ownerCount >= $this->requiredCount)
		{
			$this->description = $this->descriptionBase;
			$this->complete();
		}
	}

	function onActivate()
	{
		$this->existingCount = $ownerCount = $this->quest->owner->inventory->getItemCount($this->mask);
		if (!$this->includeExisting)
		{
			$this->requiredCount += $this->existingCount;
		}
		$this->check(null);
	}
}