<?php

class thing
{
	var $a;
	var $b;

	public function __construct($clone = false)
	{
		if ($clone)
		{
			global $thingInstance;
			$this = &$thingInstance;
			return;
		}

		$this->a = mt_rand(5, 555);
		$this->b = mt_rand(5, 555);
	}
}

$thingInstance = new thing(false);
$thingInstance2 = new thing(true);

echo $thingInstance === $thingInstance2 ? 'The same' : 'Different';

echo "\n\n";

var_dump($GLOBALS);