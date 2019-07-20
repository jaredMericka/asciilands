<?php

class NPC_vendor extends NPC
{
	public $CUR;

	public function __construct($name, $spriteSet, $gender = GND_MALE, $speechFile = null, $CUR = null, $NPCIs = null, $wares = [])
	{
		$canBuy = isset($canBuy) ? $canBuy : true;
		$canSell = isset($canSell) ? $canSell : true;

		$this->FAC = FAC_NPC_NEUTRAL;

		$this->CUR = isset($CUR) ? $CUR : CUR_GOLD;

		parent::__construct($name, $spriteSet, $gender, $speechFile);

		if ($NPCIs instanceof NPCInteraction) $NPCIs = [$NPCIs];

		if (is_array($NPCIs))
		{
			foreach ($NPCIs as $NPCI)
			{
				$this->addNPCI($NPCI);
			}
		}

		global $currencies; //XXX
		console_echo("{$this->name} deals in {$currencies[$this->CUR]->name}");

		$this->inventory->CUR = $this->CUR;
		foreach ($wares as $thing)
		{
			$this->inventory->add($thing);
		}
	}
}