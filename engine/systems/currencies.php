<?php

const CUR_GOLD		= 1;
const CUR_FENT		= 2;
const CUR_SHARPS	= 3;
const CUR_VITTIS	= 4;
const CUR_GRODOS	= 5;

$currencies = [
	CUR_GOLD	=> new currency(CUR_GOLD,	'Gold',		'&#x00a4;',	1,		'#fd0'),
	CUR_FENT	=> new currency(CUR_FENT,	'Fent',		'&#x20a3;',	100,	'#f85'),
	CUR_SHARPS	=> new currency(CUR_SHARPS,	'Sharps',	'&#x0424;',	250,	'#ddd'),
	CUR_VITTIS	=> new currency(CUR_VITTIS,	'Vittis',	'&#x040f;',	800,	'#fa0'),
	CUR_GRODOS	=> new currency(CUR_GRODOS,	'Grodos',	'&#x0431;',	640,	'#c53'),
];

function spr_money($coinColour)
{
	$coins = [
		new SpriteElement(null, $coinColour, '.'),
		new SpriteElement(null, $coinColour, ':'),
		new SpriteElement(null, $coinColour, '&bull;'),
		new SpriteElement(null, $coinColour, '&bull;'),
		null,
		null,
	];

	shuffle($coins);
	$coins = array_filter($coins);
	return new Sprite($coins);
}

function convertCurrency($amount, $curFrom, $curTo)
{
	global $currencies;
	return ($amount / $currencies[$curFrom]->goldPrice) * $currencies[$curTo]->goldPrice;
}

function getFormattedAmount($amount, $CUR, $convertFromGold = false)
{
	console_echo('Getting formatted amount.');

	if ($convertFromGold) $amount = convertCurrency($amount, CUR_GOLD, $CUR);

	$amount = number_format($amount);

	global $currencies;
//	return "{$currencies[$CUR]->symbol}&nbsp;" . number_format($amount, 2);
	return "{$currencies[$CUR]->symbol}&nbsp;{$amount}";
}

class currency
{
	public $key;
	public $name;
	public $symbol;
	public $goldPrice;
	public $coinColour;

	private $sprites = [];

	function __construct($key, $name, $symbol, $goldPrice, $coinColour)
	{
		$this->key			= $key;
		$this->name			= $name;
		$this->symbol		= $symbol;
		$this->goldPrice	= $goldPrice;
		$this->coinColour	= $coinColour;

		for ($i = 0; $i <= 3; $i++)
		{
			$this->sprites[] = spr_money($coinColour);
		}
	}

	public function __get($name)
	{
		return ($name == 'sprite' ? $this->sprites[mt_rand(0, 3)] : null);
	}

	function __toString()
	{
		return $this->name;
	}
}


class itm_money extends Item
{
	public $CUR;
	public $amount;

	function __construct($CUR, $goldValue, $absoluteValue = false)
	{
		$currency = $GLOBALS['currencies'][$CUR];
		$this->CUR = $CUR;

		if ($absoluteValue) $this->amount = $goldValue;
		else
		{
			$goldValue = (mt_rand(0, RAND_MAX) / RAND_MAX) * $goldValue + ($goldValue / 2);
			$this->amount	= round(convertCurrency($goldValue, CUR_GOLD, $CUR), 2);
		}

		// This stuff is for the parent constructor:
        $name = "{$this->amount} {$currency}";
        $description = getFormattedAmount($this->amount, $this->CUR);
		$sprite = $currency->sprite;

		console_echo("Made a money item: $description", '#ffa');

		parent::__construct($name, $description, $sprite);
	}
}
