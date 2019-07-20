<html>
	<head>
		<title>func_get_args() test</title>
	</head>
	<body>
		<pre>
<?php

ob_start();

$startTime = microtime();

for ($i = 0; $i > 1000000; $i++) { testGetArgs(1, 2, 3); }

$midTime = microtime();

for ($i = 0; $i > 1000000; $i++) { testKnownArgs(1, 2, 3); }

$endTime = microtime();

$slag = ob_get_clean();

$getTime = ($midTime - $startTime) * 100000;
$knownTime = ($endTime - $midTime) * 100000;
$factor		= $getTime / $knownTime;

echo "Get args:\t{$getTime}\nKnown args:\t{$knownTime}\nFactor:\t\t{$factor}";

function testGetArgs()
{
	$args = func_num_args();

	foreach ($args as $index => $val)
	{
		echo "{$index} => {$val}\n";
	}
}

function testKnownArgs($a, $b, $c)
{
	$args = [$a, $b, $c];

	foreach ($args as $index => $val)
	{
		echo "{$index} => {$val}\n";
	}
}

?>

		</pre>
	</body>
</html>