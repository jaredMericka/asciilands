<html>
	<head>
		<style>
			html{ padding:0px; margin:0px;overflow:hidden; background-color:#000; color:#8f8; }
			* { font-family:lucida console, monospace; font-size:13px; cursor:default; }
		</style>
	</head>
	<body>
		<pre><span id="consoleText">WEB ASCII CONSOLE OS [version:2.1.0948.3]

C:\></span><span id="blinky">_</span></pre>
	</body>
	<script>
		// <!--
			var blinkyTimer = setInterval(changeBlinky, 500);
			var blinky = document.getElementById('blinky');
			var consoleText = document.getElementById('consoleText');
			var typeRate = 20;

			var resMessage = "Minimum of 1366 x 768 resolution is recommended. Please press F11 for fullscreen or use your browser\'s zoom functionality.\n\n";
			var badRes = window.innerWidth < 1366 || window.innerHeight < 768;

			var initialisationArray = [
				'cd awesomeStuff\n\nC:\\awesomeStuff>',
				'cd games\n\nC:\\awesomeStuff\\Games>',
				'Asciilands.exe\n\n',
				'Running executable "Asciilands.exe"...\n\n',
				window.innerWidth.toString() + ' x ' + window.innerHeight.toString() + ' resolution detected.\n',
				badRes ? resMessage : 'Resolution OK\n\n',
				'Establishing OUTBOUND connection from <?php echo $_SERVER['REMOTE_ADDR']; ?>',
				'...',
				'SUCCESS!\n',
				'Establishing INBOUND  connection from http://asciilands.com',
				'...',
				'SUCCESS!\n\n',
				'Connection established.',
				' Retrieving assets:\n\n',
				'Typing up maps...100%\nAllocating characters to in-game characters...100%\nRendering 3D environments with font-color...100%\n'
			];

			var titleArray =
			[
				'<span style="color:#d05">                         &#x2590;             &#x2584;                                                                                          &#x2590;\n',
				'</span><span style="color:#d15">  &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;     &#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;  &#x2588;   &#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;  &#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580; &#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;    &#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;       &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;     &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;     &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;      &#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;  &#x2588;\n',
				'</span><span style="color:#d25">    &#x2584;&#x258c;     &#x2590;&#x2588;&#x2584;  &#x2588;&#x2588;&#x2580; &#x258c;&#x2580;&#x2588;&#x2588;&#x2588;&#x2580;  &#x2590;&#x2588;&#x2580; &#x258c;             &#x258c;       &#x258c;   &#x2584;&#x2588;&#x258c; &#x258c;             &#x2584;&#x258c;     &#x2590;&#x2588;&#x2584;     &#x2588;&#x2588;&#x2590;    &#x2590;&#x2584;       &#x2588;&#x2588;&#x258c;  &#x258c; &#x2580;&#x2588;&#x2588;&#x258c;   &#x2588;&#x2588;&#x2580; &#x258c;&#x2580;&#x2588;&#x2588;&#x2588;&#x2580;\n',
				'</span><span style="color:#d35">  &#x2584;&#x2588;&#x2580;&#x258c;     &#x2590;&#x2588;&#x2588;  &#x2588;&#x2588;  &#x258c;       &#x2588;&#x258c;  &#x258c;          &#x2584;&#x2588; &#x258c;    &#x2584;&#x2588; &#x258c;  &#x2580;&#x2588;&#x2588;&#x258c; &#x258c;           &#x2584;&#x2588;&#x2580;&#x258c;     &#x2590;&#x2588;&#x2588;     &#x2588;&#x2588;&#x2590;     &#x2580;&#x2588;&#x2584;     &#x2588;&#x2588;&#x258c;  &#x258c;   &#x2588;&#x2588;&#x258c;  &#x2588;&#x2588;  &#x258c;\n',
				'</span><span style="color:#d45"> &#x2590;&#x2588;&#x258c; &#x258c;     &#x2590;&#x2588;&#x2588;  &#x2588;&#x2588;&#x2584; &#x258c;       &#x2588;&#x258c;  &#x258c;         &#x2580;&#x2588;&#x2588; &#x258c;   &#x2580;&#x2588;&#x2588; &#x258c;   &#x2588;&#x2588;&#x258c; &#x258c;          &#x2590;&#x2588;&#x258c; &#x258c;     &#x2590;&#x2588;&#x2588;     &#x2588;&#x2588;&#x2590;      &#x2590;&#x2588;&#x258c;    &#x2588;&#x2588;&#x258c;  &#x258c;   &#x2590;&#x2588;&#x2588;  &#x2588;&#x2588;&#x2584; &#x258c;\n',
				'</span><span style="color:#d55"> &#x2588;&#x2588;  &#x258c;     &#x2590;&#x2588;&#x2588;  &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;   &#x2588;&#x258c;  &#x258c;          &#x2588;&#x2588; &#x258c;    &#x2588;&#x2588; &#x258c;   &#x2588;&#x2588;&#x258c; &#x258c;          &#x2588;&#x2588;  &#x258c;     &#x2590;&#x2588;&#x2588;     &#x2588;&#x2588;&#x2590;       &#x2588;&#x2588;    &#x2588;&#x2588;&#x258c;  &#x258c;    &#x2588;&#x2588;&#x258c; &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;\n',
				'</span><span style="color:#d65">&#x2590;&#x2588;&#x258c;  &#x258c;     &#x2590;&#x2588;&#x2588;      &#x258c; &#x2580;&#x2588;&#x2588;&#x2584;  &#x2588;&#x2588;  &#x258c;    &#x2584;     &#x2588;&#x2588; &#x258c;    &#x2588;&#x2588; &#x258c;   &#x2588;&#x2588;&#x258c; &#x258c;         &#x2590;&#x2588;&#x258c;  &#x258c;     &#x2590;&#x2588;&#x2588;     &#x2588;&#x2588;&#x2590;       &#x2590;&#x2588;&#x258c;   &#x2588;&#x2588;&#x258c;  &#x258c;    &#x2590;&#x2588;&#x2588;     &#x258c; &#x2580;&#x2588;&#x2588;&#x2584;\n',
				'</span><span style="color:#d75">&#x2588;&#x2588;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;      &#x258c;  &#x2580;&#x2588;&#x2588;&#x258c; &#x2588;&#x2588;&#x258c; &#x258c;   &#x2590;&#x2588;&#x258c;    &#x2588;&#x2588; &#x258c;    &#x2588;&#x2588; &#x258c;   &#x2588;&#x2588;&#x258c; &#x258c;         &#x2588;&#x2588;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;     &#x2588;&#x2588;&#x2590;       &#x2590;&#x2588;&#x2588;   &#x2588;&#x2588;&#x258c;  &#x258c;    &#x2590;&#x2588;&#x2588;     &#x258c;  &#x2580;&#x2588;&#x2588;&#x258c;\n',
				'</span><span style="color:#d85">&#x2588;&#x2588;   &#x258c;     &#x2590;&#x2588;&#x2588;   &#x2584;&#x258c; &#x258c;   &#x2588;&#x2588;&#x258c; &#x2590;&#x2588;&#x2588; &#x258c;    &#x2588;     &#x2588;&#x2588; &#x258c;    &#x2588;&#x2588; &#x258c;   &#x2588;&#x2588;&#x258c; &#x258c;      &#x2590;  &#x2588;&#x2588;   &#x258c;     &#x2590;&#x2588;&#x2588;     &#x2588;&#x2588;&#x2590;       &#x2588;&#x2588;&#x2588;   &#x2588;&#x2588;&#x258c;  &#x258c;    &#x2590;&#x2588;&#x258c;  &#x2584;&#x258c; &#x258c;   &#x2588;&#x2588;&#x258c;\n',
				'</span><span style="color:#d95">&#x2588;&#x2588;&#x258c;  &#x258c;     &#x2590;&#x2588;&#x2588;  &#x2588;&#x2580; &#x2590;&#x2588;   &#x2588;&#x2588;&#x258c;  &#x2588;&#x2588;&#x258c;&#x258c;    &#x258c;     &#x2588;&#x2588; &#x258c;    &#x2588;&#x2588; &#x258c;   &#x2588;&#x2588;&#x258c; &#x258c;       &#x258c; &#x2588;&#x2588;&#x258c;  &#x258c;     &#x2590;&#x2588;&#x2588;     &#x2588;&#x2588;&#x2590;       &#x2588;&#x2588;&#x2588;   &#x2588;&#x2588;&#x258c;  &#x258c;    &#x2588;&#x2588;  &#x2588;&#x2580; &#x2590;&#x2588;   &#x2588;&#x2588;&#x258c;\n',
				'</span><span style="color:#da5">&#x2588;&#x2588;&#x2588;  &#x258c;     &#x2590;&#x2588;&#x2588;  &#x2588;&#x2584;  &#x258c;  &#x2588;&#x2588;&#x2588;&#x258c;  &#x2590;&#x2588;&#x2588;&#x258c;   &#x2590;      &#x2588;&#x2588; &#x258c;    &#x2588;&#x2588; &#x258c;   &#x2588;&#x2588;&#x2588;&#x2584;&#x258c;      &#x2590;  &#x2588;&#x2588;&#x2588;  &#x258c;     &#x2590;&#x2588;&#x2588;     &#x2588;&#x2588;&#x2590;      &#x2590;&#x2588;&#x2588;&#x2588;   &#x2588;&#x2588;&#x258c;  &#x258c;   &#x2590;&#x2588;   &#x2588;&#x2584;  &#x258c;  &#x2588;&#x2588;&#x2588;&#x258c;\n',
				'</span><span style="color:#db5">&#x2590;&#x2588;&#x2588;&#x258c; &#x258c;     &#x2590;&#x2588;&#x2588;  &#x2580;&#x2588;&#x2588;&#x2584;&#x258c;&#x2584;&#x2588;&#x2588;&#x2588;&#x2580;    &#x2588;&#x2588;&#x2588;&#x2584;&#x2584;&#x2584;&#x258c;      &#x2588;&#x2588; &#x258c;    &#x2588;&#x2588; &#x258c;  &#x2590;&#x2588;&#x2580;&#x2584;&#x2584;&#x2588;&#x2584;&#x2584;&#x2584;&#x2584;&#x2584;&#x2584;&#x2588;  &#x2590;&#x2588;&#x2588;&#x258c; &#x258c;     &#x2590;&#x2588;&#x2588;     &#x2588;&#x2588;&#x2590;      &#x2588;&#x2588;&#x2588;&#x2588;   &#x2588;&#x2588;&#x258c;  &#x258c;   &#x2580;    &#x2580;&#x2588;&#x2588;&#x2584;&#x258c;&#x2584;&#x2588;&#x2588;&#x2588;&#x2580;\n',
				'</span><span style="color:#dc5"> &#x2588;&#x2588;&#x2588;&#x2584;&#x258c;   &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;  &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;       &#x2580;&#x2588;&#x2588;&#x2588;&#x2580;     &#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;  &#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;  &#x2580;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;   &#x2588;&#x2588;&#x2588;&#x2584;&#x258c;   &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584; &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;   &#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x258c; &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;       &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;\n',
				'</span><span style="color:#dd5"> &#x2590;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;                                                                  &#x2590;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;                  &#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;\n',
				'</span><span style="color:#de5">  &#x2580;&#x2588;&#x2588;&#x2588;&#x258c;       An Adventure with [unicode] character(s)!                   &#x2580;&#x2588;&#x2588;&#x2588;&#x258c;                    &#x2588;&#x2588;&#x2588;&#x258c;\n',
				'</span><span style="color:#df5">    &#x2580;&#x2588;                                                                      &#x2580;&#x2588;                      &#x2588;&#x2580;\n',
				'</span>'
			];

			var creditsArray =
			[
				'\nBy Jared Mericka and Julian Koplin\n\n',
				'With help from:\n'
			];

			var specialThanks = [
				'\nKraig Kirsch\t\t\tCasey Quinn\t\t\tJeanette Mericka',
				'\nNick Lang\t\t\tMaddy Koplin\t\t\tAaron Mericka',
				'\nNathan Mericka\t\t\tBanj Donald\t\t\tPaddy Dennis'
			];

			function changeBlinky()
			{
				blinky.innerHTML = (blinky.innerHTML === '_' ? '&#x2588;' : '_');
			}


			function arrayTyper(array, speed, lineByLine)
			{
				this.textArray = array;
				this.speed = speed;
				this.lineByLine = (lineByLine === true ? true : false);

				this.arrayIndex = 0;
				this.charIndex = 0;

				this.execute = function()
				{
					this.typeTimer = setInterval('typer.typeChunk()', this.speed);
				};

				this.typeChunk = function()
				{
					if (this.textArray[this.arrayIndex] === undefined)
					{
						clearInterval(this.typeTimer);
						run();
						return;
					}

					if (this.lineByLine)
					{
						consoleText.innerHTML = consoleText.innerHTML + this.textArray[this.arrayIndex];
						this.arrayIndex++;
					}
					else
					{
						var chunk = this.textArray[this.arrayIndex].charAt(this.charIndex);

						switch (chunk)
						{
							case '&':
								var entityEnd = this.textArray[this.arrayIndex].indexOf(';', this.charIndex);
								chunk = this.text.subString(this.nextChar, entityEnd);
								this.charIndex = ++entityEnd;
								break;

							case '':
								this.arrayIndex++;
								this.charIndex = 0;
								clearInterval(this.typeTimer);
								setTimeout('typer.execute()', 100 + (Math.random()*500));
								return;
								break;

							default:
								this.charIndex++;
						}

						consoleText.innerHTML = consoleText.innerHTML + chunk;
					}
				};

				setTimeout('typer.execute()', 500);

			}

			var phase = 0;
			function run()
			{
				switch (phase)
				{
					case 0:
						typer = new arrayTyper(initialisationArray, 20, false);
						break;

					case 1:
						typer = new arrayTyper(titleArray, 20, true);
						break;

					case 2:
						typer = new arrayTyper(creditsArray, 20, false);
						break;

					case 3:
						typer = new arrayTyper(specialThanks, 50, true);
						break;

					default:
						window.location = 'play.php';
				}
				phase ++;
			}

			document.onkeyup = function (e)
			{
				switch(e.keyCode)
				{
					case 27: // ESC
					case 13: // Enter
					case 32: // Space
						phase = -1;
						run();
						break;
				}
			};

			run();

		// -->
	</script>
</html>