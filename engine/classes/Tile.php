<?php

class Tile
{
    public $key;

    public $bg;
    public $fg;
    public $chars;
    public $static;

	public $TPL;

    public $canEnter;
	public $overPlayer;
	public $scatterDilution;

	public $jsonObject;

	public $singular;


    function __construct($bg, $chars, $TPL = null, $fg_disparity = null, $overPlayer = null, $scatterDilution = null)
    {
		if (!isset($TPL)) $TPL = TPL_OPENGROUND;
		if (!isset($fg_disparity)) $fg_disparity = 2;

		if (!isset($overPlayer) || !isset($TPL)) $overPlayer = false;

        $this->bg		= $bg;
        $this->fg		= is_int($fg_disparity) ? tint($bg, $fg_disparity, true) : $fg_disparity;
        $this->chars	= $chars;
        $this->static	= count($chars) == 6;

		if (is_bool($TPL))
		{
			$this->TPL = $TPL ? TPL_OPENGROUND : TPL_LOWOBSTACLE;
		}
		else
		{
			$this->TPL			= $TPL;
		}
		$this->overPlayer		= $overPlayer;
		$this->scatterDilution	= $scatterDilution ? $scatterDilution : count($this->chars);

		$this->jsonObject = new stdClass();
		$this->jsonObject->c = $this->chars;
		$this->jsonObject->d = $this->scatterDilution;

		if ($overPlayer && ($GLOBALS['player'] instanceof EditorPlayer ||$GLOBALS['player'] instanceof EditorPlayer2)) $this->fg = '#f0f';
    }

	function __clone()
	{
//		$this->bg = $this->tint($this->bg, -1);
//		$this->fg = $this->tint($this->fg, -1);
	}

	public function tint($amount = -1)
	{
		$newTile = clone $this;

		$newTile->bg = tint($this->bg, $amount, true);
		$newTile->fg = tint($this->fg, $amount, true);

		return $newTile;
	}

    function render($id)
    {
        echo "<div class=\"$this->key\" id=\"t$id\">";
        if ($this->static)
        {
            for ($c = 0; $c <= 5; $c++)
            {
                echo $this->chars[$c];
                if ($c == 2) echo '<br>';
            }
        }
        else
        {
            for ($c = 0; $c <= 5; $c++)
            {
                echo $this->chars[mt_rand(0,count($this->chars) - 1)];
                if ($c == 2) echo '<br>';
            }
        }
        echo '</div>';
    }

    function getCSS()
    {
		$css = ".$this->key { background-color:{$this->bg}; color:{$this->fg}; } ";
		if ($this->overPlayer) $css .= ".$this->key span { background-color:{$this->bg} !important; color:{$this->fg} !important; } ";

        return $css;
    }

    function getJS()
    {
//		return "var rt_{$this->key} = " . json_encode($this->chars);
		return "var rt_{$this->key} = " . json_encode($this->jsonObject);
    }

//    public function collide()
//    {
//        return $this->canEnter;
//    }
}


