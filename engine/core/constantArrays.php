<?php

// THIS FILE ISN'T MADE REDUNDANT BY "_constantArrays.php"!
// That said, if you add something to build script that replaces
// one of these arrays, make sure you delete the array out of here
// so that we can keep tabs on exactly HOW useful this thing is.

$DIR_opposites = [
	DIR_NORTH	=> DIR_SOUTH	,
	DIR_SOUTH	=> DIR_NORTH	,
	DIR_EAST	=> DIR_WEST		,
	DIR_WEST	=> DIR_EAST		,
];

$DMGDL_constants =
[
	DMGDL_BLUNT		,
	DMGDL_POINT		,
	DMGDL_CUT		,
	DMGDL_MISSILE	,
	DMGDL_LIQUID	,
	DMGDL_VAPOUR	,
	DMGDL_PLASMA	,
];


$DMG_constants =
[
	DMG_TRAUMA		,
	DMG_FIRE		,
	DMG_COLD		,
	DMG_ELECTRIC	,
	DMG_WATER		,
	DMG_POISON		,
	DMG_INFECTION		,
];

$DS_colours =
[
	DS_STRENGTH		=>	'#c00',
	DS_AGILITY		=>	'#0c0',
	DS_MAGIC		=>	'#0cc',
	DS_CHARISMA		=>	'#c0c',
	DS_INTELLECT	=>	'#cc0',
];


$TRG_readable = [
	TRG_ATTACK		=> 'when you attack',
	TRG_MISS		=> 'when you miss',
	TRG_STRIKE		=> 'when you land a hit',
	TRG_KILL		=> 'when you make a kill',

	TRG_DEFEND		=> 'when you are attacked',
	TRG_DEFLECT		=> 'when you deflect an attack',
	TRG_TAKE_HIT	=> 'when you take a hit',
	TRG_DEATH		=> 'when you die',

	TRG_EQUIP		=> 'when equipped',
	TRG_UNEQUIP		=> 'when unequipped',
	TRG_MAP_CHANGE	=> 'when you change area',

	TRG_COLLECT		=> 'when collected',
	TRG_DROP		=> 'when dropped',
	TRG_USE			=> 'when used',

	TRG_IDLE		=> 'when idle',
	TRG_COLLISION	=> 'when it collides with something',
	TRG_REACTION	=> 'when interacted with',
	TRG_ENGAGE		=> 'when engaged',
	TRG_DISENGAGE	=> 'when disengaged',
];

$TRG_array = [
	TRG_ATTACK,
	TRG_MISS,
	TRG_STRIKE,
	TRG_KILL,

	TRG_DEFEND,
	TRG_DEFLECT,
	TRG_TAKE_HIT,
	TRG_DEATH,

	TRG_EQUIP,
	TRG_UNEQUIP,
	TRG_MAP_CHANGE,

	TRG_COLLECT,
	TRG_DROP,
	TRG_USE,

	TRG_IDLE,
	TRG_COLLISION,
	TRG_REACTION,
	TRG_ENGAGE,
	TRG_DISENGAGE,
];

// How does this thing work? Explain it when you know.

$FAC_standing = [
	FAC_PLAYER =>
	[
		FAC_MONSTER => -1,
		FAC_ANIMAL => -1,
	],

	FAC_ANIMAL =>
	[

	],

	FAC_MONSTER =>
	[
		FAC_ANIMAL => -1,
		FAC_PLAYER => -1,
		FAC_NPC_NEUTRAL => -1
	],

	FAC_NPC_NEUTRAL =>
	[

	]
];

