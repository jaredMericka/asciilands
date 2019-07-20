<?php

class obj_portal extends AsObject

{
	public function __construct($name, $spriteSet, $teleport_n_offset, $teleport_w_offset, $MAP = null, $strip_DIR = null, $strip_length = null)
	{
		$this->addBehaviour(
			new obhv_teleporter($teleport_n_offset, $teleport_w_offset, $MAP)
		);

		if (isset($strip_DIR, $strip_length))
		{
			$start = 1;

			switch ($strip_DIR)
			{
				case DIR_NORTH:
					$strip_length = 0 - $strip_length;
					$start = -1;
				case DIR_SOUTH:
					for ($n_offset = $start; $n_offset < $strip_length; $n_offset ++)
					{
						$this->constituents[$n_offset][0] = new ObjectConstituent($spriteSet);
					}
					break;

				case DIR_WEST:
					$strip_length = 0 - $strip_length;
					$start = -1;
				case DIR_EAST:
					for ($w_offset = $start; $w_offset < $strip_length; $w_offset ++)
					{
						$this->constituents[0][$w_offset] = new ObjectConstituent($spriteSet);
					}
					break;
			}
		}

		parent::__construct($name, $spriteSet, LAYER_PORTAL);
	}
}

