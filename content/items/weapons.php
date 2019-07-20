<?php

//include "{$GLOBALS['rootPath']}engine/core/coreSprites.spr";

global $DMG_colours;

global $ospr_cold;
global $ospr_electric;
global $ospr_fire;
global $ospr_poison;
global $ospr_water;
global $ospr_trauma;

$itm_wep_silverAxe				= new eqp_sword(1, 'Silver Axe', 'A mighty axe of silver', [$spr_silverAxe]);
//$itm_wep_silverAxe->materials	= $this->materials;
$itm_wep_silverAxe->DSs			= [DS_STRENGTH => mt_rand(0, 20), DS_STRENGTH => mt_rand(0, 20)];
$itm_wep_silverAxe->DMGs		= [DMG_TRAUMA => mt_rand(20, 30)];
$itm_wep_silverAxe->DMGDL		= DMGDL_CUT;
//$itm_wep_silverAxe->DMGs_def	= [DMGDL_BLUNT => mt_rand(5, 10)];

$itm_wep_silverAxe->finish();



$itm_wep_sabre					= new eqp_sword(1, 'Sabre', 'A lovely, balanced sabre.', [$spr_sabre]);
$itm_wep_sabre->materials		= $this->materials;
$itm_wep_sabre->DSs				= [DS_AGILITY => mt_rand(10, 20), DS_FINESSE => mt_rand(20, 30)];
$itm_wep_sabre->DMGs			= [DMG_TRAUMA => 10];
$itm_wep_sabre->DMGDL			= DMGDL_CUT;
//$itm_wep_sabre->DMGs_def		= [DMGDL_POINT => mt_rand(0, 5)];

$itm_wep_sabre->finish();



$itm_wep_ltnBlade				= new eqp_sword(1, 'Lightning blade', 'A fierce sword of storms.');//, [spr_sword()->augment($ospr_electric)]);
$itm_wep_ltnBlade->materials	= $this->materials;
$itm_wep_ltnBlade->DSs			= [DS_MAGIC => mt_rand(5, 20), DS_FINESSE => mt_rand(10, 20)];
$itm_wep_ltnBlade->DMGs			= [DMG_TRAUMA => 10, DMG_ELECTRIC => 10];
$itm_wep_ltnBlade->DMGDL		= DMGDL_CUT;
//$itm_wep_ltnBlade->DMGs_def	= [DMG_ELECTRIC => mt_rand(5, 30)];
$itm_wep_ltnBlade->addBehaviour(
//		new ebhv_dealDamageOverTime(DMGDL_PLASMA, [DMG_ELECTRIC => 100], 5, 0.4),
//		new ebhv_chanceToDamageNearbyDudes(DMGDL_PLASMA, [DMG_ELECTRIC => 10], 3, 10, 3, 20, TRG_STRIKE),
		new ebhv_illuminate(2, $DMG_colours[DMG_ELECTRIC] , 0.3),
		new ebhv_dmg_electric(5, 5)
	);

$itm_wep_ltnBlade->finish();



$itm_wep_fireBlade				= new eqp_sword(1, 'Fire sword', 'A searing sword of fire.', [spr_sword()->augment($ospr_fire)]);
$itm_wep_fireBlade->materials	= $this->materials;
$itm_wep_fireBlade->materials	= $this->materials;
$itm_wep_fireBlade->DSs			= [DS_STRENGTH => mt_rand(5, 20), DS_FORCE => mt_rand(5, 20)];
$itm_wep_fireBlade->DMGs		= [DMG_TRAUMA => 4, DMG_FIRE => 5];
$itm_wep_fireBlade->DMGDL		= DMGDL_CUT;
$itm_wep_fireBlade->DSs_req		= [DS_STRENGTH => 1];
//$itm_wep_fireBlade->DMGs_def	= [DMG_WATER => mt_rand(10, 20), DMGDL_VAPOUR => mt_rand(20, 30), DMG_COLD => mt_rand(30, 45)];
$itm_wep_fireBlade->addBehaviour(
//		new ebhv_dealDamageOverTime(DMGDL_PLASMA, [DMG_FIRE => 100], 8, 0.4),
//		new ebhv_chanceToDamageNearbyDudes(DMGDL_PLASMA, [DMG_FIRE => 30, DMG_POISON => 10], 2, 10, 1, 30, TRG_DEFEND)
//		new ebhv_dmg_fire(3, 5)
//		new ebhv_dmg_poison(10, 10)
		new ebhv_dmg_water(10, 5)
//		new ebhv_dealDamagePerSecond(DMGDL_PLASMA, [DMG_FIRE => 20], 8, 0.4)
	);

$itm_wep_fireBlade->finish();

$GLOBALS['player']->inventory->add($itm_wep_fireBlade);



$itm_wep_waterBlade				= new eqp_sword(1, 'Water sword', 'Cuts and washes!', [spr_sword()->augment($ospr_water)]);
$itm_wep_waterBlade->materials	= $this->materials;
$itm_wep_waterBlade->DSs		= [DS_DEXTERITY => mt_rand(5, 20)];
$itm_wep_waterBlade->DMGs		= [DMG_TRAUMA => 10, DMG_WATER => 25];
$itm_wep_waterBlade->DMGDL		= DMGDL_CUT;
//$itm_wep_waterBlade->DMGs_def	= [DMG_FIRE => mt_rand(10, 20), DMG_ELECTRIC => mt_rand(-10, -5)];
$itm_wep_waterBlade->addBehaviour(
	new ebhv_dealDamagePerSecond(DMGDL_LIQUID, [DMG_WATER => 100], 3, 0.4)
);

$itm_wep_waterBlade->finish();



$itm_wep_psnBlade				= new eqp_sword(1, 'Poison knife', 'A noxious blade of poison.', [spr_sword()->augment($ospr_poison)]);
$itm_wep_psnBlade->materials	= $this->materials;
$itm_wep_psnBlade->DSs			= [DS_CONTROL => mt_rand(5, 20)];
$itm_wep_psnBlade->DMGs			= [DMG_TRAUMA => 10, DMG_POISON => 10];
$itm_wep_psnBlade->DMGDL		= DMGDL_CUT;
//$itm_wep_psnBlade->DMGs_def		= [DMG_POISON => mt_rand(5, 15)];
$itm_wep_psnBlade->addBehaviour(
		new ebhv_dealDamagePerSecond(DMGDL_VAPOUR, [DMG_POISON => 150], 10, 0.4)
	);

$itm_wep_psnBlade->finish();



$itm_wep_coldBlade				= new eqp_sword(1, 'Icy shard', 'Freezing blade of ancient winters.', [spr_sword()->augment($ospr_cold)]);
$itm_wep_coldBlade->materials	= $this->materials;
$itm_wep_coldBlade->DSs			= [DS_CONTROL => mt_rand(5, 20), DS_FINESSE => mt_rand(5, 20)];
$itm_wep_coldBlade->DMGs		= [DMG_TRAUMA => 2, DMG_COLD => 2];
$itm_wep_coldBlade->DMGDL		= DMGDL_CUT;
$itm_wep_coldBlade->DSs_req		= [DS_STRENGTH => 1];
//$itm_wep_coldBlade->DMGs_def	= [DMG_FIRE => mt_rand(10, 25)];
$itm_wep_coldBlade->addBehaviour(
//	new ebhv_dealDamageOverTime(DMGDL_VAPOUR, [DMG_COLD => 100], 8, 0.4)
	new ebhv_dmg_cold(3, 5)
);

$itm_wep_coldBlade->finish();



$itm_wep_sharpBlade				= new eqp_sword(1, 'Sharp dagger', 'Mind your fingers.', [spr_sword()->augment($ospr_trauma)]);
$itm_wep_sharpBlade->materials	= $this->materials;
$itm_wep_sharpBlade->DSs		= [DS_AGILITY => mt_rand(5, 20)];
$itm_wep_sharpBlade->DMGs		= [DMG_TRAUMA => 10];
$itm_wep_sharpBlade->DMGDL		= DMGDL_CUT;
$itm_wep_sharpBlade->addBehaviour(
	new ebhv_dealDamagePerSecond(DMGDL_CUT, [DMG_TRAUMA => 100], 30, 0.4), new ebhv_stealLife(20)
);

$itm_wep_sharpBlade->finish();



$itm_wep_debugger				= new eqp_sword(1, 'Debugger_' . id(mt_rand(3, 10)), 'CBF killing shit.', [spr_sword()]);
$itm_wep_debugger->materials	= $this->materials;
$itm_wep_debugger->DSs			= [DS_HP_MAX => '-30%', DS_STRENGTH => 566, DS_FORCE => 8901, DS_DEXTERITY => 1237, DS_FINESSE => 899];
$itm_wep_debugger->DMGs			= [DMG_TRAUMA => 10000];
$itm_wep_debugger->DMGDL		= DMGDL_PLASMA;
$itm_wep_debugger->DSs_req		= [DS_STRENGTH => 1];
//$itm_wep_debugger->DMGs_def	= [DMGDL_BLUNT => 100, DMGDL_CUT => 100, DMGDL_POINT => 100];

$itm_wep_debugger->finish();

$GLOBALS['player']->inventory->add($itm_wep_debugger);