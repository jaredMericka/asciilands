<?php

class SpriteElement
{
    public $bg;
    public $fg;
    public $char;

    function __construct($background, $foreground, $character)
    {
//		if (is_object($background)) console_warning('Fucked up sprite element!');	//XXX
//		if (is_object($foreground)) console_warning('Fucked up sprite element!');	//XXX

        $this->bg = $background;
        $this->fg = $foreground;
		if (!$character) $character = '&nbsp;';
        $this->char = $character === ' ' ? '&nbsp;' : $character;
    }

    function __toString()
    {
        return '<span style=\'' .
                (isset($this->bg) ? "background-color:{$this->bg};" : null) .
                (isset($this->fg) ? "color:{$this->fg};" : null) .
                "'>{$this->char}</span>";
    }

	function equals(SpriteElement $compare)
	{
		return $this->bg === $compare->bg
			&& $this->fg === $compare->fg
			&& $this->char === $compare->char;
	}
}

class Sprite
{
    public $key;
	public $frames = [];

//	public $jsonObject;

    function __construct($frames)
    {
		if (is_array(current($frames))) $this->frames = $frames;
		else $this->frames[] = $frames;

		foreach ($this->frames as $index => $frame) $this->frames[$index] = array_filter($frame);

//		foreach ($this->frames as $frame)
//		{
//			foreach ($frame as $element)
//			{
//				if (!($element instanceof SpriteElement))
//				{
//					console_echo('WTF IS THIS DOING IN HERE???', '#faa');
//					console_var_dump($element, '#faa');
//				}
//			}
//		}


    }

	function getHTML()
	{
		$html = '<div style="display:inline-block;">';

		$html .= isset($this->frames[0][0]) ? $this->frames[0][0] : '&nbsp;';
		$html .= isset($this->frames[0][1]) ? $this->frames[0][1] : '&nbsp;';
		$html .= isset($this->frames[0][2]) ? $this->frames[0][2] : '&nbsp;';
		$html .= '<br>';
		$html .= isset($this->frames[0][3]) ? $this->frames[0][3] : '&nbsp;';
		$html .= isset($this->frames[0][4]) ? $this->frames[0][4] : '&nbsp;';
		$html .= isset($this->frames[0][5]) ? $this->frames[0][5] : '&nbsp;';

		return $html . '</div>';
	}

	function getJS()
	{
		return "var spr_{$this->key}=" . json_encode($this->getJsonObject()) . ";";
	}

	function getJsonObject()
	{
		$jsonObject = new stdClass();

		$jsonObject->f = 0;

		foreach($this->frames as $fIndex => $frame)
		{
			$jsonObject->{"f{$fIndex}"} = new stdClass();

			foreach ($frame as $eIndex => $element)
			{
				$jsonObject->{"f{$fIndex}"}->{"e{$eIndex}"} = "{$element}"; // Terrible
			}
			$jsonObject->f++;
		}

		return $jsonObject;
	}

	function getBodyColour()
	{
		if(isset($this->frames[0][4]->fg))
			return $this->frames[0][4]->fg;

		console_echo('Couldn\'t find an appropriate body colour for this sprite:'. console_sprite($this), '#faa');
		return '#fff';
	}

	public function augment($overSprite)
	{
		$osFrames = count($overSprite->frames);
		$hostFrames = count($this->frames);

		$frames = $osFrames > $hostFrames ? $osFrames : $hostFrames;

		$newSpriteFrames = [];

		for ($f = 0; $f < $frames; $f++)
		{
			for ($e = 0; $e <= 5; $e++)
			{
				if (isset($overSprite->frames[$f % $osFrames][$e]))
				{
					$newSpriteFrames[$f][$e] = $overSprite->frames[$f % $osFrames][$e];
				}
				elseif (isset($this->frames[$f % $hostFrames][$e]))
				{
					$newSpriteFrames[$f][$e] = $this->frames[$f % $hostFrames][$e];
				}
			}
		}

		return new Sprite($newSpriteFrames);
	}

	function change(Sprite $sprite, $augment = true)
	{
		update_sprite($this->key, $sprite, $augment);
		$this->frames = $sprite->frames;
	}

	function getMainColour()
	{
		$vote = [];
		foreach ($this->frames as $frame)
		{
			foreach ($frame as $element)
			{
				if (isset($element->bg))
				{
					if (isset($vote[$element->bg]))
					{
						$vote[$element->bg] += 2;
					}
					else
					{
						$vote[$element->bg] = 2;
					}
				}

				if (isset($element->fg))
				{
					if (isset($vote[$element->fg]))
					{
						$vote[$element->fg] += 1;
					}
					else
					{
						$vote[$element->fg] = 1;
					}
				}
			}
		}

		//console_echo( 'Main sprite colour: ' . array_search(max($vote), $vote));

		return array_search(max($vote), $vote);
	}

	function equals(Sprite $sprite)
	{

		foreach ($this->frames as $frameNo => $frame)
		{
			if (array_keys($frame) !== array_keys($sprite->frames[$frameNo])) return false;

			foreach ($frame as $index => $element)
			{

				if (!$sprite->frames[$frameNo][$index]->equals($element)) return false;
			}
		}
		return true;
	}

	function __clone()
	{
		$frames = [];

		foreach ($this->frames as $frame => $elements)
		{
			foreach ($elements as $index => $element)
			{
				$frames[$frame][$index] = clone $element;
			}
		}

		$this->frames = $frames;
	}

	public function __debugInfo()
	{
		return [
			'key'		=> $this->key,
			'frames'	=> console_sprite($this)
		];
	}

//	public function __toString()
//	{
//		console_var_dump($this);
//		return '';
//	}
}

function flipSprite (Sprite $sprite)
{
	$frames		= [];
	$frameNo	= 0;
	foreach ($sprite->frames as $frame)
	{
		foreach ($frame as $index => $element)
		{
			$frames[$frameNo][$index + ($index > 2 ? -3 : 3)] = $element;
		}
		$frameNo++;
	}

	return new Sprite($frames);
}

function paintSprite (Sprite $sprite, $colour)
{
//	$sprite = clone $sprite;

	$new_frames = [];

	foreach ($sprite->frames as $frame)
	{
		$new_frame = [];

		foreach ($frame as $index => $element)
		{
//			if (isset($element->bg)) $element->bg = $colour;
//			if (isset($element->fg)) $element->fg = $colour;

			$new_frame[$index] = new SpriteElement(
				isset($element->bg) ? $colour : null,
				isset($element->fg) ? $colour : null,
				$element->char);
		}

		$new_frames[] = $new_frame;
	}

	return new Sprite($new_frames);
}

function halveSprite(Sprite $sprite, $takeTop = true)
{
	$sprite = clone $sprite;
	if ($takeTop)
	{
		foreach ($sprite->frames as &$frame)
		{
			unset($frame[3]);
			unset($frame[4]);
			unset($frame[5]);
		}
	}
	else
	{
		foreach ($sprite->frames as &$frame)
		{
			unset($frame[0]);
			unset($frame[1]);
			unset($frame[2]);
		}
	}

	console_echo('Halved sprite: ' . console_sprite($sprite), '#afa');

	return new Sprite($sprite->frames);
}

function tileToSprite (Tile $tile)
{
	if (count($tile->chars) === 6)
	{
		return new Sprite([
			new SpriteElement($tile->bg, $tile->fg, $tile->chars[0]),
			new SpriteElement($tile->bg, $tile->fg, $tile->chars[1]),
			new SpriteElement($tile->bg, $tile->fg, $tile->chars[2]),
			new SpriteElement($tile->bg, $tile->fg, $tile->chars[3]),
			new SpriteElement($tile->bg, $tile->fg, $tile->chars[4]),
			new SpriteElement($tile->bg, $tile->fg, $tile->chars[5]),
		]);
	}
	else
	{
		$frames = [];

		for ($i = 0; $i <= 3; $i++)
		{
			$frame = [];

			for ($j = 0; $j <= 5; $j ++)
			{
				$frame[$j] = new SpriteElement($tile->bg, $tile->fg, $tile->chars[array_rand($tile->chars)]);
			}

			$frames[] = $frame;
		}

		return new Sprite($frames);
	}
}