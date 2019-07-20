<!DOCTYPE html>
<?php $rootPath = ''; $runningFromIndex = true; ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title id="title">AsciiLands</title>
		<link rel="shortcut icon" href="favicon.png" />
        <?php

        require "{$rootPath}engine/core/include.php";

		require "{$rootPath}engine/core/initialise.php";

        ?>

		<link rel="stylesheet" type="text/css" href="engine/css/style.php" />
		<?php if ($player instanceof EditorPlayer2) { ?><link rel="stylesheet" type="text/css" href="tools/editor_v2/style.php" /><?php } ?>

<!--		<script type="text/javascript" src="engine/javascript/jvs.constants.php"></script>
		<script type="text/javascript" src="engine/javascript/jvs.ui.php"></script>
        <script type="text/javascript" src="engine/javascript/jvs.viewFunctions.php"></script>
        <script type="text/javascript" src="engine/javascript/jvs.renderingFunctions.php"></script>
        <script type="text/javascript" src="engine/javascript/jvs.main.php"></script>
		<script type="text/javascript" src="engine/javascript/jvs.updateHandlers.php"></script>-->

		<style id="style"><?php echo $map->css; ?></style>


    </head>
    <body id="body" onload="initialise();">
		<div id="backdrop"></div>
		<div id="cursorSprite"></div>
		<div id="mapContainer">
		<div id="map"></div>
		</div>
		<div id="overlay" style="background-color:<?php echo $view->overlayColour; ?>;opacity:<?php echo $view->overlayOpacity; ?>;"></div>
		<div class="wing" id="leftWing">
			<div class="wingEdge"></div>

			<?php if (!($player instanceof EditorPlayer || $player instanceof EditorPlayer2)) { // EDITOR LINE ?>

			<div class="tabBody" header="Player" title="Player details [P]">
				<div header="Information" class="panel" id="<?php echo UPD_PLAYER_INFO; ?>"></div>
				<div header="Boons" class="panel" id="<?php echo UPD_BOONS; ?>"></div>
				<div header="Readiness" class="panel" id="<?php echo UPD_DMG_DEF; ?>"></div>
			</div>

			<div class="tabBody" header="Stats" title="Detailed player statistics [T]">
				<?php // I didn't want to do it like this but it's the best way.
				foreach ($DS_types as $DS => $unused)
				{
					echo '<div header="' . ucfirst($DS_names[$DS]) . '" class="panel" id="statType_'. $DS . '"></div>';
				}
				?>
			</div>

<!--			<div class="tabBody" header="Quests" title="Quest information [Q]">
				<div header="Current tasks" class="panel" id="<?php echo UPD_TASKS; ?>"></div>
				<div header="In progress" class="panel" id="<?php echo UPD_QUESTS; ?>"></div>
				<div header="Complete" class="panel" id="<?php echo UPD_QUESTS_C; ?>"></div>
			</div>-->

			<div header="NPC" class="tabBody" title="NPC information and combat [N]">
				<div header="Interactions" class="panel" id="<?php echo UPD_INTERACTIONS; ?>"></div>
				<div header="Status" class="panel" id="<?php echo UPD_OPPONENT; ?>"></div>
				<div header="Combat" class="panel" id="<?php echo UPD_COMBAT; ?>"></div>
			</div>				
			<?php } elseif ($player instanceof EditorPlayer2) { require "{$rootPath}tools/editor_v2/infoWing.php"; } ?>

			<div id="DEV_MODE">
				<div id="frameRate"></div>
				<div id="mapHover"></div>
			</div>
        </div>

		<div class="wing" id="rightWing">
			<div class="wingEdge"></div>

			<?php if (!($player instanceof EditorPlayer || $player instanceof EditorPlayer2)) { // EDITOR LINE ?>

			<div header="Items" class="tabBody" title="Inventory and item management [I]">
				<div header="Items" class="panel" id="<?php echo UPD_ITEMS; ?>"></div>
				<div header="Available" class="panel" id="<?php echo UPD_AVAILABLE; ?>"></div>
				<div header="Wallet" class="panel" id="<?php echo UPD_MONEY; ?>"></div>
				<div header="Item Info" class="panel" id="<?php echo UPD_ITEM_INFO; ?>"></div>
			</div>

			<div header="Skills" class="tabBody" title="Skills and ability management [K]">
				<div header="Skills" class="panel" id="<?php echo UPD_SKILLS; ?>"></div>
				<div header="Passives" class="panel" id="<?php echo UPD_PASSIVES; ?>"></div>
				<div header="Skill Info" class="panel" id="<?php echo UPD_SKILL_INFO; ?>"></div>
			</div>

			<div class="tabBody" header="Quests" title="Quest information [Q]">
				<div header="Current tasks" class="panel" id="<?php echo UPD_TASKS; ?>"></div>
				<div header="In progress" class="panel" id="<?php echo UPD_QUESTS; ?>"></div>
				<div header="Complete" class="panel" id="<?php echo UPD_QUESTS_C; ?>"></div>
			</div>

<!--			<div header="NPC" class="tabBody" title="NPC information and combat [N]">
				<div header="Interactions" class="panel" id="<?php echo UPD_INTERACTIONS; ?>"></div>
				<div header="Status" class="panel" id="<?php echo UPD_OPPONENT; ?>"></div>
				<div header="Combat" class="panel" id="<?php echo UPD_COMBAT; ?>"></div>
			</div>-->


			<div id="version">Asciilands v<?php echo VERSION; ?></div>

			<?php } elseif ($player instanceof EditorPlayer2) { require "{$rootPath}tools/editor_v2/assetWing.php"; } ?>
        </div>

		<?php if (!($player instanceof EditorPlayer)) { ?>

		<div id="<?php echo UPD_CONVERSATION; ?>">
			<div id="commStream"></div>
			<div id="commLast"></div>
			<div id="mouseTrap"></div>
		</div>

		<div id="<?php echo UPD_BINDINGS; ?>"></div>

		<div id="<?php echo UPD_STATUS; ?>"></div>

		<?php if (!($player instanceof EditorPlayer2)) { ?>

		<div id="hpContainer">
			<div id="hp"></div>
		</div>
		<div id="hpLabel"></div>

		<div id="epContainer">
			<div id="ep"></div>
		</div>
		<div id="epLabel"></div>

		<?php } } ?>

    </body>

	<script type="text/javascript" src="engine/javascript/jvs.notifyServer.php"></script>
	<script type="text/javascript" src="engine/javascript/jvs.constants.php"></script>
	<script type="text/javascript" src="engine/javascript/jvs.ui.php"></script>
	<script type="text/javascript" src="engine/javascript/jvs.viewFunctions.php"></script>
	<script type="text/javascript" src="engine/javascript/jvs.renderingFunctions.php"></script>
	<script type="text/javascript" src="engine/javascript/jvs.main.php"></script>
	<script type="text/javascript" src="engine/javascript/jvs.updateHandlers.php"></script>

<?php console_echo('End of play.php', '#aff');		//XXX
