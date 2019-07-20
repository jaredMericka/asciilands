<?php

/**
 * Converts the name or key of a direction into an array contating change offset data.
 *
 * @param type $DIR a direction's name or constant value.
 * @return array Associative / numeric array of offset details.
 */
function directionToOffset($DIR)
{
	$n_offset = 0;
	$w_offset = 0;

	if (is_numeric($DIR))
	{
		switch ($DIR)
		{
			case DIR_NORTH:
				$n_offset = -1;
				break;
			case DIR_SOUTH:
				$n_offset = 1;
				break;
			case DIR_WEST:
				$w_offset = -1;
				break;
			case DIR_EAST:
				$w_offset = 1;
				break;
		}
	}
	elseif (is_string($DIR))
	{
		switch(strtolower($DIR[0]))
		{
			case 'n':
				$n_offset = -1;
				break;
			case 's':
				$n_offset = 1;
				break;
			case 'w':
				$w_offset = -1;
				break;
			case 'e':
				$w_offset = 1;
				break;
		}
	}

	// Goddam, this is some ooold shit
	return [0 => $n_offset, 'n' => $n_offset, 'n_change' => $n_offset, 'n_offset' => $n_offset,
			1 => $w_offset, 'w' => $w_offset, 'w_change' => $w_offset, 'w_offset' => $w_offset];
}

/**
 * Returns a direction code from relative offset variables.
 *
 * @param type $n_offset The north change offset.
 * @param type $w_offset The west change offset.
 * @return int Direction constant value.
 */
function offsetToDirection($n_offset, $w_offset)
{
	console_echo("N:{$n_offset}, W:{$w_offset}", '#aff');

	if ($n_offset === 0)
	{
		return ($w_offset > 0 ? DIR_EAST : DIR_WEST);
	}
	else
	{
		return ($n_offset > 0 ? DIR_SOUTH : DIR_NORTH);
	}
}

function addHeader($header, $body)
{
    header("{$header}: {$body}");
}

function shuffle_assoc(&$array)
{
	$keys = array_keys($array);
	$new = [];

	shuffle($keys);

	foreach ($keys as $key)
	{
		$new[$key] = $array[$key];
	}

	$array = $new;
}

function percentageToBool($percentage, $luck = null, $desirable = true)
{
	if (isset($luck) && $luck !== 0)
	{
		if ($luck < 0)
		{
			$desirable = !$desirable;
			$luck = 0 - $luck;
		}

		$tries = floor($luck / 100);
		$extraRollPercentage = $luck % 100;

		for ($try = $tries + 1; $try > 0; $try--)
		{
			if (percentageToBool($percentage) === $desirable) return $desirable;
		}

		if (percentageToBool($extraRollPercentage)) return percentageToBool($percentage);

		return !$desirable;
	}
	else
	{
		$against = 99;

		if ($percentage > 0 && $percentage < 1)
		{
			$against = (int) ($against * (1 / $percentage));
			$percentage = 1;
		}

		return mt_rand(0, $against) < $percentage;
	}
}


function tintByMax($colour, $max)
{
	$splitColour = str_split(trim($colour, '#'));
	foreach ($splitColour as &$char) { $char = hexdec($char); }

//	return tint($colour, $max - max($splitColour));
	return tint($colour, $max);// - max($splitColour));
}

function tint($colour, $amount, $absolute = true)
{
	if ($amount === 0) return $colour;

//	console_echo("Tinting colour by {$amount} " . ($absolute ? 'absolutely.' : 'relatively'));
//	console_echo("BEFORE: " . console_swatch($colour));

	$colour = str_split(trim($colour, '#'));

	foreach ($colour as &$char) { $char = hexdec($char); }

	if ($absolute)
	{
		foreach($colour as &$char)
		{
			$char += $amount;

			if ($char > 15) $char = 15;
			if ($char < 0) $char = 0;

			$char = dechex($char);
		}
	}
	else
	{
		if ($amount > 0)
		{
			$extreme = min($colour);
			$max = 15;
			$multiplier = ($extreme + $amount) / ($extreme <> 0 ? $extreme : 1);
		}
		else
		{
			$extreme = max($colour);
			$max = 0;
			$multiplier = $extreme / ($extreme - $amount <> 0 ? $extreme - $amount : 1);
		}

		foreach ($colour as &$char)
		{
			if ($char === 0 and $amount > 0) $char ++;

			if ($amount > 0)
			{
				$char = $char * $multiplier;
			}
			else
			{
				$char = $char  * $multiplier;
			}

			if ($char > 15) $char = 15;
			if ($char < 0) $char = 0;

			$char = dechex(round($char));

		}
	}

	$colour = '#' . implode($colour);
//	console_echo('AFTER: ' . console_swatch($colour));

	return $colour;
}

function getBetweenColour($col1, $col2)
{
	$col1 = trim($col1, '#');
	$col2 = trim($col2, '#');

	$c11 = hexdec($col1[0]);
	$c12 = hexdec($col1[1]);
	$c13 = hexdec($col1[2]);

	$c21 = hexdec($col2[0]);
	$c22 = hexdec($col2[1]);
	$c23 = hexdec($col2[2]);

	$c1 = (int) (($c11 + $c21) / 2);
	$c2 = (int) (($c12 + $c22) / 2);
	$c3 = (int) (($c13 + $c23) / 2);

	return '#' . implode('', [dechex($c1), dechex($c2), dechex($c3)]);
}

function getLargestIndex($array)
{
	$largestIndex = 0;
	foreach ($array as $index => $value)
	{
		$largestIndex = ($index > $largestIndex ? $index : $largestIndex);
	}
	return $largestIndex;
}

function getNuancedValue($value, $variationPercentage)
{
	$variationPercentage *= 0.01;

	$values = [
		floor($value - ($value * $variationPercentage)),
		ceil($value + ($value * $variationPercentage))
	];

	return mt_rand(min($values),max($values));
}

function getRandomObjectsByClassList ($objects, $classList)
{
	$returnList = [];
	$giveUp = false;

	do
	{
		shuffle ($objects);
		$giveUp = true;

		foreach($objects as $object)
		{
			foreach($classList as $key => $className)
			{
				if (is_a($object, $className))
				{
					$returnList[$key] = $object;
					unset($classList[$key]);
					$giveUp = false;
					break 2;
				}
			}
		}
	}
	while(!$giveUp && !empty($classList));

	if ($giveUp)
	{
		console_echo('Aborting the building of an object list (this is probabaly a materials problem).', '#faa');

//		console_var_dump($objects, '#ffa');
//		console_var_dump($classList, '#faa');

		return false;
	}

	return $returnList;
}

function col($string)
{
	return str_replace(
		[
			'<<',
			'>>',
			'<>'
		],
		[
			'<span style="color:',
			';">',
			'</span>',
		],
		$string);
}

function valueListToValue($valueList)
{
	$baseValue = 0;
	$multiplier = 1;

	foreach ($valueList as $value)
	{
		if (is_string($value))
		{
			$symbol = substr($value, -1, 1);
			if (!is_numeric($symbol)) $value = trim($value, $symbol);

			switch ($symbol)
			{
				case '%':
					$value = $value / 100;
				case '*':
					$multiplier += $value;
					break;
				default:
					$baseValue += $value;
			}
		}
		else
		{
			$baseValue += $value;
		}
	}

	return $baseValue * $multiplier;
}

/**
 * sa (special add)
 * This function allows you to add one number to another in various ways
 * Send the second number as a string with an operator to perform given operator
 * on the first number.
 * Send with a star to add multiples.
 * Send with a percentage sign to add a percentage of the first number to itself.
 *
 * @param type $number - A number
 * @param type $special - A number of string with a '*' or a '%'.
 *
 * @return type
 */
function sa($number, $special)
{
	if (is_string($special))
	{
		$symbol = substr($special, -1, 1);
		if (!is_numeric($symbol)) $special = trim($special, $symbol);
		switch ($symbol)
		{
			case '%':
				$special = 1 + ($special / 100);
				// Note: This only works because there is no 'break' here.
			case '*':
				$number *= $special;
				break;
			default:
				$number += $special;
		}
	}
	else
	{
		$number += $special;
	}

	return $number;
}

const GBC_DEFAULT = 0;
const GBC_PERCENTAGE = 1;
const GBC_DECIMAL = 2;

function getBiasCalculation($biasFor, $biasAgainst, $GBC = 0)
{
	if ($biasFor === 0) return 0;
	if ($biasAgainst === 0) return $biasFor;

	if ($biasFor < 1)
	{
//		console_echo('<<#aaf>>BiasFor<> was too low; noramlising everything to bring it above zero.', '#aaa');
		$biasAgainst += (0 - $biasFor);
		$biasFor = 1;
	}

	if ($biasAgainst < 1)
	{
//		console_echo('<<#fda>>BiasAgainst<> was too low; noramlising everything to bring it above zero.', '#aaa');
		$biasFor += (0 - $biasAgainst);
		$biasAgainst = 1;
	}

//	console_echo("Bias for: <<#fff>>{$biasFor}<>", '#aaf');
//	console_echo("Bias against: <<#fff>>{$biasAgainst}<>", '#fda');

	$result = $biasFor * ($biasFor / ($biasFor + $biasAgainst));

	if ($GBC !== 0)
	{
		$result = ($result / $biasFor);
		if ($GBC == GBC_PERCENTAGE) $result *= 100;
	}

//	console_echo("Result: <<#fff>>{$result}<>", '#afa');

	return $result;
}

function getReadableClass($object, $plural = false)
{
	$className = is_string($object) ? $object : get_class($object);
	$className = explode('_', $className, 2)[1];
	$className = str_split($className);

	$readableClassName = '';

	foreach ($className as $letter)
	{
		if ($letter === strtoupper($letter)) $readableClassName .= ' ';

		$readableClassName .= $letter;
	}

	if ($plural)
	{
		if (substr($readableClassName, -1) == 's') $readableClassName .= 'e';
		$readableClassName .= 's';
	}

	return $readableClassName;
}

function rotatePatternArrayCW($pattern, $_90degX)
{
	if ($_90degX > 3) $_90degX = $_90degX % 4;

	$turn = false;
	$flipX = false;
	$flipY = false;

	switch ($_90degX)
	{
		case 0: return $pattern;
		case 1:
			$turn = true;
			$flipX = true;
			break;
		case 2:
			$flipY = true;
			$flipX = true;
			break;
		case 3:
			$turn = true;
			$flipY = true;
			break;
	}

	$newPattern = [];

	foreach ($pattern as $frame)
	{
		$newFrame = [];

		foreach ($frame as $coOrds)
		{
			$n_offset = $turn ? $coOrds[1] : $coOrds[0];
			$w_offset = $turn ? $coOrds[0] : $coOrds[1];

			$newCoOrds[0] = $flipY ? 0 - $n_offset : $n_offset;
			$newCoOrds[1] = $flipX ? 0 - $w_offset : $w_offset;

			$newFrame[] = $newCoOrds;
		}

		$newPattern[] = $newFrame;
	}

	return $newPattern;
}

function id ($length = 8)
{
	$chars = 'abcdefghijklmnopqrstuvwxyz1234567890';
	$id = '';

	for ($i = 0; $i < $length; $i++)
	{
		$id .= $chars[mt_rand(0, 35)];
	}

	return $id;
}

function intToBinaryBools ($int, $min = null)
{
	$results = [];

	foreach(str_split(decbin($int)) as $a)
	{
		$results[] = $a ? true : false;
	}

	if ($min) $results = array_pad($results, $min, false);

	return $results;
}