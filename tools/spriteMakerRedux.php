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

			body
			{
				padding-top:110px;
				margin:0px;
			}

			#workspace
			{
				margin-left:10px;
				width:400px;
			}

			#addFrame
			{
				margin-left:10px;
				margin-top:10px;
				float:left;
				padding: 6px;
				background-color:#a00;
				color:#fff;
			}

			#results
			{
				position:fixed;
				top:0px;
				height:100px;
				width:100%;
				background-color:#ddd;
				border-bottom-style:solid;
				border-bottom-width:2px;
				border-bottom-color:#777;
			}

			.button
			{
				margin:1px;
				background-color:#777;
				color:#fff;
				padding:0px 2px;
			}

			.button:hover { background-color:#aaa; }
			.button:active { background-color:#444; }

			.buttonContainer
			{
				position:absolute;
				right:4px;
				bottom:4px;
			}

			input
			{
				/*background-color:#aaa;*/
				border:none;
				margin:2px;
				width:300px;
			}

			.framePanel
			{
				position:relative;
				background-color:#ddd;
				border-bottom-style:solid;
				border-bottom-width:2px;
				border-bottom-color:#777;
				padding:2px;
			}

			.framePreview
			{
				background-color:#000;
				width:<?php echo CHAR_WIDTH * 3; ?>px;
				height:<?php echo CHAR_HEIGHT * 2; ?>px;
				position:absolute;
				right:10px;
				top:10px;
			}

			#fullPreview
			{
				position:absolute;
				top:10px;
				left:10px;
				background-color:#000;
				width:<?php echo CHAR_WIDTH * 3; ?>px;
				height:<?php echo CHAR_HEIGHT * 2; ?>px;
			}

			#previewBg
			{
				position:absolute;
				bottom:10px;
				left:10px;
				width:<?php echo CHAR_WIDTH * 4; ?>px;
			}

			#code
			{
				position:absolute;
				top:10px;
				left:50px;
				height:80px;
				width:500px;
				resize:none;
			}

			#iframeContainer
			{
				position:fixed;
				top:0px;
				bottom:0px;
				right:0px;
				left:600px;
				background-color:#fff;
				border-left-style:solid;
				border-left-width:2px;
				border-left-color:#777;
			}

			iframe
			{
				height:100%;
				width:100%;
				border:none;
			}
		</style>

		<script type="text/javascript">

			var fpFrame = 0; // Full preview frame
			var frameTimer;
			var spriteData;
			var workspace;

			function initialise ()
			{
				workspace = document.getElementById('workspace');
				addFrame();
				frameTimer = setInterval(fullPreviewAnimate, 400);
				document.getElementById('previewBg').onkeyup = setPreviewBg;
			}

			function addFrame() { workspace.appendChild(getFramePanel()); }

			function getFramePanel()
			{
				var framePanel = document.createElement('div');
				framePanel.className = 'framePanel';

				framePanel.charInput	= document.createElement('input');
				framePanel.bgInput		= document.createElement('input');
				framePanel.fgInput		= document.createElement('input');
				framePanel.preview		= document.createElement('div');

				framePanel.preview.style.backgroundColor = document.getElementById('fullPreview').style.backgroundColor;

				framePanel.buttonContainer	= document.createElement('span');

				framePanel.buttonDelete		= document.createElement('span');
				framePanel.buttonDuplicate	= document.createElement('span');
				framePanel.buttonMoveUp		= document.createElement('span');
				framePanel.buttonMoveDown	= document.createElement('span');

				framePanel.buttonDelete.className		= 'button';
				framePanel.buttonDuplicate.className	= 'button';
				framePanel.buttonMoveUp.className		= 'button';
				framePanel.buttonMoveDown.className		= 'button';

				framePanel.buttonContainer.className	= 'buttonContainer';

				framePanel.buttonDelete.innerHTML		= 'X';
				framePanel.buttonDuplicate.innerHTML	= 'D';
				framePanel.buttonMoveUp.innerHTML		= '&#x25b2;';
				framePanel.buttonMoveDown.innerHTML		= '&#x25bc;';

				framePanel.buttonDelete.framePanel		= framePanel;
				framePanel.buttonDuplicate.framePanel	= framePanel;
				framePanel.buttonMoveUp.framePanel		= framePanel;
				framePanel.buttonMoveDown.framePanel	= framePanel;

				framePanel.buttonDelete.onclick		= button_delete;
				framePanel.buttonDuplicate.onclick	= button_duplicate;
				framePanel.buttonMoveUp.onclick		= button_moveUp;
				framePanel.buttonMoveDown.onclick	= button_moveDown;

				framePanel.charInput.type	= 'text';
				framePanel.bgInput.type		= 'text';
				framePanel.fgInput.type		= 'text';

				framePanel.charInput.placeholder	= 'Characters';
				framePanel.bgInput.placeholder		= 'Background colours';
				framePanel.fgInput.placeholder		= 'Foreground colours';

				framePanel.charInput.onkeyup	= refreshSpriteData;
				framePanel.bgInput.onkeyup		= refreshSpriteData;
				framePanel.fgInput.onkeyup		= refreshSpriteData;

				framePanel.preview.className = 'framePreview';

				framePanel.appendChild(framePanel.charInput);
				framePanel.appendChild(framePanel.bgInput);
				framePanel.appendChild(framePanel.fgInput);
				framePanel.appendChild(framePanel.preview);

				framePanel.buttonContainer.appendChild(framePanel.buttonDelete);
				framePanel.buttonContainer.appendChild(framePanel.buttonDuplicate);
				framePanel.buttonContainer.appendChild(framePanel.buttonMoveUp);
				framePanel.buttonContainer.appendChild(framePanel.buttonMoveDown);

				framePanel.appendChild(framePanel.buttonContainer);

				return framePanel;
			}

			function button_delete ()
			{
				workspace.removeChild(this.framePanel);
			}

			function button_duplicate()
			{
				var newFramePanel = getFramePanel();

				newFramePanel.charInput.value = this.framePanel.charInput.value;
				newFramePanel.bgInput.value = this.framePanel.bgInput.value;
				newFramePanel.fgInput.value = this.framePanel.fgInput.value;

				workspace.insertBefore(newFramePanel, this.framePanel);

				refreshSpriteData();
			}

			function button_moveUp ()
			{
				var nodeIndex = Array.prototype.indexOf.call(this.framePanel.parentNode.children, this.framePanel);
				var framePanel = this.framePanel;

				if (nodeIndex === 0) return;

				nodeIndex --;
				workspace.removeChild(this.framePanel);

				workspace.insertBefore(framePanel, workspace.children[nodeIndex]);
			}

			function button_moveDown ()
			{
				var nodeIndex = Array.prototype.indexOf.call(this.framePanel.parentNode.children, this.framePanel);
				var framePanel = this.framePanel;

				nodeIndex ++;
				workspace.removeChild(this.framePanel);

				if (workspace.children[nodeIndex])
				{
					workspace.insertBefore(framePanel, workspace.children[nodeIndex]);
				}
				else
				{
					workspace.appendChild(framePanel);
				}
			}

			function refreshSpriteData ()
			{
//				var workspace = document.getElementById('workspace');
				var codeString = '= new Sprite([';

				for (var i = 0; i < workspace.children.length; i ++)
				{
					var framePanel = workspace.children[i];

					var chars = framePanel.charInput.value.split(' ');
					var bgs = framePanel.bgInput.value.split(' ');
					var fgs = framePanel.fgInput.value.split(' ');

					var previewString = '';

					codeString = codeString + '\n\t[';

					for (var j = 0; j < 6; j ++)
					{
						// Just look at this nasty shit:
						var char	= chars	[j]	? chars	[j]	: null;
						var bg		= bgs	[j]	? bgs	[j]	: null;
						var fg		= fgs	[j]	? fgs	[j]	: chars[j] ? '#000' : null;

						if (j === 3) previewString = previewString + '<br>';

						previewString = previewString + '<span style="';

						if (bg) previewString = previewString + 'background-color:'	+ bg + ';';
						if (fg) previewString = previewString + 'color:'			+ fg + ';';

						previewString = previewString + '">' + (char ? char : '&nbsp;') + '</span>';

						bg		= bg	? "'" + bg		+ "', " : null;
						fg		= fg	? "'" + fg		+ "', " : null;
						char	= char	? "'" + char	+ "'" : null;

						if (char || bg)
						{
							codeString = codeString + '\n\t\t' + j + ' => new SpriteElement('
								+ (bg ? bg : 'null,')
								+ (fg ? fg : ' null,')
								+ (char ? char : " '&nbsp;'") + "),";
						}
					}

					codeString = codeString + '\n\t],';

					framePanel.preview.innerHTML = previewString;
				}

				codeString  = codeString + '\n]);';
				document.getElementById('code').value = codeString;
			}

			function fullPreviewAnimate ()
			{
				var frameCount	= document.getElementById('workspace').children.length - 1;
				var frames		= document.getElementById('workspace').children;
				var fullPreview	= document.getElementById('fullPreview');

				fpFrame ++;

				if (fpFrame > frameCount) fpFrame = 0;

				fullPreview.innerHTML = frames[fpFrame].preview.innerHTML;
			}

			function setPreviewBg()
			{
				var previews = document.getElementsByClassName('framePreview');
				var fullPreview = document.getElementById('fullPreview');

				for (var i = 0; i < previews.length; i++)
				{
					previews[i].style.backgroundColor = this.value;
				}

				fullPreview.style.backgroundColor = this.value;
			}

		</script>
	</head>
	<body onload="initialise()">
		<div id="workspace"></div>
		<div id="addFrame" onclick="addFrame()">frames++</div>
		<div id="results">
			<div id="fullPreview"></div>
			<textarea id="code"></textarea>
			<input type="text" id="previewBg" />
		</div>


		<div id="iframeContainer">
			<iframe src="http://localhost:<?php echo $_SERVER['SERVER_PORT']; ?>/asciilands/tools/charMapTE.php"></iframe>
		</div>
	</body>
</html>