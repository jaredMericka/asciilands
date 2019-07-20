<?php

class Status
{
	public $key;

	public $name;
	public $description;
	public $sprite;
	public $duration;
	public $canStack;

	public $expiry;

	public $DSs			= [];
	public $DMGs		= [];
	public $DMGs_def	= [];

	public $behaviours;
	public $spriteEffects	= [];


	public function __construct($name, $description, $sprite, $duration, $canStack = null, $DSs = null, $DMGs = null, $DMGs_def = null, $behaviours = null)
	{
		$this->name			= $name;
		$this->description	= $description;
		$this->duration		= $duration;
		$this->sprite		= $sprite;
		$this->canStack		= isset($canStack) ? $canStack : false;

		$this->DSs		= is_array($DSs) ? $DSs : [];
		$this->DMGs		= is_array($DMGs) ? $DMGs : [];
		$this->DMGs_def	= is_array($DMGs_def) ? $DMGs_def : [];

		if (isset($behaviours))
		{
			if (is_array($behaviours))
			{
				$this->behaviours = $behaviours;
			}
			else
			{
				$this->behaviours = [$behaviours];
			}
		}
	}

	public function deferExpiry($duration)
	{
		$this->expiry += $duration;
	}

	public function equals(Status $dudeStatus)
	{
		return
			$this->name				== $dudeStatus->name
//			&& $this->description	== $dudeStatus->description
//			$this->DSs		== $dudeStatus->DSs			&&
//			$this->DMGs		== $dudeStatus->DMGs		&&
//			$this->DMGs_def	== $dudeStatus->DMGs_def
			;
	}

	public function getGoldValue()
	{
		return 5; //oosenupt
	}
}

trait StatusCapability
{
	public $statuses		= [];
	public $nextStatusCheck = INF;

	function addStatus(Status $status)
	{
		global $player;

		console_echo("Adding status <<#fff>>{$status->name}<> to <<#fff>>{$this->name}<>.", '#aaa');
		$status->expiry = $_SERVER['REQUEST_TIME_FLOAT'] + $status->duration;

		$clash = false;
		foreach ($this->statuses as $index => $existingStatus)
		{
			if ($status->equals($existingStatus))
			{
				$clash = !$status->canStack;
				console_echo('Clash found!', '#fcc');

				if ($clash)
				{
//					$this->statuses[$index]->expiry = $status->expiry;
					$this->removeStatus($this->statuses[$index]);
					console_echo('Replacing status with extended version.', '#fda');
				}
			}
		}

//		if (!$clash)
//		{
			$this->statuses[] = $status;
			console_echo('Adding new status.', '#fcc');

			if ($status->behaviours)
			{
				foreach ($status->behaviours as $status_behaviour)
				{
					$this->addBehaviour($status_behaviour);
				}
			}

			if ($status->spriteEffects)
			{
				foreach ($status->spriteEffects as $spriteEffect)
				{
					$this->addSpriteEffect($spriteEffect);
				}
			}

			if ($this === $player)
			{
				update_stats(array_keys($status->DSs));
				if ($status->DMGs || $status->DMGs_def) update_readiness();
			}
//		}

		if ($status->expiry < $this->nextStatusCheck) $this->nextStatusCheck = $status->expiry;

		if ((isset($status->DSs[DS_SPEED]) || isset($status->DSs[DS_SPEED_FAST])) && isset($this->behaviours[BHVK_MOVEMENT]))
		{
			foreach ($this->behaviours[BHVK_MOVEMENT] as &$behaviour) $behaviour->onRegister();
		}
	}

	function removeStatus(Status $status)
	{
		console_echo("Removing status <<#fff>>{$status->name}<> from <<#afa>>{$this->name}<>.");

		global $player;

		if ($isPlayer = $this === $player) // On purpose
		{
			$update = [];
			$affectedDSs = [];
			$refreshReadiness = false;
		}

		$key = array_search($status, $this->statuses);

		unset($this->statuses[$key]);

		if ((isset($status->DSs[DS_SPEED]) || isset($status->DSs[DS_SPEED_FAST])) && isset($this->behaviours[BHVK_MOVEMENT]))
		{
			foreach ($this->behaviours[BHVK_MOVEMENT] as &$behaviour) $behaviour->onRegister();
		}

		if ($status->behaviours)
		{
			foreach ($status->behaviours as $status_behaviour)
			{
				$this->removeBehaviour($status_behaviour);
			}
		}

		if ($status->spriteEffects)
		{
			foreach ($status->spriteEffects as $spriteEffect)
			{
				$this->removeSpriteEffect($spriteEffect);
			}
		}

		if ($isPlayer)
		{
			$affectedDSs = $affectedDSs + $status->DSs;
			if (!$refreshReadiness && (!empty($status->DMGs) || !empty($status->DMGs_def))) $refreshReadiness = true; // Oosenupt - this is fucked

			update_stats(array_keys($affectedDSs));
			if ($refreshReadiness) update_readiness();
			update(UPD_STATUS, $update);
		}
	}

	function hasStatus(Status $status)
	{

	}

	function checkStatuses()
	{
		global $player;
		global $view;

		if ($isPlayer = $this === $player) // On purpose
		{
			$update = [];
			$affectedDSs = [];
			$refreshReadiness = false;
		}

		$this->nextStatusCheck = INF;

		foreach ($this->statuses as $key => $status)
		{
			if ($status->expiry <= $_SERVER['REQUEST_TIME_FLOAT'])
			{

				$this->removeStatus($status);

//				unset($this->statuses[$key]);
//
//				if ((isset($status->DSs[DS_SPEED]) || isset($status->DSs[DS_SPEED_FAST])) && isset($this->behaviours[BHVK_MOVEMENT]))
//				{
//					foreach ($this->behaviours[BHVK_MOVEMENT] as &$behaviour) $behaviour->onRegister();
//				}
//
//				if ($status->behaviours)
//				{
//					foreach ($status->behaviours as $status_behaviour)
//					{
//						$this->removeBehaviour($status_behaviour);
//					}
//				}
//
//				if ($isPlayer)
//				{
//					$affectedDSs = $affectedDSs + $status->DSs;
//					if (!$refreshReadiness && (!empty($status->DMGs) || !empty($status->DMGs_def))) $refreshReadiness = true; // Oosenupt - this is fucked
//				}
			}
			else
			{
				if ($isPlayer)
				{
					$upd = new stdClass();

					$upd->sprite = $view->addClientSprite($status->sprite)->key;
					$upd->description = $status->description;

					$update[] = $upd;
				}

				if ($status->expiry < $this->nextStatusCheck) $this->nextStatusCheck = $status->expiry;
			}
		}

		if ($isPlayer)
		{
			update_stats(array_keys($affectedDSs));
			if ($refreshReadiness) update_readiness();
			update(UPD_STATUS, $update);
		}
	}
}