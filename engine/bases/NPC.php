<?php

abstract class NPC extends Dude
{
	public $NPCIs = [];

	function __construct($name, $spriteSet, $gender = null, $speechFile = null)
	{
		$this->FAC = FAC_NPC_NEUTRAL;

		parent::__construct($name, $spriteSet, $gender, $speechFile);

		// If something weird is going on, try moving these behaviours before the parent constructor. I changed it without testing it because I'm a BADASS.
		// Knowing what I know now, there is no way this shit should ever be before the parent constructor.
		$this->addBehaviour(
			new dbhv_leaveLootableCorpse($this->spriteSet), // Had to change this from $spriteSet to $this->spriteSet nearly a year later because I'm not as much of a BADASS as I thought.
			new dbhv_fleeWhenAttacked(10),
			new dbhv_speak()
		);

		$newNPCIs = [];
		foreach ($this->NPCIs as $NPCI)
		{
			$NPCI->owner = $this;
			$newNPCIs[$NPCI->key] = $NPCI;
		}
		$this->NPCIs = $newNPCIs;
	}

	public function addNPCI (NPCInteraction $NPCI)
	{
		$NPCI->owner = $this;
		$this->NPCIs[$NPCI->key] = $NPCI;
	}

	public function onEngage(Player $player)
	{
		parent::onEngage($player);

		$update = new stdClass();
		$update->type = 'init';
		$update->NPCIs = [];

		foreach ($this->NPCIs as $key => $NPCI)
		{
			$updateNPCI = new stdClass();

			$updateNPCI->name = $NPCI->name;
			$updateNPCI->desc = $NPCI->description;
			$updateNPCI->key = $NPCI->key;

			$update->NPCIs[] = $updateNPCI;
		}

		update(UPD_INTERACTIONS, $update);
	}

	public function onDisengage(Player $player)
	{
		parent::onDisengage($player);

		clearPanel(UPD_INTERACTIONS);
	}

	public function executeNPCIbehaviours ($TRG, $arg1 = null, $arg2 = null, $arg3 = null)
	{
		console_echo('Running NPCIs as behaviours');
		foreach ($this->NPCIs as $NPCI)
		{
			if ($NPCI->can($TRG))
			{
				$NPCI->$TRG($arg1, $arg2, $arg3);
				$NPCI->triggercooldown();
			}
		}
	}
}