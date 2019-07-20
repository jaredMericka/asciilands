<?php

$rootPath = '../';

function console_echo($a, $b= null) {}
function console_swatch($a, $b= null) {}

require "{$rootPath}engine/core/converters.php";

$colours = [
	'#f00',
	'#faa',
	'#f55',
	'#800',
	'#00f',
	'#058',
];

?>

<html>
	<head>
		<title>
			Tint tester
		</title>
		<style>
			#abs { float:left; }
			#rel { float:right; }
			tr
			{
				text-align:center;
				padding:5px;
			}
		</style>
	</head>
	<body>
		<table id="abs">
			<tr>
				<td colspan="3">
					ABSOLUTE
				</td>
			</tr>
			<tr>
				<td>
					Before
				</td>
				<td>
					Amount
				</td>
				<td>
					After
				</td>
			</tr>

			<?php

			for ($i = - 10; $i < 11; $i += 2)
			{
				foreach ($colours as $colour)
				{
				echo "<tr><td style=\"background-color:{$colour}\">{$colour}</td>";
					echo "<td>{$i}</td>";
					$colour = tint($colour, $i, true);
					echo "<td style=\"background-color:{$colour}\">{$colour}</td></tr>";
				}
			}

			?>
		</table>

		<table id="rel">
			<tr>
				<td colspan="3">
					RELATIVE
				</td>
			</tr>
			<tr>
				<td>
					Before
				</td>
				<td>
					Amount
				</td>
				<td>
					After
				</td>
			</tr>

			<?php

			for ($i = - 10; $i < 11; $i += 2)
			{
				foreach ($colours as $colour)
				{
					echo "<tr><td style=\"background-color:{$colour}\">{$colour}</td>";
					echo "<td>{$i}</td>";
					$colour = tint($colour, $i, false);
					echo "<td style=\"background-color:{$colour}\">{$colour}</td></tr>";
				}
			}

			?>
		</table>
	</body>
</html>