<html>
	<head>

	</head>
	<body>
		<?php
			$val = 0;
			$time = microtime();
			for ($i = 0; $i < 1000000; $i++)
			{
				$val = $val + '5';
				$val = $val + '2.5';
			}
			$time = microtime() - $time;
			echo "Strings = {$time}";
			echo "<br>{$val}";

			$val = 0;
			$time = microtime();
			for ($i = 0; $i < 1000000; $i++)
			{
				$val = $val + 5;
				$val = $val + 2.5;
			}
			$time = microtime() - $time;
			echo "<br><br>Numbers = {$time}";
			echo "<br>{$val}";

			$val = 0;
			$string = 'val';
			$time = microtime();
			for ($i = 0; $i < 1000000; $i++)
			{
				$$string = $$string + '5';
				$$string = $$string + '2.5';
			}
			$time = microtime() - $time;
			echo "<br><br>Strings = {$time}";
			echo "<br>{$val}";

			$val = 0;
			$string = 'val';
			$time = microtime();
			for ($i = 0; $i < 1000000; $i++)
			{
				$$string = $$string + 5;
				$$string = $$string + 2.5;
			}
			$time = microtime() - $time;
			echo "<br><br>Numbers = {$time}";
			echo "<br>{$val}";
		?>
	</body>
</html>
