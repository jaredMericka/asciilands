<?php

class Mask
{
	public function __construct($thing = null, $properties = null)
	{
		if (!$thing) $thing = new stdClass();

		if ($thing instanceof Item) $thing->finish();

		if (!isset($properties))
		{
			$properties = array_keys(get_object_vars($thing));
//			$properties[] = 'class';

			$unwantedVars = [
				'quantity',
				'id'
			];

			$properties = array_diff($properties, $unwantedVars);

			$lazy = true;
			console_echo('Making a lazy mask using all properties. Just letting you know because you should probabaly never do this.', '#faa');
		}
		else
		{
			$lazy = false;
		}

		console_echo('Making a mask with the following properties:', '#afa');
		foreach ($properties as $property)
		{
			switch ($property)
			{
				case 'class':
					$this->class = [get_class($thing)];
					break;
				default:
					if (isset($thing->$property) || $lazy)
					{
						$this->$property = $thing->$property;
					}
					else
					{
						console_echo("Trying to make a dodgy mask. Something lacks a property <<#fff>>\"{$property}\"<>.", '#faa');
					}
			}
			console_echo($property, '#aff');
		}
	}

	public function compare($otherThing)
	{
		$properties = get_object_vars($this);

		if (!$properties) console_echo('Dude, you\'re comparing a <<#fff>>' . get_class($otherThing) . '<> to a mask with no properties; this will always pass.', '#f00');

		foreach ($properties as $property => $value)
		{
			$pass = false;
			switch ($property)
			{
				case 'class':
					$thingClass = get_class($otherThing);
					if (is_array($this->class))
					{
						if (in_array($thingClass, $this->class)) $pass = true;
					}
					else
					{
						if ($thingClass === $value) $pass = true;
					}
					break;
				case 'sprite':
					if ($value->equals($otherThing->$property)) $pass = true;
					break;
				case 'spriteSet':
					$pass = true;
					break;
				case 'materials':
					$pass = count(array_diff($value, $otherThing->$property)) === 0;
					break;
				case 'name':
				case 'description:':
					if (strtolower($otherThing->$property) === strtolower($value)) $pass = true;
					break;

				default:
					if ($otherThing->$property === $value) $pass = true;
			}

			if (!$pass)
			{
				// All this console error checking shit is probabaly a bit over the top but when you're trying to track down
				// something that's going wrong with a mask comparison, you'll be glad its here.

				if (!isset($otherThing->$property)) $otherThingValue = 'NOTHING';														//XXX
				elseif (is_scalar($otherThing->$property)) $otherThingValue = $otherThing->$property;									//XXX
				elseif (is_object($otherThing->$property)) $otherThingValue = 'AsObject(' . get_class($otherThing->$property) . ')';		//XXX
				elseif (is_array($otherThing->$property)) $otherThingValue = 'Array[' . count($otherThing->$property) . ']';			//XXX
				else $otherThingValue = '???';																							//XXX


				if (is_scalar($value)) $value = $value;										//XXX
				elseif (is_object($value)) $value = 'AsObject(' . get_class($value) . ')';	//XXX
				elseif (is_array($value)) $value = 'Array[' . count($value) . ']';			//XXX
				else $value = '???';														//XXX

				console_echo("Mask comparison failed on property <<#fff>>\"{$property}\"<> which had the value <<#afa>>\"{$value}\"<> instead of <<#faf>>\"{$otherThingValue}\"<>.", '#ffa');

				return false;
			}
		}
		return true;
	}
}