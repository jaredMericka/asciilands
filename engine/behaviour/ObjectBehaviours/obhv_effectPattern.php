<?php

class obhv_effectPattern extends ObjectBehaviour
{
	public $pattern;

	public $n_offset;
	public $w_offset;

	public $frame = 0;

	public $DMGs;
	public $DMGDL;
	public $TEQT;

	public $attack;
	public $statuses;

	public function __construct ($n_offset, $w_offset, $pattern, Sprite $sprite,
//		$attack = null,
		$DMGs = null,
		$DMGDL = null,
		$TEQT = null,
		$statuses = null)
	{
		$this->onIdle = true;
		$this->onRegister = true;

		$this->n_offset		= $n_offset;
		$this->w_offset		= $w_offset;

		$this->pattern		= $pattern;

		$this->spriteSet	= [$sprite];

		$this->DMGs			= $DMGs;
		$this->DMGDL		= $DMGDL;
		$this->TEQT			= $TEQT;

//		$this->attack		= $attack;
		$this->statuses		= $statuses;

		$description = 'Does stuff in a pattern';

		parent::__construct($description, id(), MIN_COOLDOWN);
	}

	public function onRegister()
	{
		if (isset($this->DMGs, $this->DMGDL, $this->TEQT))
		{
			$this->attack = new Attack($this->owner, $this->DMGDL, $this->DMGs, $this->TEQT);
			$this->attack->alwaysReady = true;
			$this->attack->alwaysHit = true;

			console_echo('Attack successfully added to pattern behaviour.', '#aaf');
		}
		else
		{
			console_var_dump($this->DMGs);
			console_var_dump($this->DMGDL);
			console_var_dump($this->TEQT);
		}

		parent::onRegister();
	}

	public function onIdle()
	{
		if (!isset($this->pattern[$this->frame]))
		{
			$this->delete();
			return;
		}

		global $map;

		$effects = [];

		foreach ($this->pattern[$this->frame] as $coOrds)
		{
//			$coOrds[0] += $this->n_offset;
//			$coOrds[1] += $this->w_offset;
			$n_offset = $coOrds[0] + $this->n_offset;
			$w_offset = $coOrds[1] + $this->w_offset;

			$noEffect = false;

			if (isset($map->scenery[$n_offset][$w_offset]))
			{
				// Oosenupt - this is bullshit. I guess scenery will need to prescribe to the layer system after all. Damn.
				$noEffect = $map->scenery[$n_offset][$w_offset]->TPL_borders === [
					DIR_NORTH => TPL_HIGHOBSTACLE,
					DIR_SOUTH => TPL_HIGHOBSTACLE,
					DIR_EAST => TPL_HIGHOBSTACLE,
					DIR_WEST => TPL_HIGHOBSTACLE,
				];
			}

			if (!$noEffect)
			{
				$effect  = new Effect($this->spriteSet[SPRI_DEFAULT],
					$n_offset, $w_offset,
					1);


				$effects[] = $effect;
			}

			if (isset($map->objects[$n_offset][$w_offset][LAYER_DUDE]))
			{
				console_echo('Pattern has touched a dude!', '#ffa');
				$dude = &$map->objects[$n_offset][$w_offset][LAYER_DUDE];

				if (isset($this->attack))
				{
					$this->attack->execute($dude);
				}
			}
		}

		$map->addEffects($effects);

		$this->frame++;
	}
}