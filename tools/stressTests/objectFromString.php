<html>
	<head>

	</head>
	<body>
		<?php

		class StringyThingy
		{
			public $variable;

			public function __construct($variable)
			{
				$this->variable = $variable;
			}
		}

		$string = 'StringyThingy';

		$object = new $string(4);

		var_dump($object);

		?>
	</body>
</html>

