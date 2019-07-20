<?php

$sylables = mt_rand(1, 4);

$word = '';

for ($s = 1; $s <= $sylables; $s++)
{
	$word = appendSylable($word);
}

echo ucwords($word);

function appendSylable (& $word)
{
	$consts_start	= str_split('bcdfghjklmnprstvwz');
	$consts	= str_split('bcdfghjklmnprstvwxyz');
	$vowels	= str_split('aeiou');

	$first = $word === '';

	// [Add this letter] => [if this letter is last]
	$nextLetterKey = [
		'r' => str_split('bcdfgkpt'),
		'h' => str_split('scg'),
		'l' => str_split('bcfgks'),
		'y' => str_split('eo')
	];

	$lastLetter = substr($word, -1, 1);

	if (mt_rand(0,3) || in_array($lastLetter, $vowels))	$word .= ar($first ? $consts_start : $consts);

	if (mt_rand(0,2))
	{
		$lastLetter = substr($word, -1, 1);
		$nextLetters = [];

		foreach ($nextLetterKey as $nextLetter => $lastLetters)
		{
			if (in_array($lastLetter, $lastLetters))
			{
				$nextLetters[] = $nextLetter;
			}
		}

		if ($nextLetters) $word .= ar($nextLetters);
	}

	$word .= ar($vowels);
//	if (mt_rand(0,1)) $sylable .= ar($consts);

	return $word;
}

function ar ($array) { return $array[array_rand($array)]; }