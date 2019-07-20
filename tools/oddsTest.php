<?php

$rootPath = '../';

require "{$rootPath}engine/core/converters.php";

function drawBar($label, $percentage, $colour = '#000')
{
	echo "<div class=\"label\">{$label}:</div><div title=\"{$label}: {$percentage}%\" class=\"bar\" style=\"background-color:{$colour};width:{$percentage}%;\">{$percentage}%</div>";
}

//ob_start();

?>

<html>
	<head>
		<title>
			Odds tester!
		</title>
		<style>

			.bar
			{
				padding:5px;
				vertical-align:top;
				font-weight:bold;
				font-size:9pt;
				color:#fff;
			}

			.label
			{
				font-size:8pt;
				font-weight:bold;
			}

			h1
			{
				font-size:15pt;
				font-weight:bold;
			}

			.histogram
			{
				height:500px;
				width:100%
			}

			.histBar
			{
				background-color:#000;
				width:1px;
				float:left;
			}

			.histBar:hover
			{
				background-color:#f00;
			}
		</style>
	</head>
	<body>
		<h1>Testing "getNuancedValue(100, 5);" (1000 rounds)</h1>

		<?php
			$nuancedValues = [];

			for($i = 0; $i < 1000; $i++)
			{
				$nuancedValues[] = getNuancedValue(100, 5);
			}

			$nuancedValuesDistribution = array_count_values($nuancedValues);
			ksort($nuancedValuesDistribution);

			foreach($nuancedValuesDistribution as $value => $number)
			{
				drawBar($value, $number / 10);
			}
		?>

		<h1>Testing "getNuancedValue(10, 20);" (1000 rounds)</h1>

		<?php
			$nuancedValues = [];

			for($i = 0; $i < 1000; $i++)
			{
				$nuancedValues[] = getNuancedValue(10, 20);
			}

			$nuancedValuesDistribution = array_count_values($nuancedValues);
			ksort($nuancedValuesDistribution);

			foreach($nuancedValuesDistribution as $value => $number)
			{
				drawBar($value, $number / 10);
			}
		?>

		<h1>Testing "percentageToBool(50, 100, true)" (1000 runs)</h1>

		<?php
			$trues = 0;
			$falses = 0;

			for ($i = 0; $i < 1000; $i++)
			{
				if (percentageToBool(50, 100, true))
				{
					$trues ++;
				}
				else
				{
					$falses ++;
				}
			}

			drawBar('True', $trues / 10);
			drawBar('False', $falses / 10);
		?>

		<h1>Testing "percentageToBool(90, -200, true)" (1000 runs)</h1>

		<?php
			$trues = 0;
			$falses = 0;

			for ($i = 0; $i < 1000; $i++)
			{
				if (percentageToBool(90, -200, true))
				{
					$trues ++;
				}
				else
				{
					$falses ++;
				}
			}

			drawBar('True', $trues / 10);
			drawBar('False', $falses / 10);
		?>

		<h1>Testing "percentageToBool(10, -50, false)" (1000 runs)</h1>

		<?php
			$trues = 0;
			$falses = 0;

			for ($i = 0; $i < 1000; $i++)
			{
				if (percentageToBool(10, -50, false))
				{
					$trues ++;
				}
				else
				{
					$falses ++;
				}
			}

			drawBar('True', $trues / 10);
			drawBar('False', $falses / 10);
		?>

		<h1>Testing "percentageToBool(50, 600, true)" (1000 runs)</h1>

		<?php
			$trues = 0;
			$falses = 0;

			for ($i = 0; $i < 1000; $i++)
			{
				if (percentageToBool(50, 600, true))
				{
					$trues ++;
				}
				else
				{
					$falses ++;
				}
			}

			drawBar('True', $trues / 10);
			drawBar('False', $falses / 10);
		?>

		<h1>Testing "percentageToBool(50, $luck, true)" where $luck ranges from -500 to 500 (500 runs of each)</h1>

		<div class="histogram">
			<?php
				for ($i = -500; $i < 500; $i++)
				{
					$trues = 0;
					for ($j = 0; $j < 500; $j++)
					{
						if (percentageToBool(50, $i, true)) $trues ++;
					}

					$height = $trues;
					$margin = 500 - $height;
					$percent = $trues / 5;

					echo "<div title=\"Luck: {$i} - {$percent}%\" style=\"height:{$height};margin-top:{$margin}\" class=\"histBar\"></div>";

				}
			?>
		</div>

		<h1>Testing "percentageToBool(90, $luck, true)" where $luck ranges from -500 to 500 (500 runs of each)</h1>

		<div class="histogram">
			<?php
				for ($i = -500; $i < 500; $i++)
				{
					$trues = 0;
					for ($j = 0; $j < 500; $j++)
					{
						if (percentageToBool(90, $i, true)) $trues ++;
					}

					$height = $trues;
					$margin = 500 - $height;
					$percent = $trues / 5;

					echo "<div title=\"Luck: {$i} - {$percent}%\" style=\"height:{$height};margin-top:{$margin}\" class=\"histBar\"></div>";

				}
			?>
		</div>

		<h1>Testing "percentageToBool(20, $luck, true)" where $luck ranges from -500 to 500 (500 runs of each)</h1>

		<div class="histogram">
			<?php
				for ($i = -500; $i < 500; $i++)
				{
					$trues = 0;
					for ($j = 0; $j < 500; $j++)
					{
						if (percentageToBool(20, $i, true)) $trues ++;
					}

					$height = $trues;
					$margin = 500 - $height;
					$percent = $trues / 5;

					echo "<div title=\"Luck: {$i} - {$percent}%\" style=\"height:{$height};margin-top:{$margin}\" class=\"histBar\"></div>";

				}
			?>
		</div>

	</body>
</html>

