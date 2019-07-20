<!DOCTYPE html>
<html>
	<head>
		<title>Location Planner</title>
		<style>
			.l
			{
				display:block;
				float:left;
				height:100px;
				width:100px;
				font-family:"Source Code Pro";
				font-weight:bold;
				color:#000;
				text-shadow:0px 0px 10px #fff;
				font-size:12px;
				background-color:#777;
				margin:2px;
				padding:6px;
			}
		</style>
	</head>
	<body>

<?php

$bioms = [
	'Fields' => '#484',
	'Marsh' => '#949',
	'Jungle' => '#6a3',
	'Desert' => '#fc8',
	'Snow' => '#ddd',
	'Ice' => '#aff',
	'Hel' => '#f82',
	'Pontoons' => '#09f',
	'Mountainous' => '#89c',
	'Bogs' => '#841',
	
];

$features = [
	'Small town',
	'Esoteric town',
	'Hostile town',
	'Trade town',
	'Castle',
	'Ruin',
	'Cathedral',
	'Chappel',
	'Hermit',
	'Natural wonder',
	'Open area',
	'Claustrophobic area',
	'Tunnels',
	'Trial',
	'Sacred ground',
	'No man\'s land',
];

$locations = [];

foreach ($bioms as $biom => $biomColour)
{
	foreach ($features as $feature)
	{
		$locations[] = "<div class='l' style='background-color:{$biomColour};'>{$biom} {$feature}</div>";
	}
}

do
{
	shuffle($locations);
	echo array_pop($locations);
} while ($locations)

?>

	</body>
</html>