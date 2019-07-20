<?php

class macro
{
	var $object;
	var $array;

	public function __construct()
	{
		$this->object = new AsObject();
		$this->array = ['a', 'b', 'c', 1, 2, 3];
	}
}

class micro
{
	var $object;
	var $array;

	public function __construct()
	{
		$this->object = new AsObject();
		$this->array = ['a', 'b', 'c', 1, 2, 3];

		$this->object = serialize($this->object);
		$this->array = serialize($this->array);
	}
}

class Object
{
	var $number = 5;
	var $float = 4234.65245345;
	var $string = 'Abadacus rudkin herrebus';
	var $array = [4, 6, 234, 'nine'];
}

$macro = new macro();
$micro = new micro();

$macroStart = microtime();

for ($i = 0; $i < 10000; $i++)
{
	$macro = serialize($macro);
	$macro = unserialize($macro);
}

$macroEnd = microtime();

$microStart = microtime();

for ($i = 0; $i < 10000; $i++)
{
	$micro = serialize($micro);
	$micro = unserialize($micro);
}

$microEnd = microtime();

?>

<html>
	<head>

	</head>
	<body>
		Macro: <?php echo $macroEnd - $macroStart; ?>
		<br>
		Micro: <?php echo $microEnd - $microStart; ?>
	</body>
</html>




