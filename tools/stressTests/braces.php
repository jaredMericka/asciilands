<?php

$array1 = [
	'key1' => 'bogus',
	'key2' => 'testValue1',
	'key3' => 'testValue3',
];

$array2 = [
	'key1',
	'key2'
];

$tv1 = 'testValue1';
$tv2 = 'testValue2';
$tv3 = 'testValue3';

$testClass = new stdClass();

$testClass->testValue1 = 'yex plox';
$testClass->testValue2 = 'no ty';
$testClass->testValue3 = ['gud' => 'yeppus', 'bad' => 'noppus'];

echo $testClass->$array1['key2'];
echo $testClass->$tv3['gud'];

// Nope, this shit doesn't work