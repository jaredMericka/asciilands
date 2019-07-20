<html>
	<head>
		<title>Attack Tester</title>
		<style>

			html,
			body
			{
				padding:0px;
				margin:0px;
			}

			*
			{
				font-family:consolas;
				font-size:10pt;
			}

			th
			{
				background-color:#000;
				color:#fff;
				padding:6px;
			}

			table
			{
				float:left;
				border-spacing:0px;
				border-collapse:collapse;
				margin-right:5px;
			}

			#options
			{
				float:right;
			}

			.ds1 td
			{
				background-color:#aaf;
			}

			.ds2 td
			{

			}

			.ds3 td
			{
				color:#555;
			}

			input[type="number"]
			{
				width:50px;
			}

			#data
			{
				border-radius:20px;
				float:left;
				clear:both;
				width:500px;
				background-color:#030;
				color:#8f8;
				height:200px;
				resize:vertical;
				overflow-y:scroll;
				padding-left:10px;
				margin:20px;
				margin-bottom:100px;
				border:10px inset #888;
			}

			#submit
			{
				width:100%;
				border:none;
				background-color:#a00;
				color:#fff;
				padding:5px;
			}

			a
			{
				text-decoration:underline;
				font-weight:bold;
				cursor:pointer;
			}
		</style>
	</head>
	<body>
		<?php

		$rootPath = '../';

		require "{$rootPath}engine/core/include.php";

		?>

		<b>Attack Tester</b> is to be used in conjuction with the <a onclick="openConsole()">Asciilands Console</a>.<br>
		It runs an attack between two dudes behind the scenes using the stats given below.<br>
		The attack code will dump data into the console and hopefully the information you want will be in there.

		<form method="GET" action="attackTester.php" style="margin-top:10px;">

			<table id="options">
				<tr>
					<th>Option</th><th>Always</th><th>Never</th><th>No change</th>
				</tr>
				<tr>
					<td>Hit</td>
					<td><input type="radio" name="hitChanceOv" value="0"></td>
					<td><input type="radio" name="hitChanceOv" value="1"></td>
					<td><input type="radio" name="hitChanceOv" checked="true" value="2"></td>
				</tr>
				<tr>
					<td>Crit</td>
					<td><input type="radio" name="critChanceOv" value="0"></td>
					<td><input type="radio" name="critChanceOv" value="1"></td>
					<td><input type="radio" name="critChanceOv" checked="true" value="2"></td>
				</tr>
			</table>

			<input id="submit" type="submit" value="GO!"/>

			<table>
				<tr>
					<th>Damage type</th>
					<th>Value</th>
				</tr>
			<?php
				foreach ($DMG_names as $DMG => $name)
				{
					echo "<tr><td>{$name}</td><td><input type=\"number\" value=\"0\" name=\"DMG_{$DMG}\"></td></tr>";
				}
			?>
			</table>

			<table>
				<tr>
					<th colspan="2">Delivery</th>
				</tr>
			<?php
				foreach ($DMGDL_names as $DMGDL => $name)
				{
					echo "<tr><td><input type=\"radio\" name=\"DMGDL\" value=\"$DMGDL\"</td><td>{$name}</td></tr>";
				}
			?>
			</table>

			<table>
				<tr>
					<th>Defence type</th>
					<th>Value</th>
				</tr>
			<?php
				foreach ($DMG_names as $DMG => $name)
				{
					echo "<tr><td>{$name}</td><td><input type=\"number\" value=\"0\" name=\"defence_{$DMG}\"></td></tr>";
				}
				foreach ($DMGDL_names as $DMGDL => $name)
				{
					echo "<tr><td>{$name}</td><td><input type=\"number\" value=\"0\" name=\"defence_{$DMGDL}\"></td></tr>";
				}
			?>
			</table>

			<table>
				<tr>
					<th>Attacker Stats</th>
					<th>Value</th>
				</tr>
			<?php
				foreach ($DS_types as $DS => $DSs)
				{
					echo "<tr class=\"ds1\"><td>{$DS_names[$DS]}</td><td><input type=\"number\" value=\"100\" name=\"a_DSs_{$DS}\"></td></tr>";

					foreach ($DSs as $sDS)
					{
						$class = $sDS % 100 > 50 ? 'ds3' : 'ds2';
						echo "<tr class=\"{$class}\"><td>{$DS_names[$sDS]}</td><td><input type=\"number\" value=\"100\" name=\"a_DSs_{$sDS}\"></td></tr>";
					}
				}
			?>
			</table>

			<table>
				<tr>
					<th>Target Stats</th>
					<th>Value</th>
				</tr>
			<?php
				foreach ($DS_types as $DS => $DSs)
				{
					echo "<tr class=\"ds1\"><td>{$DS_names[$DS]}</td><td><input type=\"number\" value=\"100\" name=\"t_DSs_{$DS}\"></td></tr>";

					foreach ($DSs as $sDS)
					{
						$class = $sDS % 100 > 50 ? 'ds3' : 'ds2';
						echo "<tr class=\"{$class}\"><td>{$DS_names[$sDS]}</td><td><input type=\"number\" value=\"100\" name=\"t_DSs_{$sDS}\"></td></tr>";
					}
				}
			?>
			</table>

			<table>
				<tr>
					<th>Attacker Techniques</th>
					<th>Damage</th>
					<th>Hit %</th>
					<th>Crit D</th>
					<th>Crit %</th>
				</tr>
			<?php
				foreach ($DS_types as $DS => $DSs)
				{
					echo "<tr class=\"ds1\"><td>{$DS_names[$DS]}</td>";
					echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_damage_{$DS}\"></td>";
					echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_hitChance_{$DS}\"></td>";
					echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_critDamage_{$DS}\"></td>";
					echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_critChance_{$DS}\"></td>";
					echo '</tr>';

					foreach ($DSs as $sDS)
					{
						$class = $sDS % 100 > 50 ? 'ds3' : 'ds2';
						echo "<tr class=\"{$class}\"><td>{$DS_names[$sDS]}</td>";
						echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_damage_{$sDS}\"></td>";
						echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_hitChance_{$sDS}\"></td>";
						echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_critDamage_{$sDS}\"></td>";
						echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_critChance_{$sDS}\"></td>";
						echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_attackSpeed_{$sDS}\"></td>";
						echo "<td><input type=\"number\" value=\"0\" name=\"a_TEQ_consistency_{$sDS}\"></td>";
						echo '</tr>';
					}
				}
			?>
			</table>

			<table>
				<tr>
					<th>Target Techniques</th>
					<th>Defence</th>
					<th>Dodge</th>
				</tr>
			<?php
				foreach ($DS_types as $DS => $DSs)
				{
					echo "<tr class=\"ds1\"><td>{$DS_names[$DS]}</td>";
					echo "<td><input type=\"number\" value=\"0\" name=\"t_TEQ_defence_{$DS}\"></td>";
					echo "<td><input type=\"number\" value=\"0\" name=\"t_TEQ_dodgeChance_{$DS}\"></td>";
					echo '</tr>';

					foreach ($DSs as $sDS)
					{
						$class = $sDS % 100 > 50 ? 'ds3' : 'ds2';
						echo "<tr class=\"{$class}\"><td>{$DS_names[$sDS]}</td>";
						echo "<td><input type=\"number\" value=\"0\" name=\"t_TEQ_defence_{$sDS}\"></td>";
						echo "<td><input type=\"number\" value=\"0\" name=\"t_TEQ_dodgeChance_{$sDS}\"></td>";
						echo '</tr>';
					}
				}
			?>
			</table>
		</form>

		<pre id="data"><?php

		class dude_attackTester extends Dude
		{
			public function __construct($isAttacker = true)
			{
				$name = $isAttacker ? 'Attacker' : 'Target';
				$spriteSet = [new Sprite([null])];
				$n_offset = 0;
				$w_offset = 0;

				parent::__construct($name, $spriteSet, $n_offset, $w_offset);
			}
		}

		$attacker = new dude_attackTester(true);
		$target = new dude_attackTester(false);

//		var_dump($_GET);

		processGetVars();

		$attacker->attack = new Attack($attacker);

		if (isset($_GET['hitChanceOv']) && $_GET['hitChanceOv'] != 2)
		{
			$attacker->attack->alwaysHit = ($_GET['hitChanceOv'] == 0);
		}

		if (isset($_GET['critChanceOv']) && $_GET['critChanceOv'] != 2)
		{
			$attacker->attack->alwaysCrit = ($_GET['critChanceOv'] == 0);
		}

		$attacker->doAttack($target);

		?></pre>
	</body>
	<script>
		var getVals = <?php echo json_encode($_GET, JSON_OBJECT_AS_ARRAY); ?>;

		var radioVars = ['DMGDL', 'hitChanceOv', 'critChanceOv'];

		for (var index in radioVars)
		{
			var radioVar = radioVars[index];
			if(getVals[radioVar] !== undefined)
			{
				document.getElementsByName(radioVar)[getVals[radioVar]].checked = true;
				delete getVals[radioVar];
			}
		}

		for (index in getVals)
		{
//			if (index == 'DMGdl') continue;
			document.getElementsByName(index)[0].value = getVals[index];
		}

		function openConsole()
		{
			window.open(
				'../engine/systems/console.php?console=window',
				'Asciilands console',
				'height=700,width=500,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,status=no'
			);
		}
	</script>
</html>
<?php

function processGetVars()
{
	global $attacker;
	global $target;

	$DMGDL = isset($_GET['DMGDL']) ? $_GET['DMGDL'] : DMGDL_CUT;
//	unset($_GET['DMGDL']);

	$arrayNames = [
		'DMG',
		'defence',
		'a_DSs',
		't_DSs',
		'a_TEQ_damage',
		'a_TEQ_hitChance',
		'a_TEQ_critDamage',
		'a_TEQ_critChance',
		'a_TEQ_attackSpeed',
		'a_TEQ_consistency',
		't_TEQ_defence',
		't_TEQ_dodgeChance',
	];

	foreach ($arrayNames as $arrayName)
	{
		$array = [];

		foreach ($_GET as $name => $val)
		{
			if (strpos($name, $arrayName) === 0)
			{
				// Convert techniques from percentages to decimals
				if (strpos($name, 'TEQ') === 2) $val /= 100;

				// Discard zero-value non-dudeStats.
				if ((strpos('DS', $val) !== 2) && $val == 0) continue;

				// Discard zero-value non-dudeStats.
				if (strpos('DMGDL', $val)) continue;

				$name = trim($name, "{$arrayName}_");


				$array[(int)$name] = (double)$val;
			}
		}

		$$arrayName = $array;

		echo "\n\n<b>{$arrayName}</b>\n";
		var_dump($$arrayName);
	}

//	var_dump($DMG);

	$attacker->DMGDL = $DMGDL;
	$attacker->DMGs = $DMG;
	$attacker->DSs = $a_DSs + $attacker->DSs;
	$attacker->technique['base'][TEQ_DAMAGE]		= $a_TEQ_damage;
	$attacker->technique['base'][TEQ_HIT_CHANCE]		= $a_TEQ_hitChance;
	$attacker->technique['base'][TEQ_CRIT_CHANCE]	= $a_TEQ_critChance;
	$attacker->technique['base'][TEQ_CRIT_DAMAGE]	= $a_TEQ_critDamage;
	$attacker->technique['base'][TEQ_ATTACK_SPEED]	= $a_TEQ_attackSpeed;
	$attacker->technique['base'][TEQ_CONSISTENCY]	= $a_TEQ_consistency;

	$target->DSs = $t_DSs + $target->DSs;
	$target->DMGs_def = $defence;
	$target->technique['base'][TEQ_DEFENCE]			= $t_TEQ_defence;
	$target->technique['base'][TEQ_DODGE_CHANCE]		= $t_TEQ_dodgeChance;
}

// myoldboy