<html>
	<head>

	</head>
	<body>
		<?php
			$val = 0;
			$time = microtime(true);
			for ($i = 0; $i < 1000000; $i++)
			{
				$val = $val + '5';
				$val = $val + '2.5';
			}
			$time = microtime(true) - $time;
			echo "Strings = {$time}";
			echo "<br>{$val}";

			$val = 0;
			$time = microtime(true);
			for ($i = 0; $i < 1000000; $i++)
			{
				$val = $val + 5;
				$val = $val + 2.5;
			}
			$time = microtime(true) - $time;
			echo "<br><br>Numbers = {$time}";
			echo "<br>{$val}";

			$val = 0;
			$string = 'val';
			$time = microtime(true);
			for ($i = 0; $i < 1000000; $i++)
			{
				$$string = $$string + '5';
				$$string = $$string + '2.5';
			}
			$time = microtime(true) - $time;
			echo "<br><br>Strings = {$time}";
			echo "<br>{$val}";

			$val = 0;
			$string = 'val';
			$time = microtime(true);
			for ($i = 0; $i < 1000000; $i++)
			{
				$$string = $$string + 5;
				$$string = $$string + 2.5;
			}
			$time = microtime(true) - $time;
			echo "<br><br>Numbers = {$time}";
			echo "<br>{$val}";
		?>
	</body>
</html>
