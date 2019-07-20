<html>
	<head>
		<title>Random Colour</title>
		<style>
			html
			{
				background-color:<?php

				if (!empty($_GET))
				{
					$colour = '#' . reset($_GET);
					$message = 'Colour brought to you bu $_GET arrray and user input.';
				}
				else
				{
					$colourVals = [15, 0, mt_rand(0, 15)];
					shuffle($colourVals);
					ob_start();
					echo '#';
					foreach ($colourVals as $val)
					{
						echo dechex($val);
					}
					$colour = ob_get_clean();
					$message = 'Random colour brought to you by "mt_rand" and "shuffle" functions.';
				}

				echo $colour;
				?>;
				font-family:lucida console;
				color:#fff;
				font-size:200px;
				padding:100px;
				text-shadow: #000 10px 10px 30px;
			}
		</style>
	</head>
	<body>
		<?php echo $colour; ?><br>
		<span style="font-size:20px;text-shadow: #000 2px 2px 6px;"><?php echo $message; ?></span>
	</body>
</html>