<?php

$mapTemplatePath = "{$rootPath}tools/editor_v2/_newMapTemplate.map";

$spritesFolder = "{$rootPath}content/sprites/";
$tilesFolder = "{$rootPath}content/tiles/";
$materialsFolder = "{$rootPath}content/materials/";

$itemsFolder = "{$rootPath}content/items/";

echo "<span style=\"color:#fff\">Creating deafult map template...</span>";

function prepareAssetImports($directory)
{
	global $rootPath;
	global $echo;

	$files = dir($directory);

	$echo .= "\n\n<span style=\"color:#aff;\">Scanning {$directory} for assets...</span>";

	$reqDirectory = str_replace($rootPath, '{$rootPath}', $directory);
	$filePaths = [];

	while($file = $files->read())
	{
		if ($file === '.' || $file === '..' || $file === 'basicTerrain.til') continue;

		$echo .= "\n\tAdding {$file}";

		$filePaths[] = "//require \"{$reqDirectory}{$file}\";";
	}

	return implode("\n", $filePaths);
}

ob_start();

echo '<?php'; ?>


//=========================================<editor-fold desc="Tile Assets">
// TILE ASSETS
//=========================================
// Uncomment paths as required
//=========================================

require "{$rootPath}content/tiles/basicTerrain.til";
<?php echo prepareAssetImports($tilesFolder); ?>


//</editor-fold>

//=========================================<editor-fold desc="Sprite Assets">
// SPRITE ASSETS (mixed)
//=========================================
// Uncomment paths as required
//=========================================

<?php echo prepareAssetImports($spritesFolder); ?>


//</editor-fold>

//=========================================<editor-fold desc="Assets Workbench">
// ASSETS WORKBENCH
//=========================================
// Make your assets here. Run factory methods or build
// esoteric assets up from scratch.
//=========================================

//</editor-fold>

//=========================================<editor-fold desc="Tile Key">
// TILE KEY
//=========================================
// Add tiles by variable name to the tile array.
// First tile in the array will be the "empty space" tile.
//=========================================

$this->tileKey = [
	$t_water,
	$t_shallows,
	$t_sand,
	$t_grass,
];

//</editor-fold>

//=========================================<editor-fold desc="Scenery Key">
// SCENERY KEY
//=========================================
// Add scenery objects into this array to make them available as scenery.
// Don't forget to import the sprites above.
//=========================================

$this->sceneryKey = [

];

//</editor-fold>

//=========================================<editor-fold desc="Material Assets">
// MATERIAL ASSETS
//=========================================
// Uncomment paths as required
//=========================================

<?php echo prepareAssetImports($materialsFolder); ?>


//</editor-fold>

//=========================================<editor-fold desc="Material Array">
// MATERIAL ARRAY
//=========================================
// Add materials that are available in this area. Random items in this area
// will be made of these materials
//=========================================

$this->materials = [

];

//</editor-fold>

//=========================================<editor-fold desc="Item Assets">
// ITEM ASSETS
//=========================================
// Uncomment paths as required
//=========================================

<?php echo prepareAssetImports($itemsFolder); ?>


//</editor-fold>

//=========================================<editor-fold desc="AsObject Workbench">
// OBJECT WORKBENCH
//=========================================
// This is a good place to initiate the map's items / objects and assign
// them to variables.
// Under some circumstances it might be appropriate to create objects from
// inside the array declaration. If you think that's cool, do it.
// Obviously this isn't feasible for objects with multiple late assignments etc.
//=========================================

// Make some stuff

//</editor-fold>

//=========================================<editor-fold desc="Quest Workbench">
// QUEST WORKBENCH
//=========================================
// This is a good place to build the quest objects that are initiated in
// this map.
// The objects in the map should be already declared above so you have access to
// those but you might have to refer to them again down here to add quest-giving
// behaviours to existing objects or something.
//=========================================

// Set up some quests

//</editor-fold>

//=========================================<editor-fold desc="AsObject Array">
// OBJECT ARRAY
//=========================================
// Add objects to this array.
// AsObject order does not matter.
// The key should be a string in the format "{north offset}:{west offset}"
// (e.g., "23:44").
// If you need to put a few things on the same tile (but in different layers),
// add another colon then any combinatino of characters to make the index
// unique. The layering will sort itself out (e.g., "4:21:asdf" and "4:21:qw").
// Try to avoid creating objects inside the array. Instead, create them on the
// workbench and then clone them into the array.
//=========================================

$this->objects = [

];

//</editor-fold>

//=========================================<editor-fold desc="Extra Stuff">
// EXTRA STUFF
//=========================================
// Sometimes it might be appropriate to send a thought update unpon entering the map.
// These other variables are used by various objects and events through the game.
// (e.g., torch objects will only work in maps where $isDark is true.)
// All these values are optional so uncomment and set only as required.
//=========================================

//	update_thoughts('Haven\'t been here before.');

//	$this->viewHeight		= 10;
//  $this->viewWidth		= 10;

//	$this->overlayColour	= '#000';
//	$this->overlayOpacity	= 0.7;

//	$this->minimapTop = 0;
//	$this->minimapLeft = 0;
//	$this->minimapRight = 100;
//	$this->minimapBottom = 100;

//	$this->isDark			= true;		// Light objects only affect dark maps

//	$this->isUnderground	= true;
//	$this->isIndoor			= true;

//	$this->localCurrency	= CUR_GOLD;	// NOT YET SUPPORTED
//	$this->territory		= null;		// NOT YET SUPPORTED
//	$this->continent		= null;		// NOT YET SUPPORTED
//	$this->district			= null;		// NOT YET SUPPORTED
//	$this->landOwner		= null;		// NOT YET SUPPORTED
//	$this->landLord			= null;		// NOT YET SUPPORTED
//	$this->landKing			= null;		// NOT YET SUPPORTED
//	$this->landEmperor		= null;		// NOT YET SUPPORTED

//</editor-fold>

// NOTE: To edit the template, alter the build script createMapTemplate.php, not the template file.

<?php

if (file_put_contents($mapTemplatePath, ob_get_clean()))
{
	echo $echo;

	echo "\n\n<span style=\"color:#aaf\">New map template saved.</span>\n\n";
}
else
{
	echo $echo;

	echo "\n\n<span style=\"color:#faa\">Write to file failed!.</span>\n\n";
}