<?php

trait inheritArray
{
	function getArray()
	{
		return $this->array + parent::getArray();
	}
}

class test1
{
	private $array =
	[
		1 => 'test1 1',
		2 => 'test1 2',
		3 => 'test1 3',
	];

	function getArray() { return $this->array; }
}

class test2 extends test1
{
	use inheritArray;

	private $array =
	[
		1 => 'test2 1',
		2 => 'test2 2',
	];

}

class test3 extends test2
{
	use inheritArray;

	private $array =
	[
		1 => 'test3 1',
		4 => 'test3 4',
	];

}

$test2 = new test3();

var_dump($test2->getArray());