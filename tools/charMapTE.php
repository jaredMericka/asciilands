<html>
	<head>
		<style>
			#body
			{
				font-family:lucida console;
				font-size:13px;
				cursor:default;
				letter-spacing:0px;
			}

			span
			{
				display:inline-block;
				padding:10px;
				border:1px solid #ddd;
			}

			span:hover
			{
				background-color:#faa;
			}

			#prev
			{
				left:-100px;
				pointer-events:none;
				border-style:solid;
				border-width:2px;
				border-color:#000;
				position:absolute;
				display:inline-block;
				text-align:center;
				background-color:#eee;
				box-shadow: 5px 5px 15px #555;
			}

			#prevChar
			{
				font-size:30px;
				margin:10px;
				background-color:#aaa;
			}

			#toolBar
			{
				background-color:#eee;
			}

			#charCode
			{
				position:fixed;
				top:0px;
				left:0px;
				/*left:-100px;*/
			}
		</style>


	<title id="title">LOADING...</title>
	</head>
	<body onload="init();">
		<div id="body">
<?php
ob_start();
$chars = str_split('0123456789abcdef');
$charCount = 0;

$skip = [ '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e'];

foreach ($chars as $char0)
{
	if (in_array($char0, $skip)) continue;

	foreach ($chars as $char1)
	{
		if ($char0 === '1' && in_array($char1, ['8', '9', 'a', 'b', 'c'])) continue;
		elseif ($char0 === '2' && in_array($char1, ['7', '8', '9', 'a', 'b', 'e', 'f'])) continue;
		elseif ($char0 === 'f' && $char1 !== 'b') continue;

		foreach ($chars as $char2)
		{
			foreach ($chars as $char3)
			{
				$charCount ++;
				$charCode = implode([$char0, $char1, $char2, $char3]);
				echo "<span code=\"{$charCode}\" id=\"{$charCount}\" onclick=\"copyCode(this);\" onmouseover=\"preview(this);\">&#x{$charCode};</span>";
			}
		}
	}
	//if ($charCount > 2000) break; // This reduces the load time when I'm testing.
}

?>

			<div id="prev">
				<div id="prevChar"></div>
				<div id="prevCode"></div>
			</div>
		</div>
		<input type="text" id="charCode"/>
	</body>

	<script type="text/javascript">

		function harvest(sampleID)
		{
			var sampleReszie = 20;
			var sampleNode = document.getElementById(sampleID);
			var title = document.getElementById('title');
			var body = document.getElementById('body');
			var round1Ids_purge = new Array();
			var round2Ids_test = new Array();
			var round2Ids_purge = new Array();
			var round3Ids_test = new Array();
			var node;

			var width = sampleNode.offsetWidth;
			var height = sampleNode.offsetHeight;
			for (i = 0; i <= <?php echo $charCount ?>; i++)
			{
				node = document.getElementById(i);
				if (node !== null)
				{
//					if (node.offsetWidth !== width || node.offsetHeight !== height)
					if (
						node.offsetWidth - width > 1 ||
						width - node.offsetWidth > 1 ||
						node.offsetHeight - height > 1 ||
						height - node.offsetHeight > 1 )
					{
//						alert(node.innerHTML + '\n' + node.offsetWidth + ' vs ' + width + '\n' + node.offsetHeight + ' vs ' + height);
						round1Ids_purge.push(node.id);
					}
					else
					{
						round2Ids_test.push(node.id);
					}
				}
			}

			nodeIDcount = round1Ids_purge.length;
			for (i = 0; i < nodeIDcount; i++)
			{
				node = document.getElementById(round1Ids_purge[i]);
				node.parentNode.removeChild(node);
			}

			for (j = 50; j >= 13; j --)
			{
				body.style.fontSize = j + 'px';
				var width = sampleNode.offsetWidth;
				var height = sampleNode.offsetHeight;

				nodeIDcount = round2Ids_test.length;
				for (i = 0; i < nodeIDcount; i++)
				{
					node = document.getElementById(round2Ids_test[i]);
					if (node !== null)
					{
//						if (node.offsetWidth !== width || node.offsetHeight !== height)
						if (
							node.offsetWidth - width > 1 ||
							width - node.offsetWidth > 1 ||
							node.offsetHeight - height > 1 ||
							height - node.offsetHeight > 1 )
						{
//							alert(node.innerHTML + '\n' + node.offsetWidth + ' vs ' + width + '\n' + node.offsetHeight + ' vs ' + height);

							round2Ids_purge.push(node.id);
						}
						else
						{
							round3Ids_test.push(node.id);
						}
					}
				}

				nodeIDcount = round2Ids_purge.length;
				for (i = 0; i < nodeIDcount; i++)
				{
					node = document.getElementById(round2Ids_purge[i]);
					node.parentNode.removeChild(node);
				}

				round2Ids_test = round3Ids_test;
				round3Ids_test = new Array();
				round2Ids_purge = new Array();
			}



			//body.style.fontSize = '13px';

			title.innerHTML = 'Initialise complete!';
			setTimeout("title.innerHTML = 'CharMap Turbo Edition';", 1000);

		}

		function testWidth(node)
		{
			alert(node.offsetWidth === 8 ? 'Appropriate (8px).' : 'Garbage ('+node.offsetWidth+'px).');
		}

		function preview(node)
		{
			var previewBox = document.getElementById('prev');
			var previewChar = document.getElementById('prevChar');
			var previewCode = document.getElementById('prevCode');

			previewBox.style.left = (node.offsetLeft - 5);
			previewBox.style.top = (node.offsetTop - 15);

			previewChar.innerHTML = node.innerHTML;
			previewCode.innerHTML = node.getAttribute('code');

//			charCodeText.value = '&#x' + node.getAttribute('code') + ';';
//			charCodeText.focus();
//			charCodeText.select();
			return false;

		}

		function copyCode(node)
		{
			var charCodeText = document.getElementById('charCode');

			charCodeText.value = '&#x' + node.getAttribute('code') + ';';
			charCodeText.focus();
			charCodeText.select();

			return false;
		}

		function init()
		{
			harvest(66);
		}


	</script>

</html>