<?php

class Scenery
{
    public $key;
    public $sprite;

	public $TPL_borders;

	public $minimap;

    function __construct($sprite, $TPL_borders = null, $minimap = null)
    {
        $this->sprite = $sprite;
		if (is_numeric($TPL_borders))
		{
			$this->TPL_borders = [
				DIR_NORTH	=> $TPL_borders,
				DIR_SOUTH	=> $TPL_borders,
				DIR_EAST	=> $TPL_borders,
				DIR_WEST	=> $TPL_borders,
			];
		}
		elseif (is_array($TPL_borders))
		{
			$this->TPL_borders = $TPL_borders;
		}
		else
		{
			$this->TPL_borders = [];
		}

		$this->minimap = isset($minimap) ? $minimap : array_search(TPL_HIGHOBSTACLE, $this->TPL_borders) !== false;
    }

    function getJS()
    {
        return $this->sprite->getJS();
    }
}


