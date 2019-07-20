<div id="info">
	<div class="editorSubHeader">TILE CLIPBOARD</div>
	<div class="clipboard" id="tileClipboard" onclick="notifyServer('EDITOR_PASTE', UIN_CLICK, 'tiles');">
		<?php
			if (isset($player->copiedTiles))
			{
				echo $player->tileClipboardCache;
			}
			else
			{
				echo '<span class="fade">Nothing</span>';
			}
		?>
	</div>
	<div class="editorSubHeader">SCENERY CLIPBOARD</div>
	<div class="clipboard" id="sceneryClipboard" onclick="notifyServer('EDITOR_PASTE', UIN_CLICK, 'scenery');">
		<?php
				if (isset($player->copiedScenery))
				{
					echo $player->sceneryClipboardCache;
				}
				else
				{
					echo '<span class="fade">Nothing</span>';
				}
		?>
	</div>
</div>