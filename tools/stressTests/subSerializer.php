<?php

class SubSerializerTestClass
{
	var $integer	= 1234567890;
	var $string		= 'qwertyuiopasdghjklzxcvbnm';
	var $array		= ['asdfasdf', 21334, 'adsfadfs', '9874', 84375903845, 'asdfasdfaretiuroetb'];
}

$class = new SubSerializerTestClass();

$start1 = microtime(true);
for ($i = 0; $i < 99999; $i ++)
{
	$ser = serialize($class);
	$class = unserialize($ser);
}
$end1 = microtime(true);

$start2 = microtime(true);
for ($i = 0; $i < 99999; $i ++)
{
	$class->integer = serialize($class->integer);
	$class->string = serialize($class->string);
	$class->array = serialize($class->array);

	$ser = serialize($class);
	$class = unserialize($ser);

	$class->integer = unserialize($class->integer);
	$class->string = unserialize($class->string);
	$class->array = unserialize($class->array);
}
$end2 = microtime(true);

$dur1 = ($end1 - $start1) * 10000;
$dur2 = ($end2 - $start2) * 10000;

echo "{$dur1}\n{$dur2}";
