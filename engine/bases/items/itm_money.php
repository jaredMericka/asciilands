<?php

//class itm_money extends Item
//{
//	public $CUR;
//	public $amount;
//
//	function __construct($CUR, $goldValue, $absoluteValue = false)
//	{
//		$currency = $GLOBALS['currencies'][$CUR];
//		$this->CUR = $CUR;
//
//		$goldValue = (mt_rand(0, RAND_MAX) / RAND_MAX) * $goldValue + ($goldValue / 2);
//
//		if ($absoluteValue) $this->amount = $goldValue;
//		else
//		{
//			$this->amount	= round(convertCurrency($goldValue, CUR_GOLD, $CUR), 2);
//		}
//
//		// This stuff is for the parent constructor:
//        $name = "{$this->amount} {$currency}";
//        $description = getFormattedAmount($this->amount, $this->CUR);
//		$sprite = $currency->sprite;
//		parent::__construct($name, $description, $sprite);
//	}
//}
//
//
