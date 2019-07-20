<?php

////////////////////////////////////////////////////////
//
// This file is just for declaring all items so that
// next time they undergo a big change, they'll all
// be in one place making the updating easier.
//
////////////////////////////////////////////////////////

//$eqp_torch = new eqp_torch('Flaming torch', 'Makes looking at stuff in the dark easier.', $spr_torch, 2, '#f22', 0.1);


//$spr_hazeSpecs = new Sprite([
//	4 => new SpriteElement(null, '#fff', '&#x221e;'),
//	5 => new SpriteElement(null, '#fff', '&#x00ac;')
//]);
//
//$eqp_hazeSpecs = new eqp_hat('Haze specs', 'Helps the wearer see more clearly', [$spr_hazeSpecs], null,
//	[],
//	[DS_EVASIVENESS => mt_rand(10, 30)],
//	[DMGDL_PLASMA => mt_rand(5, 10), DMGDL_VAPOUR => mt_rand(10, 15), DMG_FIRE => mt_rand(5, 10)],
//	[
//		new ebhv_illuminate(1, null, 0.3, false)
//	]
//	);
//
//$eqp_helm = new eqp_hat('Helm with reqs', 'Feels pretty sturdy.', eqp_hat::getSpriteSet('&#x0a72;', '#ddd'), null,
//	[DS_STRENGTH => 40, DS_FINESSE => 110],
//	null,
//	[DMGDL_CUT => 30, DMGDL_BLUNT => 5, DMG_COLD => 15, DMG_TRAUMA => 10]
//	);
//
//
//$spr_leatherBoots = new Sprite([
//		3 => new SpriteElement(null, '#753', '&#x255c;'),
//		5 => new SpriteElement(null, '#753', '&#x2559;'),
//	]);
//
//$eqp_leatherBoots = new eqp_boots('Leather Boots', 'Shiny boots of leather', [$spr_leatherBoots], null,
//	[],
//	[DS_AGILITY => mt_rand(10, 20)],
//	[DMGDL_CUT => mt_rand(10,20)]
//	);
//
//
//$spr_fancyBoots = new Sprite([
//	0 => new SpriteElement(null, '#a83', '&#x2584;'),
//	2 => new SpriteElement(null, '#a83', '&#x2584;'),
//	3 => new SpriteElement(null, '#ca6', '&#x255c;'),
//	5 => new SpriteElement(null, '#ca6', '&#x2559;'),
//	]);
//
//$eqp_fancyBoots = new eqp_boots('Fancy Boots', 'These are for the wealthy. Also water resistant!', [$spr_fancyBoots], null,
//	[],
//	[DS_AGILITY => mt_rand(20, 30), DS_EVASIVENESS => mt_rand(10, 20)],
//	[DMGDL_CUT => mt_rand(10,20), DMG_WATER => mt_rand(5, 10)]
//	);
//
//$eqp_fancyBoots->goldValue = 10;
//
//$spr_woodenSheild = new Sprite([4 => new SpriteElement(null, '#a96', ']')]);
//
//$eqp_woodenShield = new eqp_shield('Wooden Sheild', 'It\'s crap but it might stop a weak attack.', [$spr_woodenSheild], null,
//	[],
//	[DS_EVASIVENESS => -5, DS_FINESSE => -10],
//	[DMGDL_BLUNT => 20, DMGDL_CUT => 40, DMGDL_MISSILE => 30]
//	);
//
//$spr_steelSheild = new Sprite([4 => new SpriteElement(null, '#aaa', ')')]);
//
//$eqp_steelShield = new eqp_shield('Steel Sheild', 'Like the wooden sheild but not garbage!.', [$spr_steelSheild], null,
//	[],
//	[DS_EVASIVENESS => -5, DS_FINESSE => -5],
//	[DMGDL_BLUNT => 30, DMGDL_CUT => 60, DMGDL_MISSILE => 30]
//	);
//
//$spr_gauntlets = new Sprite([
//	0 => new SpriteElement(null, '#ddd', '&#x0428;'),
//	2 => new SpriteElement(null, '#ddd', '&#x0428;'),
//	3 => new SpriteElement(null, '#888', '&#x2580;'),
//	5 => new SpriteElement(null, '#888', '&#x2580;'),
//	]);
//
//$eqp_gauntlets = new eqp_gloves('Gauntlets', 'Time to get heavy handed.', [$spr_gauntlets], null,
//	[],
//	[DS_DEXTERITY => mt_rand(-15, -10), DS_FORCE => mt_rand(10, 20)],
//	[DMGDL_CUT => mt_rand(15,30), DMGDL_BLUNT => mt_rand(15, 30), DMGDL_POINT => mt_rand(15,30)]
//	);
//
//$spr_leatherGloves = new Sprite([
//	0 => new SpriteElement(null, '#a73', '&#x03c9;'),
//	2 => new SpriteElement(null, '#a73', '&#x03c9;'),
//	3 => new SpriteElement(null, '#a73', '&#x2580;'),
//	5 => new SpriteElement(null, '#a73', '&#x2580;'),
//	]);
//
//$eqp_leatherGloves = new eqp_gloves('Leather gloves', 'This\'ll come in handy.', $spr_leatherGloves, null,
//	[],
//	[DS_DEXTERITY => mt_rand(-5, 0), DS_FORCE => mt_rand(10, 20)],
//	[DMGDL_CUT => mt_rand(15,30), DMGDL_POINT => mt_rand(15,30), DMG_FIRE => mt_rand(5,15)]
//	);
//
//$spr_sunCoat = new Sprite([
//	0 => new SpriteElement(null, '#a00', '&#x2584;'),
//	1 => new SpriteElement(null, '#900', '&#x2584;'),
//	2 => new SpriteElement(null, '#800', '&#x2584;'),
//	3 => new SpriteElement(null, '#d70', '&#x2590;'),
//	4 => new SpriteElement('#900', '#f92', '&#x263c;'),
//	5 => new SpriteElement(null, '#b50', '&#x258c;'),
//	]);
//
//$eqp_sunCoat = new eqp_plateArmor('Sun Coat', 'Crafted by the Solsmiths.', [$spr_sunCoat], null,
//	[],
//	[DS_FINESSE => mt_rand(10, 20), DS_RESILIENCE => mt_rand(20, 25), DS_MAGIC => mt_rand(15, 25)],
//	[DMGDL_CUT => mt_rand(15,30), DMGDL_POINT => mt_rand(5,15), DMG_FIRE => mt_rand(60,80)],
//	[
//		new ebhv_chanceToDamageNearbyDudes(DMGDL_PLASMA, [DMG_FIRE => mt_rand(15, 20)], 3, 5, 5, 100, TRG_TAKE_HIT),
//		new ebhv_chanceToAmpDamage(mt_rand(30, 50), mt_rand(20, 30), DMG_FIRE)
//	]
//	);
//
//$spr_mailleCoat = new Sprite([
//	0 => new SpriteElement(null, '#888', '&#x2584;'),
//	1 => new SpriteElement(null, '#777', '&#x2584;'),
//	2 => new SpriteElement(null, '#666', '&#x2584;'),
//	3 => new SpriteElement(null, '#888', '&#x2590;'),
//	4 => new SpriteElement('#555', '#777', '&#x2593;'),
//	5 => new SpriteElement(null, '#666', '&#x258c;'),
//	]);
//
//$eqp_mailleCoat = new eqp_plateArmor('Maille Coat', 'Tightly linked and heavy maille with a bit of plating for luck.', [$spr_mailleCoat], null,
//	[],
//	[DS_RESILIENCE => mt_rand(10, 30)],
//	[DMGDL_CUT => mt_rand(30,45), DMGDL_POINT => mt_rand(15,20), DMGDL_PLASMA => mt_rand(-10,-5)],
//	[
//		new ebhv_chanceToDamageNearbyDudes(DMGDL_PLASMA, [DMG_FIRE => mt_rand(15, 20)], 3, 5, 5, 20, TRG_TAKE_HIT),
//		new ebhv_chanceToAmpDamage(mt_rand(30, 50), mt_rand(20, 30), DMG_FIRE)
//	]
//	);
//
//$spr_leatherBelt = new Sprite([
//	3 => new SpriteElement('#975', '#000', '&#x0387;'),
//	4 => new SpriteElement('#864', '#ddd', 'E'),
//	5 => new SpriteElement('#642', '#000', '&#x0387;'),
//	]);
//
//$eqp_leatherBelt = new eqp_belt('Leather Belt', 'Hand made by some guy\'s grandfather.', [$spr_leatherBelt], null,
//	[],
//	[DS_STRENGTH => mt_rand(5, 10), DS_RESILIENCE => mt_rand(5, 10), DS_DISCIPLINE => mt_rand(10, 20)],
//	[DMGDL_CUT => mt_rand(5,10), DMGDL_POINT => mt_rand(5,10)]
//	);
//
//$spr_studdedBelt = new Sprite([
//	3 => new SpriteElement('#333', '#fff', ':'),
//	4 => new SpriteElement('#222', '#ddd', 'E'),
//	5 => new SpriteElement('#111', '#ddd', ':'),
//	]);
//
//$eqp_studdedBelt = new eqp_belt('Studded Belt', 'Should keep the blades out of your waiste.', [$spr_studdedBelt], null,
//	[],
//	[DS_STRENGTH => mt_rand(5, 10), DS_RESILIENCE => mt_rand(5, 10), DS_DISCIPLINE => mt_rand(15, 25)],
//	[DMGDL_CUT => mt_rand(15,25), DMGDL_POINT => mt_rand(10,20)],
//	[
//		new ebhv_chanceToAmpDamage(50, 20)
//	]
//	);
//
//$spr_greenCloak = new Sprite([
//	0 => new SpriteElement(null, '#0d0', '&#x2584;'),
//	1 => new SpriteElement('#0b0', '#000', '&#x25b2;'),
//	2 => new SpriteElement(null, '#0b0', '&#x2584;'),
//	3 => new SpriteElement('#060', null, ' '),
//	4 => new SpriteElement('#080', '#b96', 'X'),
//	5 => new SpriteElement('#0a0', null, ' '),
//	]);
//
//$eqp_greenCloak = new eqp_cloak('Woodland Cloak', 'A cloak often worn by the people of the Noth Wood.', [$spr_greenCloak], null,
//	[],
//	[DS_EVASIVENESS => mt_rand(20, 30), DS_FOCUS => mt_rand(10, 20), DS_DISCIPLINE => mt_rand(10, 20), DS_LUCK => mt_rand(5, 10)],
//	[DMG_COLD => mt_rand(20,30), DMG_INFECTION => mt_rand(10,20)]
//	);
//
//$spr_vampCloak = new Sprite([
//	0 => new SpriteElement(null, '#444', '&#x2584;'),
//	1 => new SpriteElement('#444', '#000', '&#x25b2;'),
//	2 => new SpriteElement(null, '#444', '&#x2584;'),
//	3 => new SpriteElement('#600', null, ' '),
//	4 => new SpriteElement('#800', '#333', 'X'),
//	5 => new SpriteElement('#a00', null, ' '),
//	]);
//
//$eqp_vampCloak = new eqp_cloak('Vampire Cloak', 'Full of weird, fucked up magic.', [$spr_vampCloak], null,
//	[],
//	[DS_EVASIVENESS => mt_rand(30, 40), DS_AGILITY => mt_rand(20, 35), DS_CONTROL => mt_rand(10, 20), DS_LUCK => mt_rand(-15, -10)],
//	[DMG_COLD => mt_rand(20,30), DMG_TRAUMA => mt_rand(10,15), DMG_FIRE => mt_rand(10,20)],
//	[
//		new ebhv_stealLife(10)
//	]
//	);
//
//$spr_chainFauld = new Sprite([
//	0 => new SpriteElement(null, '#975', '&#x2584;'),
//	1 => new SpriteElement(null, '#864', '&#x2584;'),
//	2 => new SpriteElement(null, '#753', '&#x2584;'),
//	3 => new SpriteElement('#555', '#888', '&#x2593;'),
//	4 => new SpriteElement('#333', '#666', '&#x2593;'),
//	5 => new SpriteElement('#111', '#444', '&#x2593;'),
//	]);
//
//$eqp_chainFauld = new eqp_fauld('Chain Fauld', 'Well crafted chain maille hanging from a fine leather waisteband.', [$spr_chainFauld], null,
//	[],
//	[DS_EVASIVENESS => mt_rand(-10, -5), DS_AGILITY => mt_rand(-10, -5), DS_CONTROL => mt_rand(10, 15)],
//	[DMGDL_CUT => mt_rand(30,40), DMGDL_POINT => mt_rand(15,25)],
//	[
//		new ebhv_frustration(10),
//	]
//	);
//
//$spr_apron = new Sprite([
//	0 => new SpriteElement(null, '#b42', '&#x2590;'),
//	1 => new SpriteElement(null, '#a31', '&#x2584;'),
//	2 => new SpriteElement(null, '#920', '&#x258c;'),
//	3 => new SpriteElement('#b42', null, ' '),
//	4 => new SpriteElement('#a31', null, ' '),
//	5 => new SpriteElement('#920', null, ' '),
//	]);
//
//$eqp_smithApron = new eqp_fauld('Blacksmith\'s Apron', 'A well worn apron, spent many years by the fires of a smiths\' furnace.', [$spr_apron], null,
//	[],
//	[DS_STRENGTH => mt_rand(10, 15), DS_FORCE => mt_rand(15, 25), DS_RESILIENCE => mt_rand(20, 30)],
//	[DMGDL_CUT => mt_rand(10, 15), DMGDL_PLASMA => mt_rand(20, 30), DMG_FIRE => mt_rand(30, 50)]
//	);
//
//$spr_spikedPauldrons = new Sprite([
//	0 => new SpriteElement(null, '#666', '&#x25ba;'),
//	2 => new SpriteElement(null, '#666', '&#x25c4;'),
//	]);
//
//$eqp_spikedPauldrons = new eqp_pauldrons('Spiked Pauldrons', 'Maybe the worst sprite yet!', [$spr_spikedPauldrons], null,
//	[],
//	[DS_STRENGTH => mt_rand(10, 15), DS_RESILIENCE => mt_rand(20, 30)],
//	[DMGDL_CUT => mt_rand(10, 15), DMGDL_BLUNT => mt_rand(15, 20), DMGDL_POINT => mt_rand(10, 15)]
//	);

// Still to go:

// Pants

// Cuffs

// Ring

// Amulet

// Bangle