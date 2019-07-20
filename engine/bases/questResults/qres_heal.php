<?php

class qres_heal extends QuestResult
{
	public $hpAmount;
	public $epAmount;

	public function __construct($hpAmount = null, $epAmount = null)
	{
		$this->hpAmount = $hpAmount;
		$this->epAmount = $epAmount;

		if (!isset($hpAmount)) { $hpDesc = 'Heal to full health. '; }
		elseif ($hpAmount > 0) { $hpDesc = "Recover {$hpAmount} health. "; }
		elseif ($hpAmount < 0) { $hpDesc = "Take {$hpAmount} damage. "; }
		else { $hpDesc = ''; }

		if (!isset($epAmount)) { $epDesc = 'Recharge to full energy. '; }
		elseif ($epAmount > 0) { $epDesc = "Recharge {$epAmount} energy. "; }
		elseif ($epAmount < 0) { $epDesc = "Lose {$epAmount} energy. "; }
		else { $hpDesc = ''; }

		parent::__construct(trim($hpDesc . $epDesc));
	}

	public function deliver(Player $recipient)
	{
		$recipient->alterHp(isset($this->hpAmount) ? $this->hpAmount : $recipient->hp_max);
		$recipient->alterEp(isset($this->epAmount) ? $this->epAmount : $recipient->ep_max);
	}
}