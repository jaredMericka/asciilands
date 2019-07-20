<?php

$rootPath = '../';

require "{$rootPath}engine/reqFiles/global.req";
require "{$rootPath}content/sprites/materials.spr";

?>
<html>
	<head>
		<title>Material Inspector</title>
		<style>

			#oosenupt
			{
				position:fixed;
				width:100%;
				height:20px;
				background-color:#ff0;
				font-weight:bold;
				top:0px;
				left:0px;
				right:0px;
				text-align:center;
			}

			*
			{
				font-family:consolas;
				cursor:none;
			}

			#vline, #hline
			{
				background-color:red;
				pointer-events:none;
				position:fixed;
				opacity:0.5;
				z-index:99;
			}

			#vline
			{
				width:1px;
				height:100%;
				top:0px;
				bottom:0px;
			}

			#hline
			{
				height:1px;
				width:100%;
				left:0px;
				right:0px;
			}

			th
			{
				font-weight:normal;
				background-color:#bbb;
			}

			table *
			{
				border:1px solid #999;
				white-space:nowrap;
			}

			td
			{
				text-align:right;
			}

			.name
			{
				background-color:#ddd;
				text-align:left;
				text-transform:capitalize;
			}

			.forced { box-shadow:inset 0 0 10px #f00; }
			.inherited { background-color:#aaf; }
			.owned { background-color:#ffa; }
			.strength { background-color:#afa; }
			.weakness { background-color:#faa; }
			.noval { color:#aaa; }

			#legend
			{
				position:fixed;
				bottom:20px;
				right:20px;
				opacity:0.4;
			}

			#legend:hover
			{
				opacity:1;
			}

		</style>

		<script>
			window.onmousemove = function (e)
			{
				document.getElementById('vline').style.left = e.clientX;
				document.getElementById('hline').style.top = e.clientY;
			};
		</script>
	</head>
	<body>
		<div id="oosenupt">THIS IS BUGGERED!</div>
		<div id="hline"></div>
		<div id="vline"></div>
		<table>
			<tr>
				<th style="width:500px;" rowspan="2" >Material</th>
				<th colspan="13">Attributes</th>
				<th colspan="14">Defence</th>
				<th colspan="7">Damage</th>
				<th colspan="7">Deliveries</th>
				<th colspan="13">Requirements</th>
			</tr>
			<tr>

				<?php
					foreach ($DS_names as $name)
					{
						$abbrev = substr($name, 0, 4);
						echo "<th title=\"{$name}\">{$abbrev}</th>";
					}

					foreach ($DMG_DMGDL_names as $name)
					{
						$abbrev = substr($name, 0, 4);
						echo "<th title=\"{$name}\">{$abbrev}</th>";
					}

					foreach ($DMG_names as $name)
					{
						$abbrev = substr($name, 0, 4);
						echo "<th title=\"{$name}\">{$abbrev}</th>";
					}

					foreach ($DMGDL_names as $name)
					{
						$abbrev = substr($name, 0, 4);
						echo "<th title=\"{$name}\">{$abbrev}</th>";
					}

					foreach ($DS_names as $name)
					{
						$abbrev = substr($name, 0, 4);
						echo "<th title=\"{$name}\">{$abbrev}</th>";
					}

				?>
			</tr>
		<?php

			$eqpFiles = dir("{$rootPath}engine/bases/Equipment");
			$eqpTypes = [];

			while($eqpFile = $eqpFiles->read())
			{
				if ($eqpFile === '.' || $eqpFile === '..') continue;

				require "{$rootPath}engine/bases/Equipment/{$eqpFile}";

				if (substr($eqpFile, 0, 4) === 'eqp_') $eqpTypes[] = $eqpFile;
			}

			$eqps = [];

			foreach ($eqpTypes as $eqp)
			{
				// These equipments need materials.
				$eqps[] = new $eqp(1);
			}

			foreach ($materials as $material)
			{
				$class = get_class($material);

				if (!isset(${"paragon_{$class}"}))
				{
					${"paragon_{$class}"} = new $class(null, null, null);
				}

				$paragon = ${"paragon_{$class}"};

				$shortClass = str_replace('mat_', '', $class);

				echo "<tr><td class=\"name\">{$material->name} - {$shortClass}</td>";

				renderSomeData($material, $paragon, $DS_names, 'DSs', 'gain');
				renderSomeData($material, $paragon, $DMG_DMGDL_names, 'DMGs_def', 'defence');
				renderSomeData($material, $paragon, $DMG_names, 'DMGs', 'damage');
				renderSomeData($material, $paragon, $DMGDL_names, 'DMGDLs', 'delivery');
				renderSomeData($material, $paragon, $DS_names, 'DSs_req', 'requirement');

				echo '</tr>';
			}

			?>
		</table>

		<table id="legend">
			<tr>
				<th colspan="2">Legend</th>
			</tr>
			<tr title="Inherited material properties come from the material type rather than the material itself (e.g., All fabrics have cold defence).">
				<td class="name">Inherited</td>
				<td class="inherited">&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr title="Forced properties are properties that will always appear on an item using a given material (e.g., All metal defencive items have point defence).">
				<td class="name">Forced property</td>
				<td class="forced">&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr title="Owned properties are properties that make the material differ from others of the same type (e.g., Gold reduces lightning defence more than other metals).">
				<td class="name">Owned property</td>
				<td class="owned">&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr title="Strengths are properties with a higher value than others of their type (e.g., All metals add to blunt damage delivery iron adds more that other metals).">
				<td class="name">Strength</td>
				<td class="strength">&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr title="Weaknesses are properties with a lower value than others of their type (e.g., All metals add to point defence but gold is weaker in this regard).">
				<td class="name">Weakness</td>
				<td class="weakness">&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
		</table>

	</body>
</html>



<?php

function renderSomeData($material, $paragon, $nameArray, $arrayName, $titleSuffix = '')
{
	$materialArray = $material->$arrayName;
	$paragonArray = $paragon->$arrayName;
	foreach ($nameArray as $key => $name)
	{
		$title = "{$material->name} - {$name} {$titleSuffix}";
		if (isset($materialArray[$key]))
		{
			$class = '';
			if (is_string($materialArray[$key]))
			{
				$class .= ' forced';
			}

			if (isset($paragonArray[$key]))
			{
				if ($paragonArray[$key] == $materialArray[$key]) $class .= ' inherited';
				elseif ($paragonArray[$key] > $materialArray[$key]) $class .= ' weakness';
				elseif ($paragonArray[$key] < $materialArray[$key]) $class .= ' strength';

			}
			else
			{
				$class = ' owned';
			}

			$content = number_format($materialArray[$key], 1);

			echo "<td class=\"{$class}\" title=\"{$title}\">{$content}</td>";
		}
		else
		{
			echo "<td class=\"noval\" title=\"{$title}\">1.0</td>";
		}
	}
}