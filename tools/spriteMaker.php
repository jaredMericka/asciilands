<!DOCTYPE html>

<?php
$rootPath = '../';
require "{$rootPath}engine/core/constants.php";
?>

<html>
	<head>
		<title>Sprite Maker</title>
		<style>
			* { font-family:lucida console, monospace; font-size:13px; cursor:default; line-height:<?php echo CHAR_HEIGHT; ?>px; }

			.sprite
			{
				float:left;
				width:<?php echo (3 * CHAR_WIDTH); ?>px;
				height:<?php echo (2 * CHAR_HEIGHT); ?>px;
				word-break:break-all;
				margin-right:8px;
			}
			.sprite>span
			{
				min-width:<?php echo CHAR_WIDTH; ?>px;
				background-color:transparent;
			}

			#dark
			{
				background-color:#000;
			}

			#light
			{
				background-color:#fff;
			}

			input
			{
				background-color:#aaa;
				border:none;
				margin:0px;
				padding:0px;
				width:<?php echo (8 * CHAR_WIDTH); ?>px;
				height:<?php echo CHAR_HEIGHT; ?>px;
			}

			input[aspect="bg"],
			input[aspect="fg"],
			input[aspect="custom"]
			{
				width:<?php echo (3 * CHAR_WIDTH); ?>px;
			}

			table,
			tr,
			td,
			tbody
			{
				border:none;
				margin:0px;
				padding:0px;
			}

			table { clear:both; }

			textarea
			{
				background-color:#aaa;
				border:none;
				margin:0px;
				padding:0px;
				width:<?php echo (60 * CHAR_WIDTH); ?>px;
				height:<?php echo (8 * CHAR_HEIGHT); ?>px;
				resize:none;
			}

			#iframeContainer
			{
				position:fixed;
				top:0px;
				bottom:0px;
				right:0px;
				left:500px;
			}

			iframe
			{
				height:100%;
				width:100%;
				border:none;
			}
		</style>

		<script type="text/javascript">

		chars	= [];
		bgs		= [];
		fgs		= [];

		function registerInput()
		{
			var inputs = document.getElementsByTagName('input');

			for (inputIndex in inputs)
			{
				var input = inputs[inputIndex];
				input.onblur = function () { edit(this); };
				input.onclick = function () { this.select(); };
			}
		}

		function edit(input)
		{
			var dark = document.getElementById('dark');
			var light = document.getElementById('light');
			var custom = document.getElementById('custom');

			var char = input.parentNode.parentNode.getAttribute('char');

			switch(input.getAttribute('aspect'))
			{
				case 'char':
					if (input.value === '')
					{
						var character = '&nbsp;';
						chars[char] = undefined;
					}
					else
					{
						var character = input.value;
						chars[char] = "'" + input.value + "'";
					}

					dark.children[char].innerHTML = character;
					light.children[char].innerHTML = character;
					custom.children[char].innerHTML = character;
					break;
				case 'bg':
					if (input.value === '')
					{
						var colour = 'transparent';
						bgs[char] = undefined;
					}
					else
					{
						var colour = "#" + input.value;
						bgs[char] = "'#" + input.value + "'";
					}

					dark.children[char].style.background = colour;
					light.children[char].style.background = colour;
					custom.children[char].style.background = colour;
					break;
				case 'fg':
					if (input.value === '')
					{
						var colour = 'inherit';
						fgs[char] = undefined;
					}
					else
					{
						var colour = "#" + input.value;
						fgs[char] = "'#" + input.value + "'";
					}

					dark.children[char].style.color = colour;
					light.children[char].style.color = colour;
					custom.children[char].style.color = colour;
					break;
				case 'custom':
					custom.style.background = '#' + input.value;
					break;
			}
			buildPHP();
		}

		function buildPHP ()
		{
			var phpOutput = document.getElementById('php');

			var dark = document.getElementById('dark');

			var php = "new Sprite([\n";

			for (var i = 0; i <= 5; i ++)
			{
				if ((chars[i] !== undefined && chars[i] !== '&nbsp;') || (bgs[i] !== undefined && bgs[i] !== 'transparent'))
				{
					if (chars[i] === undefined) var char = "'&nbsp'"; else var char = chars[i].replace('&', '&amp;');
					if (bgs[i] === undefined) var bg = 'null'; else var bg = bgs[i];
					if (fgs[i] === undefined) var fg = 'null'; else var fg = fgs[i];

					php = php + "\t" + i.toString() + " => new SpriteElement(" + bg + ", " + fg + ", " + char + "),\n";
					//php = php.replace('&', '&amp;');
				}
			}

			php = php + "\t]);";

			phpOutput.innerHTML = php;
		}

		function nudge(char, direction)
		{
			 var char_from = document.getElementById('char' + char);
			 var bg_from = document.getElementById('bg' + char);
			 var fg_from = document.getElementById('fg' + char);

			 var char_to = document.getElementById('char' + (char + direction));
			 var bg_to = document.getElementById('bg' + (char + direction));
			 var fg_to = document.getElementById('fg' + (char + direction));

			 var char_hold = char_to.value;
			 var bg_hold = bg_to.value;
			 var fg_hold = fg_to.value;

			 char_to.value = char_from.value;
			 char_to.onblur();
			 char_from.value = char_hold;
			 char_from.onblur();

			 bg_to.value = bg_from.value;
			 bg_to.onblur();
			 bg_from.value = bg_hold;
			 bg_from.onblur();

			 fg_to.value = fg_from.value;
			 fg_to.onblur();
			 fg_from.value = fg_hold;
			 fg_from.onblur();

		}

		</script>
	</head>
	<body onload="registerInput();">
		<div class="sprite" id="dark">	<span id="0">&nbsp;</span><span id="1">&nbsp;</span><span id="2">&nbsp;</span><span id="3">&nbsp;</span><span id="4">&nbsp;</span><span id="5">&nbsp;</span></div>
		<div class="sprite" id="light">	<span id="0">&nbsp;</span><span id="1">&nbsp;</span><span id="2">&nbsp;</span><span id="3">&nbsp;</span><span id="4">&nbsp;</span><span id="5">&nbsp;</span></div>
		<div class="sprite" id="custom"><span id="0">&nbsp;</span><span id="1">&nbsp;</span><span id="2">&nbsp;</span><span id="3">&nbsp;</span><span id="4">&nbsp;</span><span id="5">&nbsp;</span></div>
		#<input maxlength="3" aspect="custom" />
		<br>
		<table>
			<tr>
				<td>Char</td><td>BG</td><td colspan="3">FG</td>
			</tr>
			<tr char="0">
				<td><input id="char0" aspect="char" /></td><td>#<input id="bg0" maxlength="3" aspect="bg" /></td><td>#<input id="fg0" maxlength="3" aspect="fg" /></td><td onclick="nudge(0, 1);">&#x25bc;</td><td></td>
			</tr>
			<tr char="1">
				<td><input id="char1" aspect="char" /></td><td>#<input id="bg1" maxlength="3" aspect="bg" /></td><td>#<input id="fg1" maxlength="3" aspect="fg" /></td><td onclick="nudge(1, 1);">&#x25bc;</td><td onclick="nudge(1, -1);">&#x25b2;</td>
			</tr>
			<tr char="2">
				<td><input id="char2" aspect="char" /></td><td>#<input id="bg2" maxlength="3" aspect="bg" /></td><td>#<input id="fg2" maxlength="3" aspect="fg" /></td><td onclick="nudge(2, 1);">&#x25bc;</td><td onclick="nudge(2, -1);">&#x25b2;</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr char="3">
				<td><input id="char3" aspect="char" /></td><td>#<input id="bg3" maxlength="3" aspect="bg" /></td><td>#<input id="fg3" maxlength="3" aspect="fg" /></td><td onclick="nudge(3, 1);">&#x25bc;</td><td onclick="nudge(3, -1);">&#x25b2;</td>
			</tr>
			<tr char="4">
				<td><input id="char4" aspect="char" /></td><td>#<input id="bg4" maxlength="3" aspect="bg" /></td><td>#<input id="fg4" maxlength="3" aspect="fg" /></td><td onclick="nudge(4, 1);">&#x25bc;</td><td onclick="nudge(4, -1);">&#x25b2;</td>
			</tr>
			<tr char="5">
				<td><input id="char5" aspect="char" /></td><td>#<input id="bg5" maxlength="3" aspect="bg" /></td><td>#<input id="fg5" maxlength="3" aspect="fg" /></td><td></td><td onclick="nudge(5, -1);">&#x25b2;</td>
			</tr>
		</table>
		<textarea id='php'></textarea><br>
		This is a quick and nasty tool for making sprites.<br>
		The parsing is rubbish so if the output wraps your<br>
		lines or gives you a scroll bar, something is wrong<br>
		with the input.

		<div id="iframeContainer">
			<iframe src="http://localhost:<?php echo $_SERVER['SERVER_PORT']; ?>/asciilands/tools/charMapTE.php"></iframe>
		</div>
	</body>
</html>