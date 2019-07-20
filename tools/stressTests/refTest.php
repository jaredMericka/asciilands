<html>
	<head>
		<title>Reference test</title>
	</head>
	<body>
		<?php

		$testVar = new stdClass();
		$testVar->testVal = 5;

		$testArray[0] = &$testVar;

		unset($testVar);

		echo isset($testArray[0]) . '<br>';
		echo isset($testVar) . '<br>';

		var_dump($testArray);

		?>
	</body>
</html>


