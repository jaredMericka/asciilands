<?php

class dbhv_crippleOnHit extends DudeBehaviour
{
	public $status;

	function __construct($speedPercentage, $strengthPercentage, $cooldown = 0)
	{
		$this->onStrike = true;

		$DSs = [
			DS_SPEED => $speedPercentage . '%',
			DS_SPEED_FAST => $speedPercentage . '%',
			DS_STRENGTH => '-' . $strengthPercentage . '%',
		];

		$statusDescription = "Speed reduced by {$speedPercentage}%, Strength reduced by {$strengthPercentage}%";

		$sprite = new Sprite([
			0 => new SpriteElement(null, '#f00', '\\'),
			1 => new SpriteElement(null, '#f00', 'v'),
			2 => new SpriteElement(null, '#f00', '/'),
			3 => new SpriteElement('#fda', '#000', '-'),
			4 => new SpriteElement('#fda', '#000', '_'),
			5 => new SpriteElement('#fda', '#000', '-'),
			]);

		$this->status = new Status('Crippled', $statusDescription, $sprite, 8, false, $DSs); // This needs to be created AT THE TIME OF USE! fix this.

		$description = "On hit, cripples the target which has its speed reduced by {$speedPercentage}% and strength reduced by {$strengthPercentage}%";
		parent::__construct($description, 'crip', $cooldown);
	}

	function onStrike(Attack $attack)
	{
		console_echo("Adding cripple status to {$attack->target->name}", '#faa');
		$attack->target->addStatus(clone $this->status);
	}
}