<?php

/**
 * GLOSSARY:
 *
 * UIN		- User INteration
 * DIR		- DIRection
 * TPL		- Tile PLane
 * QS		- Quest Status
 * GND		- GeNDer
 * DMGDL	- DaMaGe DeLivery
 * DMG		- DaMaGe
 * DS		- Dude Stat
 * TEQT		- TEchniQue Type
 * TEQ		- TEchniQue
 * SPRI		- SPRIte Index
 * UPD		- UPDate index
 * SKLS		- SKiLl Slot
 * DRCT		- client DiReCTive (not used atm)
 * EQP		- EQuiPment type
 * TFI		- TransFormation Inventory type
 * SPSI		- SPeech SItuation
 * SEI		- Sprite Element Index
 * BHVK		- BeHaViour Key
 * TRG		- behaviour TRiGger
 * ICAT		- Item CATegory
 * FAC		- FACtion
 * INV		- sub-INVentory type
 * EOI		- Event Of Interst (to the quest checker)
 * WP		- Waypoint
 */

// Version
const VERSION = '0.2 &#x03b1;';

// URLs
const URL_GAME	= 'play.php';
const URL_INTRO	= 'index.php';
const URL_MAP	= 'minimap.php';

// Character dimensions
const CHAR_HEIGHT	= 13;
const CHAR_WIDTH	= 8;

// Line break character
const LINE_BREAK	= "\n";

// Width of side-wings
const WING_WIDTH	= 40;

// Fastest possible cooldown (important for controlling poll rate)
const MIN_COOLDOWN	= 0.2;

// Lowest possible durability for an item
const MIN_DURABILITY = 5;

// UI motification types
const UIN_CLICK					= 100;
const UIN_RIGHT_CLICK			= 200;

const UIN_CTRL_CLICK			= 110;
const UIN_CTRL_RIGHT_CLICK		= 210;

const UIN_ALT_CLICK				= 101;
const UIN_ALT_RIGHT_CLICK		= 201;

const UIN_CTRL_ALT_CLICK		= 111;
const UIN_CTRL_ALT_RIGHT_CLICK	= 211;

const UIN_TEXT					= 300;

// Ready time correction (keep an eye on this; it should be the minimum effective dose)
const READY_TIME_CORRECTION	= 0.001;

// View parameters
const DEFAULT_VIEW_SIZE		= 21;
const MAX_VIEW_SIZE			= 25;
const MIN_VIEW_SIZE			= 5;
const ACTION_AREA_RADIUS	= 15;

// Directions
const DIR_NORTH	= 111;
const DIR_SOUTH	= 222;
const DIR_WEST	= 333;
const DIR_EAST	= 444;

// Tile planes
const TPL_OPENGROUND	= 1;	// Player can move over this
const TPL_VERTICAL		= 2;	// Vertical faces (e.g., cliff faces, walls)
const TPL_LOWOBSTACLE	= 3;	// Something under the ground layer that can't be traversed (e.g., water, void)
const TPL_HIGHOBSTACLE	= 4;	// Something above the ground that can't be traversed (e.g., rocks, trees)
const TPL_WALL			= 5;	// Something that notching can traverse (e.g., inside the walls of caves, outside the walls in building interiors)
const TPL_LADDER		= 6;	// A ladder or something as easy to climb as a ladder. Players and humanoids can climb this by default.
const TPL_STAIRS		= 7;	// Stairs. Player and dudes can handle by default.

// Quest statuses
const QS_NOT_STARTED	= 0;
const QS_IN_PROGRESS	= 1;
const QS_COMPLETE		= 2;
const QS_FAILED			= 3;
const QS_ABANDONED		= 4;

// Headers
const HEADER_TILES			= 't';
const HEADER_SPRITES		= 's';
const HEADER_VIEW_HEIGHT	= 'vh';
const HEADER_VIEW_WIDTH		= 'vw';
const HEADER_NEXTFRAME		= 'n';

// Damage deliveries
const DMGDL_BLUNT	= 1000;
const DMGDL_POINT	= 1001;
const DMGDL_CUT		= 1002;
const DMGDL_MISSILE	= 1003;
const DMGDL_LIQUID	= 1004;
const DMGDL_VAPOUR	= 1005;
const DMGDL_PLASMA	= 1006;

// Damage types
const DMG_TRAUMA	= 1100;
const DMG_FIRE		= 1101;
const DMG_COLD		= 1102;
const DMG_ELECTRIC	= 1103;
const DMG_WATER		= 1104;
const DMG_POISON	= 1105;
const DMG_INFECTION	= 1106;

// Attack config types
const TEQT_MELEE		= 2001		;
const TEQT_RANGED		= 2002		;
const TEQT_MAGIC		= 2003		;

// Attack config channels
const TEQ_DAMAGE		= 2100		;
const TEQ_HIT_CHANCE		= 2101		;
const TEQ_CRIT_DAMAGE	= 2102		;
const TEQ_CRIT_CHANCE	= 2103		;
const TEQ_DEFENCE		= 2104		;
const TEQ_DODGE_CHANCE	= 2105		;

// Experimental TEQs
const TEQ_ATTACK_SPEED	= 2106		;
const TEQ_CONSISTENCY	= 2107		;

// Genders
const GND_MALE		= 0;
const GND_FEMALE	= 1;

// Dude Stats
	// Hidden
const DS_HANDICAP		= -1	;	// Applies a handicap to all stats with unaltered defaults
const DS_RANDOMISER		= -2	;	// Randomises all stats by this percentage

const DS_HP_MAX			= 2		;	// Max life
const DS_REGENERATION	= 3		;	// Life regenerated per second
const DS_EP_MAX			= 4		;	// Max energy
const DS_RECHARGE		= 5		;	// Energy regenerated per second

const DS_LUCK			= 7		;	// Luck

const DS_EXPERIENCE		= 10	;	// Experience

const DS_ATTACKSPEED	= 53	;	// Attack interval

const DS_SPEED			= 50	;	// Movement speed
const DS_SPEED_FAST		= 51	;	// Movement speed (fast e.g., for chasing)

const DS_DAMAGE			= TEQ_DAMAGE		;	// Final multiplier of damage
const DS_HIT_CHANCE		= TEQ_HIT_CHANCE	;	// Final multiplier of chance to hit
const DS_CRIT_DAMAGE	= TEQ_CRIT_DAMAGE	;	// Final multiplier of crit damage
const DS_CRIT_CHANCE	= TEQ_CRIT_CHANCE	;	// Final multiplier of crit chance
const DS_DEFENCE		= TEQ_DEFENCE		;	// Final multiplier of defence
const DS_DODGE_CHANCE	= TEQ_DODGE_CHANCE	;	// Final multiplier of dodge chance

const DS_ATTACK_SPEED	= TEQ_ATTACK_SPEED	;	// Final multiplier of attack speed
const DS_CONSISTENCY	= TEQ_CONSISTENCY	;	// Final multiplier of consitency

const DS_ENERGYUSE		= 60		;	// Final multiplier of energy use

// These will hold percentages and those percentages will be added to the pool when damage of that type is calculated.
const DS_DMGDL_BLUNT	= DMGDL_BLUNT	;
const DS_DMGDL_POINT	= DMGDL_POINT	;
const DS_DMGDL_CUT		= DMGDL_CUT		;
const DS_DMGDL_MISSILE	= DMGDL_MISSILE	;
const DS_DMGDL_LIQUID	= DMGDL_LIQUID	;
const DS_DMGDL_VAPOUR	= DMGDL_VAPOUR	;
const DS_DMGDL_PLASMA	= DMGDL_PLASMA	;

const DS_DMG_TRAUMA		= DMG_TRAUMA	;
const DS_DMG_FIRE		= DMG_FIRE		;
const DS_DMG_COLD		= DMG_COLD		;
const DS_DMG_ELECTRIC	= DMG_ELECTRIC	;
const DS_DMG_WATER		= DMG_WATER		;
const DS_DMG_POISON		= DMG_POISON	;
const DS_DMG_INFECTION	= DMG_INFECTION	;

	// Exposed
		// Strength
const DS_STRENGTH		= 100	;	// Base strength stat
const DS_FORCE			= 101	;	// Increases damage of melee damage
const DS_RESILIENCE		= 102	;	// Decreases incoming physical damage
const DS_CONTROL		= 103	;	// Reduces random aspects of physical activities

const DS_INERTIA		= 150	;	// Makes for long swings. May affect chance to hit or crit chance
const DS_RECOVERY		= 151	;	// Increases effect of healing actions

		// Agility
const DS_AGILITY		= 200	;	// Movement stuff
const DS_DEXTERITY		= 201	;	// Hit chance
const DS_EVASIVENESS	= 202	;	// Dodge chance
const DS_FINESSE		= 203	;	// Increases the positive aspects of agility (increases reputation?)

const DS_BALANCE		= 250	;	// Affects dodging and reduces knock-back, may stop a dodge from delaying next attack
const DS_REACH			= 251	;	// Affects dodging and reduces knock-back, may stop a dodge from delaying next attack

		// Magic
const DS_MAGIC			= 300	;	// Base Magic stat
const DS_DISRUPTION		= 301	;	// Power of the magic you cast
const DS_DISCIPLINE		= 302	;	// Reduces the strong random aspects of the magic you cast
const DS_FOCUS			= 303	;	// Increases general strength of magic effects

const DS_INSANITY		= 350	;	// Greatly increases power of magic, moderately increases random aspect of magic.

		// Social
const DS_CHARISMA		= 400	;	// Affects how people treat you
const DS_REPUTATION		= 401	;	// How impressed people are by you
const DS_NOTORIETY		= 402	;	// How afaid people are of you
const DS_FAME			= 403	;	// Odds of meeting someone who has heard of you

const DS_DISCOUNT		= 450	;	// Discounts when buying goods
const DS_BARGAINING		= 451	;	// Kinda like a "trading crit", a chance to recieve something for a substantial discount.
const DS_LEADERSHIP		= 452	;	// Affects effectiveness of non-summoned minions.

		// Intellect
const DS_INTELLECT		= 500	;	// Base intelligence stat
const DS_TENACITY		= 501	;	// Persistence, is admirable to others (increases reputation?)
const DS_KNOWLEDGE		= 502	;	// Unlocks additional details on general stuff
const DS_HEURISTICS		= 503	;	// Gives "gut-feelings", can be used to strengthen various effects of intellect (may work like an intel-crit)

const DS_JUDGEMENT		= 550	;	// May help with stuff like accuracy at a large distance
const DS_PRAXIS			= 551	;	// Increases experience gathering



// Sprite indexes
const SPRI_DEFAULT			= 0;
const SPRI_CORPSE			= 1;
const SPRI_MALE				= 2;
const SPRI_FEMALE			= 3;
const SPRI_MALE_CORPSE		= 4;
const SPRI_FEMALE_CORPSE	= 5;
const SPRI_ACTIVE			= 6;
const SPRI_INACTIVE			= 7;
const SPRI_OPEN				= 8;
const SPRI_CLOSED			= 9;
const SPRI_OVERSPRITE		= 10;
const SPRI_GEAR				= 11;
const SPRI_NORTH			= DIR_NORTH;	// 111
const SPRI_SOUTH			= DIR_SOUTH;	// 222
const SPRI_WEST				= DIR_WEST;		// 333
const SPRI_EAST				= DIR_EAST;		// 444

// AsObject layers
const LAYER_PLAYER		= 9999;
const LAYER_DOOR_CLOSED	= 9000;
const LAYER_DUDE		= 8000;
const LAYER_PROJECTILE	= 7000;
const LAYER_PUSHBLOCK	= 6000;
const LAYER_PORTAL		= 5000;
const LAYER_CHECKPOINT	= 4500;
const LAYER_SIGN		= 4000;
const LAYER_CHEST		= 3000;
const LAYER_COLLECTIBLE	= 2000;
const LAYER_DOOR_OPEN	= 1000;

const LAYER_EDITOR_MARK		= 100000;
const LAYER_EDITOR_PLAYER	= 200000;

// Update keys
// Update keys in capital letters are deployed first followed by update keys in lowercase.
const UPD_OVERLAY		= 'OL'	;
const UPD_JVS_KEYS		= 'JVK'	;
const UPD_PLAYER_SPRITE	= 'PS'	;
const UPD_SPRITE		= 'PE'	;

const UPD_REFRESHRATE	= 'rr'	;
const UPD_STATS			= 'st'	;
const UPD_PLAYER_STATUS	= 'pst'	;
const UPD_HP			= 'hp'	;
const UPD_EP			= 'ep'	;
const UPD_XP			= 'xp'	;
const UPD_BOONS			= 'bn'	;
const UPD_PLAYER_INFO	= 'inf'	;
const UPD_CONVERSATION	= 'cnv'	;
const UPD_COMMS			= 'com'	;
const UPD_RESPONSES		= 'rsp'	; // NYI
const UPD_ITEMS			= 'itm'	;
const UPD_MONEY			= 'mon'	;
const UPD_STATUS		= 'tus'	;
const UPD_TEXT			= 'txt'	;
const UPD_AVAILABLE		= 'avl'	;
const UPD_COMBAT		= 'cmb'	;
const UPD_ITEM_INFO		= 'itn'	;
const UPD_DMG_DEF		= 'dd'	;
const UPD_OPPONENT		= 'opp'	;
const UPD_QUESTS		= 'qst'	;
const UPD_QUESTS_C		= 'qcm'	;
const UPD_TASKS			= 'tsk'	;
const UPD_SKILLS		= 'skl'	;
const UPD_PASSIVES		= 'psv'	;
const UPD_SKILL_INFO	= 'ski'	;
const UPD_PASSIVE_INFO	= 'psi'	;
const UPD_BINDINGS		= 'bnd'	;
const UPD_TECHNIQUE		= 'teq'	;
const UPD_SOUNDS		= 'snd'	;
const UPD_INTERACTIONS	= 'int'	;

const UPD_EDITOR_SKILLS		= 'edsk';
const UPD_EDITOR_CLIPBOARD	= 'eclp';

// Skill slots
const SKLS_KEY_0		= 0;
const SKLS_KEY_1		= 1;
const SKLS_KEY_2		= 2;
const SKLS_KEY_3		= 3;
const SKLS_KEY_4		= 4;
const SKLS_KEY_5		= 5;
const SKLS_KEY_6		= 6;
const SKLS_KEY_7		= 7;
const SKLS_KEY_8		= 8;
const SKLS_KEY_9		= 9;
const SKLS_CLICK		= UIN_CLICK;
const SKLS_RIGHT_CLICK	= UIN_RIGHT_CLICK;

// Client directives
const DRCT_RELOAD		= 0;
const DRCT_RFSH_REND	= 1;

// Special case update bodies
const UPDB_CLEAR		= 'CLEAR';

// Equipment slots
const EQP_HEAD		= 'Head'		;
const EQP_CHEST		= 'Chest'		;
const EQP_BELT		= 'Belt'		;
const EQP_GLOVES	= 'Gloves'		;
const EQP_PANTS		= 'Pants'		;
const EQP_BOOTS		= 'Boots'		;
const EQP_CLOAK		= 'Cloak'		;
const EQP_SHOULDERS	= 'Shoulders'	;
const EQP_FAULD		= 'Fauld'		;
const EQP_WRISTS	= 'Wrists'		;

const EQP_NECKLACE	= 'Necklace'	;
const EQP_RING		= 'Ring'		;
const EQP_BANGLE	= 'Bangle'		;

const EQP_BANNER	= 'Banner'		;

const EQP_HAND		= 'Hand'		;
const EQP_OFFHAND	= 'Off-hand'	;

// Transformation types
const TFI_FIRE		= 1		;
const TFI_WATER		= 2		;
const TFI_FURNACE	= 3		;
const TFI_CRUSHER	= 4		;

// Speech Delay
const SPEECH_DELAY		= 1		;

// Speech Situations
const SPSI_GREETING		= 1		;
const SPSI_CONVERSING	= 2		;
const SPSI_SAYING_BYE	= 3		;

const SPSI_SELLING		= 10	;
const SPSI_BUYING		= 11	;
const SPSI_SELLING_NE	= 12	;

const SPSI_GIVING		= 13	;
const SPSI_TAKING		= 14	;

const SPSI_EXCHANGING	= 15	;

const SPSI_REPAIRING	= 16	;
const SPSI_REPAIRING_NE	= 17	;

const SPSI_FOLLOWING	= 20	;
const SPSI_WAITING		= 21	;

const SPSI_ATTACKING	= 30	;
const SPSI_MISSING		= 31	;
const SPSI_STRIKING		= 32	;
const SPSI_KILLING		= 33	;

const SPSI_DEFENDING	= 34	;
const SPSI_DEFLECTING	= 35	;
const SPSI_TAKING_HIT	= 36	;
const SPSI_DYING		= 37	;

const SPSI_ERROR		= -1	;


// Sprite Element Indexes
const SEI_TOP_L		= 0;
const SEI_TOP_M		= 1;
const SEI_TOP_R		= 2;
const SEI_BOTTOM_L	= 3;
const SEI_BOTTOM_M	= 4;
const SEI_BOTTOM_R	= 5;

// Behaviour keys
const BHVK_MOVEMENT		= 'mov'		;
const BHVK_SPEAK		= 'spk'		;
const BHVK_SHOWTEXT		= 'stx'		;
const BHVK_TELEPORT		= 'tlp'		;
const BHVK_TELEPORT_MOD	= 'tlpm'	;
const BHVK_PRIMARY		= 'prm'		;
const BHVK_PUSHABLE		= 'psh'		;
const BHVK_TEXT			= 'txt'		;
const BHVK_OVERLAY		= 'ovl'		;
const BHVK_TRANSACTION	= 'tns'		;
const BHVK_DAMAGE		= 'dmg'		;
const BHVK_DEATH		= 'dth'		;
const BHVK_CORPSE		= 'cps'		;

// Behaviour triggers
const TRG_ATTACK		= 'onAttack'	;
const TRG_MISS			= 'onMiss'		;
const TRG_STRIKE		= 'onStrike'	;
const TRG_KILL			= 'onKill'		;
const TRG_DEFEND		= 'onDefend'	;
const TRG_DEFLECT		= 'onDeflect'	;
const TRG_TAKE_HIT		= 'onTakeHit'	;
const TRG_DEATH			= 'onDeath'		;

const TRG_EQUIP			= 'onEquip'		;
const TRG_UNEQUIP		= 'onUnequip'	;
const TRG_MAP_CHANGE	= 'onMapChange'	;

const TRG_COLLECT		= 'onCollect'	;
const TRG_DROP			= 'onDrop'		;
const TRG_USE			= 'onUse'		;

const TRG_IDLE			= 'onIdle'		;
const TRG_COLLISION		= 'onCollision'	;
const TRG_REACTION		= 'onReaction'	;
const TRG_ENGAGE		= 'onEngage'	;
const TRG_DISENGAGE		= 'onDisengage'	;

const TRG_GAIN_ITEM		= 'onGainItem'	;
const TRG_LOSE_ITEM		= 'onLoseItem'	;

// Item Categories
const ICAT_MATERIAL		= 'Material';
const ICAT_KEY			= 'Key';
const ICAT_TEXT			= 'Text';
const ICAT_CONSUMABLE	= 'Consumable';
const ICAT_REMEDY		= 'Remedial';

const ICAT_EQUIP		= 'Equippable';
	const ICAT_WEAPON		= 'Weapon';
		const ICAT_SWORD		= 'Sword';
		const ICAT_AXE			= 'Axe';
		const ICAT_MACE			= 'Mace';
		const ICAT_HAMMER		= 'Hammer';
		const ICAT_POLEARM		= 'Polearm';
		const ICAT_SPEAR		= 'Spear';

	const ICAT_TOOL			= 'Tool';
		const ICAT_TORCH		= 'Torch';

	const ICAT_ARMOR		= 'Armor';
		const ICAT_HELMET		= 'Helmet';
		const ICAT_CHEST_ARMOR	= 'Chest Armour';

	const ICAT_CLOTHING		= 'Clothing';
		const ICAT_HAT			= 'Hat';
		const ICAT_JACKET		= 'Jacket';


// Factions
const FAC_PLAYER		= 0;
const FAC_ANIMAL		= 1;
const FAC_MONSTER		= 2;
const FAC_NPC_NEUTRAL	= 3;

// Inventory subtypes
const INV_QUEST			= 'Quest';
const INV_MATERIALS		= 'Materials';
const INV_POTIONS		= 'Potions';
const INV_KEYS			= 'Keys';
const INV_BANNERS		= 'Banners';

// Events of interest (used for quest triggers)
const EOI_INVENTORY		= 0;	// Change of inventory contents
const EOI_WALLET		= 1;	// Change of wallet contents
const EOI_ATTACK		= 2;	// Happens at the very end of an attack.		Send the attack
const EOI_ENGAGE_NPC	= 3;	// Happens when you chat to an NPC.				Send the NPC

// Maximum random integer used for calculating random things
define('RAND_MAX', getrandmax());

const EDITOR_FILL_MARKER	= 'X';	// EDITOR LINE

const WP_GRUB_SOUTH_COAST		= 0;
const WP_GRUB_TOWN				= 1;
const WP_EFFENDON_GROVE			= 2;