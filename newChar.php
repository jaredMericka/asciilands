<!DOCTYPE html>
<?php $rootPath = '';
require "{$rootPath}engine/core/constants.php";
require "{$rootPath}engine/core/newCharConstants.php";
?>

<html>
	<head>
		<title>Create Character</title>
		<style>
			html{ padding:0px; margin:0px;overflow:hidden;color:#8f8;}
			* { font-family:lucida console, monospace; font-size:13px; cursor:default; line-height:<?php echo CHAR_HEIGHT; ?>px; }
			pre { font-family:inherit; font-size:inherit; }

			#character
			{
				display:inline-block;
				width:<?php echo CHAR_WIDTH * 3; ?>px;
				height:<?php echo CHAR_HEIGHT * 2; ?>px;
			}

			#container
			{
				position:absolute;
				top:50%;
				margin-top:<?php echo 0 - CHAR_HEIGHT; ?>px;
				text-align:center;
				width:100%;
			}

			#controls
			{
			}

			body
			{
				font-family:lucida console, monospace;
				font-size:13px;
				background-color:#000;
				padding:0px;
				margin:0px;
			}

			.slider
			{
				text-align:left;
				display:inline-block;
				width:<?php echo CHAR_WIDTH * 21; ?>px;
				position:relative;
			}

			.next, .prev
			{
				padding:0px <?php echo CHAR_WIDTH; ?>px;
				position:absolute;
			}

			.button:hover
			{
				color:#000;
				background-color:#8f8;
			}

			#begin
			{
				padding:0px <?php echo CHAR_WIDTH; ?>px;
			}

			.next { right:0px; }
			.prev { left:0px; }

			.label
			{
				margin-left:<?php echo CHAR_WIDTH * 8; ?>px;
			}

			#name
			{
				border:none;
				background-color:#8f8;
				color:#000;
				height:<?php echo CHAR_HEIGHT; ?>px;
				width:<?php echo CHAR_WIDTH * 13; ?>px;
			}

		</style>
	</head>
	<body>
		<div id="container">
			<div id="character"><!--
				-->&nbsp;<!--
				--><span class="skin" id="head">o</span><!--
				-->&nbsp;
				<span class="skin" id="l_hand">&deg;</span><!--
				--><span class="pants" id="pants">&Omega;</span><!--
				--><span class="skin" id="r_hand">&deg;</span><!--
				--></div>
			<div id="controls">
				<br>
				<div>
					Name:
					<input id="name" type="text" />
				</div>
				<br>
			</div>
		</div>
	</body>
	<script>
		// <!--

		var genders = new Array('male', 'female');

		var heads_m = <?php echo json_encode($heads_m); ?>;
		var heads_f = <?php echo json_encode($heads_f); ?>;

		var legs_m = <?php echo json_encode($legs_m); ?>;
		var legs_f = <?php echo json_encode($legs_f); ?>;

		var skins = <?php echo json_encode($skins); ?>;
		var pants = <?php echo json_encode($pants); ?>;

		var genderIndex = 0;
		var headIndex = 0;
		var legsIndex = 0;
		var skinIndex = 0;
		var pantsIndex = 0;

		var controls = document.getElementById('controls');

		var genderSlider = makeSlider('Gender');
		genderSlider.next.onclick = function(){changeGender();};
		genderSlider.prev.onclick = function(){changeGender();};

		var headSlider = makeSlider('Head');
		headSlider.next.onclick = function(){changeHead(1);};
		headSlider.prev.onclick = function(){changeHead(-1);};

		var legsSlider = makeSlider('Legs');
		legsSlider.next.onclick = function(){changeLegs(1);};
		legsSlider.prev.onclick = function(){changeLegs(-1);};

		var skinSlider = makeSlider('Skin');
		skinSlider.next.onclick = function(){changeSkin(1);};
		skinSlider.prev.onclick = function(){changeSkin(-1);};

		var pantsSlider = makeSlider('Pants');
		pantsSlider.next.onclick = function(){changePants(1);};
		pantsSlider.prev.onclick = function(){changePants(-1);};

		var beginButton = document.createElement('span');
		beginButton.className = 'button';
		beginButton.id = 'begin';
		beginButton.innerHTML = 'Begin';
		beginButton.onclick = begin_onclick;

		controls.appendChild(genderSlider);
		controls.appendChild(document.createElement('br'));
		controls.appendChild(headSlider);
		controls.appendChild(document.createElement('br'));
		controls.appendChild(legsSlider);
		controls.appendChild(document.createElement('br'));
		controls.appendChild(skinSlider);
		controls.appendChild(document.createElement('br'));
		controls.appendChild(pantsSlider);
		controls.appendChild(document.createElement('br'));
		controls.appendChild(document.createElement('br'));
		controls.appendChild(beginButton);

		changeSkin(0);
		changePants(0);

		function setSkin(colour)
		{
			var nodes = document.getElementsByClassName('skin');

			for (var i = 0; i < nodes.length; i++)
			{
				nodes[i].style.color = colour;
			}
		}

		function setPants(colour) { document.getElementById('pants').style.color = colour; }
		function setLegs(character) { document.getElementById('pants').innerHTML = character; }
		function setHead(character) { document.getElementById('head').innerHTML = character; }

		function makeSlider(labelContent)
		{
			var slider = document.createElement('div');
			var prev = document.createElement('span');
			var next = document.createElement('span');
			var label = document.createElement('span');

			slider.className = 'slider';
			prev.className = 'prev button';
			next.className = 'next button';
			label.className = 'label';

			prev.innerHTML = '&#x25c4;';
			next.innerHTML = '&#x25ba;';
			label.innerHTML = labelContent;

			slider.appendChild(prev);
			slider.appendChild(label);
			slider.appendChild(next);

			slider.prev = prev;
			slider.next = next;

			return slider;
		}

		function changeGender()
		{
			genderIndex = genderIndex ? 0 : 1;
			changeHead(0);
			changeLegs(0);
		}

		function changeHead(indexChange)
		{
			var heads = genderIndex ? heads_f : heads_m;
			headIndex += indexChange;

			var headChar = heads[headIndex];

			if (!headChar)
			{
				headIndex = (indexChange < 0) ? heads.length - 1 : 0;
				headChar = heads[headIndex];
			}

			setHead(headChar);
		}

		function changeLegs(indexChange)
		{
			var legs = genderIndex ? legs_f : legs_m;
			legsIndex += indexChange;

			var legsChar = legs[legsIndex];

			if (!legsChar)
			{
				legsIndex = (indexChange < 0) ? legs.length - 1 : 0;
				legsChar = legs[legsIndex];
			}

			setLegs(legsChar);
		}

		function changeSkin(indexChange)
		{
			skinIndex += indexChange;

			var skinColour = skins[skinIndex];

			if (!skinColour)
			{
				skinIndex = (indexChange < 0) ? skins.length - 1 : 0;
				skinColour = skins[skinIndex];
			}

			setSkin(skinColour);
		}

		function changePants(indexChange)
		{
			pantsIndex += indexChange;

			var pantsColour = pants[pantsIndex];

			if (!pantsColour)
			{
				pantsIndex = (indexChange < 0) ? pants.length - 1 : 0;
				pantsColour = pants[pantsIndex];
			}

			setPants(pantsColour);
		}

		function begin_onclick()
		{
			request = new XMLHttpRequest();

			request.open("POST","ajax/createCharacter.php","true");
			request.setRequestHeader("Content-type","application/x-www-form-urlencoded");

			request.send("g="+genderIndex+"&h="+headIndex+'&l='+legsIndex+'&s='+skinIndex+'&p='+pantsIndex+'&n='+document.getElementById('name').value);

			request.onreadystatechange = function()
			{
				 if (request.readyState === 4 && request.status === 200)
				{
					switch(request.responseText)
					{
						case '0':
							window.location = 'play.php';
							break;

						case '1':
							alert('FAIL!');
							break;
					}
				}
			};
		}

		// -->
	</script>
</html>