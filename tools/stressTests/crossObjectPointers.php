<?php

$thing3 = 'bleh';

class TestClass
{
	public $thing1;
	public $thing2;

	public function __construct($thing1, $thing2)
	{
		$this->thing1 = $thing1;
		$this->thing2 = $thing2;
	}
}

class PointerClass
{
	public $pointer1;
	public $pointer2;
	public $pointer3;

	public function __construct(TestClass $testClass)
	{
		$this->pointer1 = &$testClass->thing1;
		$this->pointer2 = &$testClass->thing2;

		global $thing3;

		$this->pointer3 = &$thing3;
	}
}

$testClass = new TestClass('abadacus', 'rudkin');
$pointerClass = new PointerClass($testClass);

var_dump($pointerClass);

$thing3 = 'garble';
$testClass->thing1 = 'shhhh HO';
$testClass->thing2 = 'hrrrbrus';

var_dump($pointerClass);