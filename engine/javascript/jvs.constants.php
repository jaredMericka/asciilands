<?php if (false) { ?> <script> <?php }
header("content-type: application/javascript");

$rootPath = '../../';
require "{$rootPath}engine/core/constants.php";
require "{$rootPath}engine/core/constantArrays.php";
require "{$rootPath}engine/core/_constantArrays.php";
require "{$rootPath}engine/core/_soundIndex.php";

?>

SOUND_PATH				= 'content/sounds/';

SND_COMMS				= <?php echo SND_COMMS;		?>;

WING_WIDTH				= <?php echo WING_WIDTH;	?>;
CHAR_HEIGHT				= <?php echo CHAR_HEIGHT;	?>;
CHAR_WIDTH				= <?php echo CHAR_WIDTH;	?>;

UIN_CLICK				= <?php echo UIN_CLICK;					?>;
UIN_RIGHT_CLICK			= <?php echo UIN_RIGHT_CLICK;			?>;
UIN_CTRL_CLICK			= <?php echo UIN_CTRL_CLICK;			?>;
UIN_CTRL_RIGHT_CLICK	= <?php echo UIN_CTRL_RIGHT_CLICK;		?>;
UIN_ALT_CLICK			= <?php echo UIN_ALT_CLICK;				?>;
UIN_ALT_RIGHT_CLICK		= <?php echo UIN_ALT_RIGHT_CLICK;		?>;
UIN_CTRL_ALT_CLICK		= <?php echo UIN_CTRL_ALT_CLICK;		?>;
UIN_CTRL_ALT_RIGHT_CLICK= <?php echo UIN_CTRL_ALT_RIGHT_CLICK;	?>;
UIN_TEXT				= <?php echo UIN_TEXT;					?>;

DMG_names				= <?php echo json_encode($DMG_names);		?>;
DMGDL_names				= <?php echo json_encode($DMGDL_names);		?>;
DS_names				= <?php echo json_encode($DS_names);		?>;
DS_descriptions			= <?php echo json_encode($DS_descriptions);	?>;
DS_types_core			= <?php echo json_encode($DS_types_core);	?>;
DS_types_subs			= <?php echo json_encode($DS_types_subs);	?>;
SKLS_values				= <?php echo json_encode($SKLS_values);		?>;
TEQT_names				= <?php echo json_encode($TEQT_names);		?>;
TEQ_names				= <?php echo json_encode($TEQ_names);		?>;
SND_files				= <?php echo json_encode($SND_files);		?>;