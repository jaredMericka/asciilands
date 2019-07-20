<?php

class AssetSet
{
	protected function getColour($colour, $directive = null)
	{
		if (!$directive)			return $colour;
		if (is_numeric($directive))	return tint($colour, (int) $directive);
		if (is_string($directive))	return $directive;
	}
}