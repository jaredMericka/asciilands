<?php //
//$zombieColour = '#0f0';
//
//$spr_zombie_m = new Sprite([
//	[ // Both hands up
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		new SpriteElement(null, $zombieColour, 'o'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		4 => new SpriteElement(null, '#400', '&lambda;'),
//	],
//	[ // Left hand down, right hand up
//		1 => new SpriteElement(null, $zombieColour, 'o'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		4 => new SpriteElement(null, '#400', '&lambda;'),
//	],
//	[ // Both hands down
//		1 => new SpriteElement(null, $zombieColour, 'o'),
//		3 => new SpriteElement(null, $zombieColour, '&deg;'),
//		new SpriteElement(null, '#400', '&lambda;'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//	],
//	[ // Left hand up, right hand down
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		new SpriteElement(null, $zombieColour, 'o'),
//		4 => new SpriteElement(null, '#400', '&lambda;'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//	],
//]);
//
//$spr_zombie_f = new Sprite([
//	[ // Both hands up
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		new SpriteElement(null, $zombieColour, 'o'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		4 => new SpriteElement(null, '#400', '&Delta;'),
//	],
//	[ // Left hand up, right hand down
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		new SpriteElement(null, $zombieColour, 'o'),
//		4 => new SpriteElement(null, '#400', '&Delta;'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//	],
//	[ // Both hands down
//		1 => new SpriteElement(null, $zombieColour, 'o'),
//		3 => new SpriteElement(null, $zombieColour, '&deg;'),
//		new SpriteElement(null, '#400', '&Delta;'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//	],
//	[ // Left hand down, right hand up
//		1 => new SpriteElement(null, $zombieColour, 'o'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		new SpriteElement(null, $zombieColour, '&deg;'),
//		4 => new SpriteElement(null, '#400', '&Delta;'),
//	],
//]);
//
//$spr_zombieCorpse_m = spr_personCorpse($spr_zombie_m, '#f50');
//$spr_zombieCorpse_f = spr_personCorpse($spr_zombie_f, '#f50');
//
//$DSs_zombie = [
//	DS_HANDICAP => 0.3,
//	DS_HP_MAX => 140,
//	DS_EP_MAX => 80,
//	DS_LUCK => 0,
//	DS_SPEED => 1,
//	DS_SPEED_FAST => 0.4,
//	DS_ATTACKSPEED => 0.4,
//	DS_STRENGTH => 90,
//	DS_FORCE => 70,
//	DS_RESILIENCE => 130,
//	DS_CONTROL => 20,
//	DS_INERTIA => 110,
//	DS_RECOVERY => 70,
//	DS_AGILITY => 40,
//	DS_DEXTERITY => 20,
//	DS_EVASIVENESS => 10,
//	DS_FINESSE => 5,
//	DS_BALANCE => 60,
//	DS_MAGIC => 5,
//	DS_DISRUPTION => 4,
//	DS_DISCIPLINE => 2,
//	DS_FOCUS => 3,
//	DS_INSANITY => 200,
//	DS_CHARISMA => 0,
//	DS_REPUTATION => 0,
//	DS_NOTORIETY => 20,
//	DS_FAME => 0,
//	DS_DISCOUNT => 0,
//	DS_BARGAINING => 0,
//	DS_LEADERSHIP => 0,
//	DS_INTELLECT => 10,
//	DS_TENACITY => 40,
//	DS_KNOWLEDGE => 0,
//	DS_HEURISTICS => 20,
//	DS_JUDGEMENT => 10,
//];
//
//$zombieSpriteSet =
//[
//	SPRI_MALE			=> $spr_zombie_m,
//	SPRI_MALE_CORPSE	=> $spr_zombieCorpse_m,
//	SPRI_FEMALE			=> $spr_zombie_f,
//	SPRI_FEMALE_CORPSE	=> $spr_zombieCorpse_f
//];
//
//$spr_skeleton = new Sprite([
//	1 => new SpriteElement(null, '#fda', '&#x2640;'),
//	3 => new SpriteElement(null, '#fda', '&deg;'),
//	4 => new SpriteElement(null, '#fda', '&Lambda;'),
//	5 => new SpriteElement(null, '#fda', '&deg;'),
//	]);
//
//$spr_skeleton_corpse = new Sprite([
//	3 => new SpriteElement(null, '#fda', '&#x2640;'),
//	4 => new SpriteElement(null, '#fda', '&omega;'),
//	5 => new SpriteElement(null, '#fda', '<'),
//	]);
//
//$DSs_skeleton = [
//	DS_HANDICAP => 0.8,
//	DS_HP_MAX => 74,
//	DS_EP_MAX => 80,
//	DS_LUCK => 60,
//	DS_SPEED => 1,
//	DS_SPEED_FAST => 0.4,
//	DS_STRENGTH => 90,
//	DS_FORCE => 80,
//	DS_RESILIENCE => 120,
//	DS_CONTROL => 90,
//	DS_INERTIA => 90,
//	DS_RECOVERY => 80,
//	DS_AGILITY => 90,
//	DS_DEXTERITY => 90,
//	DS_EVASIVENESS => 110,
//	DS_FINESSE => 90,
//	DS_BALANCE => 110,
//	DS_MAGIC => 130,
//	DS_DISRUPTION => 120,
//	DS_DISCIPLINE => 110,
//	DS_FOCUS => 80,
//	DS_INSANITY => 170,
//	DS_CHARISMA => 1,
//	DS_REPUTATION => 0,
//	DS_NOTORIETY => 0,
//	DS_FAME => 0,
//	DS_DISCOUNT => 0,
//	DS_BARGAINING => 0,
//	DS_LEADERSHIP => 0,
//	DS_INTELLECT => 70,
//	DS_TENACITY => 60,
//	DS_KNOWLEDGE => 20,
//	DS_HEURISTICS => 20,
//	DS_JUDGEMENT => 70,
//];
//
//$skeletonSpriteSet =
//[
//	SPRI_DEFAULT	=> $spr_skeleton,
//	SPRI_CORPSE		=> $spr_skeleton_corpse
//];
//
//$golemColour = '#763';
//
//$spr_golem = new Sprite([
//	0 => new SpriteElement(null, $golemColour, '&#x2584;'),
//	1 => new SpriteElement($golemColour, '#000', '&#x201c;'),
//	2 => new SpriteElement(null, $golemColour, '&#x2584;'),
//	3 => new SpriteElement(null, $golemColour, '&#x258c;'),
//	4 => new SpriteElement(null, $golemColour, '&Pi;'),
//	5 => new SpriteElement(null, $golemColour, '&#x2590;'),
//	]);
//
//$spr_golem_corpse = new Sprite([
//	3 => new SpriteElement(null, $golemColour, '&#x2584;'),
//	4 => new SpriteElement($golemColour, '#000', '-'),
//	5 => new SpriteElement(null, $golemColour, '&#x2584;'),
//	]);
//
//$DSs_golem = [
//	DS_HANDICAP => 1.2,
//	DS_HP_MAX => 240,
//	DS_EP_MAX => 120,
//	DS_LUCK => 80,
//	DS_SPEED => 1.6,
//	DS_SPEED_FAST => 1.6,
//	DS_STRENGTH => 370,
//	DS_FORCE => 410,
//	DS_RESILIENCE => 320,
//	DS_CONTROL => 40,
//	DS_INERTIA => 220,
//	DS_RECOVERY => 10,
//	DS_AGILITY => 2,
//	DS_DEXTERITY => 40,
//	DS_EVASIVENESS => 1,
//	DS_FINESSE => 1,
//	DS_BALANCE => 200,
//	DS_MAGIC => 90,
//	DS_DISRUPTION => 30,
//	DS_DISCIPLINE => 100,
//	DS_FOCUS => 170,
//	DS_INSANITY => 8,
//	DS_CHARISMA => 70,
//	DS_REPUTATION => 1,
//	DS_NOTORIETY => 1,
//	DS_FAME => 1,
//	DS_DISCOUNT => 1,
//	DS_BARGAINING => 1,
//	DS_LEADERSHIP => 1,
//	DS_INTELLECT => 40,
//	DS_TENACITY => 5,
//	DS_KNOWLEDGE => 60,
//	DS_HEURISTICS => 140,
//	DS_JUDGEMENT => 190,
//];
//
//$golemSpriteSet = [
//	SPRI_DEFAULT	=> $spr_golem,
//	SPRI_CORPSE		=> $spr_golem_corpse
//];
//
//$golem_behaviours = [
//	new dbhv_crippleOnHit(50, 80, 10),
//];