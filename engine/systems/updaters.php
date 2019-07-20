<?php

function update($UPD, $update)
{
	$updatePreview = htmlspecialchars(json_encode($update, JSON_PRETTY_PRINT));		//XXX
	console_echo("Update sent: {$UPD} - {$updatePreview}", '#aaa', CNS_UPDATES);
	console_echo(">" . (strlen($updatePreview) / 1000) . ' kb', '#fff', CNS_UPDATES);

	// If we have no updates, easy; add the update.
	if (empty($_SESSION['updates'][$UPD]))
	{
		$_SESSION['updates'][$UPD] = $update;
	}
	else // If there is already an update there, less easy; decide how to handle multiple updates in a single transmission.
	{
		switch ($UPD)
		{
			case UPD_STATUS:
				$_SESSION['updates'][$UPD] = $update + $_SESSION['updates'][$UPD];
				break;

			case UPD_CONVERSATION:
				$_SESSION['updates'][$UPD]->lines = array_merge($_SESSION['updates'][$UPD]->lines, $update->lines);
				break;

			case UPD_COMMS:
				$_SESSION['updates'][$UPD] = array_merge($_SESSION['updates'][$UPD], $update);
				break;

			case UPD_SPRITE:
				if (is_array($_SESSION['updates'][$UPD])) $_SESSION['updates'][$UPD][] = $update;
				else $_SESSION['updates'][$UPD] = [$_SESSION['updates'][$UPD], $update];
				break;

			case UPD_COMBAT:
				$_SESSION['updates'][$UPD]->events = array_merge($_SESSION['updates'][$UPD]->events, $update->events);
				break;

			case UPD_PLAYER_STATUS:
				$_SESSION['updates'][$UPD] = array_merge($_SESSION['updates'][$UPD], $update);
				break;

			case UPD_STATS:
				$_SESSION['updates'][$UPD]->shallow = $update->shallow + $_SESSION['updates'][$UPD]->shallow;
				$_SESSION['updates'][$UPD]->deep = $update->deep + $_SESSION['updates'][$UPD]->deep;
				break;

			case UPD_TASKS:
				$_SESSION['updates'][$UPD]->tasks = array_merge($update->tasks, $_SESSION['updates'][$UPD]->tasks);
				break;

			case UPD_ITEMS:
				$_SESSION['updates'][$UPD]->items = array_merge($update->items, $_SESSION['updates'][$UPD]->items);
				break;

			case UPD_SOUNDS:
				$_SESSION['updates'][$UPD]= array_merge($update, $_SESSION['updates'][$UPD]);
				break;

			case UPD_INTERACTIONS:
				if (!is_array($_SESSION['updates'][$UPD])) $_SESSION['updates'][$UPD] = [$_SESSION['updates'][$UPD]];
				$_SESSION['updates'][$UPD][] = $update;
				break;
			default:
				$_SESSION['updates'][$UPD] = $update;
		}
	}
}

function clearPanel($UPD)
{
	$_SESSION['updates'][$UPD] = UPDB_CLEAR;
	console_echo("Update sent: {$UPD} - CLEAR", '#caa');	//XXX
}


function update_text($header, $text, $bg, $fg, $height = null, $width = null)
{
	$update = new stdClass();

	if (is_string($text) && strtolower(substr($text, -4)) == '.tex')
	{
		include "{$GLOBALS['rootPath']}content/text/$text";
	}

	$update->commType = 'text';
	$update->header = $header;
	$update->body = is_array($text) ? $text : [$text];
	$update->bg = $bg;
	$update->fg = $fg;
	$update->height = $height;
	$update->width = isset($width) ? $width : 20;

	update(UPD_COMMS, [$update]);
}


function update_conversation($name, $body, $colour)
{

	$item = new stdClass();

	$item->name = $name;
	$item->commType = 'speech';
	$item->body = $body;
	$item->nameColour = $colour;

	update(UPD_COMMS, [$item]);
}


function update_money()
{
	global $player;
	global $currencies;

	$update = new stdClass();

	$update->currencies = [];

	foreach ($player->wallet->contents as $CUR => $amount)
	{
		$currency = new stdClass();

		$currency->symbol	= $currencies[$CUR]->symbol;
		$currency->name		= $currencies[$CUR]->name;
//		$currency->amount	= number_format($amount, 2);
		$currency->amount	= number_format($amount);

		$update->currencies[] = $currency;
	}

	update(UPD_MONEY, $update);
}


function update_thoughts($thought)
{
	global $player;

	$player->speak($thought);
}

function update_sprite($spriteKey, Sprite $overSprite, $augment = true)
{
	$update = new stdClass();
	$update->key = $spriteKey;
	$update->sprite = $overSprite->getJsonObject();
	$update->augment = $augment;
	update(UPD_SPRITE, $update);
}


function update_items(Item $item = null)
{
	global $player;

	$player->handleEventOfInterest(EOI_INVENTORY);

	$update = new stdClass();

	console_echo('Items have prices? ' . ($player->showItemPrices ? 'true' : 'false'));

	if ($item)
	{
		$update->items = [$item->getAjaxObject()];
	}
	else
	{
		$update->refresh = true;
		$update->items = $player->inventory->getAjaxObjects();
	}

	update(UPD_ITEMS, $update);
}

function update_title($text)
{
	update('title', $text);
}


function update_available(Item $item = null)
{
	global $player;

	$update = new stdClass();

	if (isset($player->engagement->inventory))
	{

		if ($item)
		{
			$update->items = [$item->getAjaxObject()];
		}
		else
		{
			$update->refresh = true;
			$update->items = $player->engagement->inventory->getAjaxObjects();
		}

		$update->header = $player->engagement->name;
		$update->isEmpty = !(count($update->items) > 0);

		update(UPD_AVAILABLE, $update);
	}
}

//function update_itemInfo($string, Sprite $sprite = null)
function update_itemInfo(Item $item)
{
	global $player;
	global $view;

	global $DS_names;

	$update = new stdClass();

	$update->name = $item->name;
	$update->sprite = $view->addClientSprite($item->sprite)->key;
	$update->description = $item->description;
	$update->id = $item->id;

	if ($item->level) $update->level = $item->level;

	if (isset($item->durability))
	{
		$update->dur = $item->durability;
		$update->durMax = $item->durabilityMax;
	}

	$update->behaviours = [];
	foreach ($item->behaviours as $behaviours)
	{
		foreach ($behaviours as $behaviour)
		{
			$update->behaviours[] = $behaviour->description;
		}
	}

	if ($item instanceof Equipment)
	{
		$existingItem = $player->getItemByEQP($item->EQP);

		$update->EQP = $item->EQP;

		if (isset($item->DSs))
		{
			$update->DSs = $item->DSs;

			foreach ($update->DSs as $DS => &$val)
			{
				switch ($DS) // Switch for handling special cases
				{
					case DS_LUCK:
						$val = $val > 0 ? 'lucky' : 'unlucky';
						continue;
				}

				if ($existingItem && isset($existingItem->DSs[$DS]) && $val != $existingItem->DSs[$DS])
				{
					$val = ($existingItem->DSs[$DS] < $val ? '{g}' : '{r}') . $val;
				}
				else
				{
					$val = "{w}{$val}";
				}
			}
		}
		if (isset($item->DSs_req))
		{
			$update->DSs_req = $item->DSs_req;

			foreach ($update->DSs_req as $DS => &$val)
			{
				$val = ($player->$DS_names[$DS] >= $val ? '{g}' : '{r}') . $val;
			}
		}

		if (isset($item->DMGs))
		{
			$update->DMGs = $item->DMGs;

			foreach ($update->DMGs as $DMG => &$val)
			{
				if ($existingItem && isset($existingItem->DMGs[$DMG]) && $val != $existingItem->DMGs[$DMG])
				{
					$val = ($existingItem->DMGs[$DMG] < $val ? '{g}' : '{r}') . $val;
				}
				else
				{
					$val = "{w}{$val}";
				}
			}
		}
		if (isset($item->DMGs_def))
		{
			$update->DMGs_def = $item->DMGs_def;

			foreach ($update->DMGs_def as $DMG => &$val)
			{
				if ($existingItem && isset($existingItem->DMGs_def[$DMG]) && $val != $existingItem->DMGs_def[$DMG])
				{
					$val = ($existingItem->DMGs_def[$DMG] < $val ? '{g}' : '{r}') . $val;
				}
				else
				{
					$val = "{w}{$val}";
				}
			}
		}

		if (isset($item->DMGDL)) $update->DMGDL = $item->DMGDL;
	}

	update(UPD_ITEM_INFO, $update);
}

function update_overlay($colour, $opacity)
{
	$update = new stdClass();

	$update->colour = $colour;
	$update->opacity = $opacity;

	update(UPD_OVERLAY, $update);
}

function update_jvsKeys()
{
	global $map;

	$update = ['tileKey' => $map->tileKeyJson, 'spriteKey' => $map->spriteKeyJson, 'css' => $map->css, 'mapName' => $map->MAP];

	update(UPD_JVS_KEYS, $update);
}

/**
 * Updates the combat panel. The body string is parsed using function col. Adhere to the following standards when sending information here:
 *
 * #fff - Player and enemy names
 * #f00 - damage
 * #0f0 - healing
 * #00f - skills
 *
 * @param type $body - This string will be parsed with funtion col.
 */
function update_combat($body)
{
	$update = new stdClass();
	$update->events = [];

	$event = new stdClass();
	$event->body = col("{$body}");
	$update->events[] = $event;

	update(UPD_COMBAT, $update);
}

function update_playerHp($value = null)
{
	global $player;

	$update = new stdClass();
	$update->hpMax = $player->hp_max;
	$update->hp = isset($value) ? $value : $player->hp;
	$update->hp = round($update->hp, 1);

	update(UPD_HP, $update);
}

function update_playerEp($value = null)
{
	global $player;

	$update = new stdClass();
	$update->epMax = $player->ep_max;
	$update->ep = isset($value) ? $value : $player->ep;
	$update->ep = round($update->ep, 1);

	update(UPD_EP, $update);
}

function update_playerXp($value = null, $lvl = null)
{
	global $player;

	$update = new stdClass();
	$update->xpMax = $player->nextLevel;
	$update->xp = isset($value) ? $value : $player->xp;
	$update->xp = round($update->xp);

	if ($lvl)
	{
		$update->lvl = $lvl;
	}

	update(UPD_XP, $update);
}

function update_stats($stats = null)
{
	global $player;
	global $DS_typed;

	$update = new stdClass();

	$update->shallow	= array_intersect_key($player->getShallowArray('DSs'), $DS_typed);
	$update->deep		= array_intersect_key($player->getDeepArray('DSs'), $DS_typed);

	if (is_array($stats))
	{
		$stats				= array_flip($stats);
		$update->shallow	= array_intersect_key($update->shallow, $stats);
		$update->deep		= array_intersect_key($update->deep, $stats);
		$update->rebuild	= false;
	}
	else
	{
		$update->rebuild	= true;
	}

	update(UPD_STATS, $update);

	update_readiness();
}

function update_readiness()
{
	global $player;

	if (!isset($player->attack)) $player->attack = new Attack($player);

	$DPS = 0;
	$DMGs = $player->attack->base_DMGs;

	$playerBiases = $player->getTEQbiases();
	$cooldown = $player->attack->getcooldown($playerBiases[TEQ_ATTACK_SPEED]);
	$DPS = number_format(array_sum($DMGs) / $cooldown, 1);

	$update = [
		'DMGs'	=> $DMGs,
		'DMGDL'	=> $player->DMGDL,
		'DMGs_def' => $player->DMGs_def,
		'DPS' => $DPS,
		'APS' => round(1 / $cooldown, 1),
	];

	update(UPD_DMG_DEF, $update);
}

function update_opponent()
{
	global $player;
	global $view;

	if ($player->distanceFrom($player->opponent) < 10)
	{
		$update = new stdClass();

		$playerBiases	= $player->getTEQbiases();
		foreach($playerBiases as &$value) $value += $value * (($player->level - $player->opponent->level) * 0.2);
		$opponentBiases	= $player->opponent->getTEQbiases();
//		foreach($opponentBiases as &$value) $value *= $player->opponent->level / $player->level;

		$update->name	= $player->opponent->name;
		$update->hp		= round($player->opponent->hp, 1);
		$update->hpMax	= $player->opponent->hp_Max;
		$update->lvl	= $player->opponent->level;

		$update->hc		= getBiasCalculation($playerBiases[TEQ_HIT_CHANCE], $opponentBiases[TEQ_DODGE_CHANCE], GBC_PERCENTAGE);
		$update->hc		= number_format($update->hc, 1);
		$update->hcB	= $playerBiases[TEQ_HIT_CHANCE];

		$update->dc		= 100 - getBiasCalculation($opponentBiases[TEQ_HIT_CHANCE], $playerBiases[TEQ_DODGE_CHANCE], GBC_PERCENTAGE);
		$update->dc		= number_format($update->dc, 1);
		$update->dcB	= $playerBiases[TEQ_DODGE_CHANCE];

		$update->cc		= getBiasCalculation($playerBiases[TEQ_CRIT_CHANCE], $opponentBiases[TEQ_DODGE_CHANCE], GBC_PERCENTAGE);
		$update->cc		= number_format($update->cc, 1);
		$update->ccB	= $playerBiases[TEQ_CRIT_CHANCE];


		if ($player->opponent->statuses)
		{
			$update->statuses = [];

			foreach ($player->opponent->statuses as $status)
			{
				$upd = new stdClass();
				$upd->spr = $view->addClientSprite($status->sprite)->key;
				$upd->desc = $status->description;

				$update->statuses[] = $upd;
			}
		}

		update(UPD_OPPONENT, $update);
	}
	else
	{
		$player->opponent = null;
		clearPanel(UPD_OPPONENT);
	}
}

function update_statuses()
{
	global $player;

	$player->checkStatuses();
}

function update_quests($quests = null)
{
	global $player;

	$update = new stdClass();

	$update->quests = [];

	$player->getActiveTasks();

	$discardComplete = false;

	if (empty($quests))
	{
		$quests = $player->quests;
		// If we're getting all of them, it's probably because we're populating
		// the quest panel on load, not because something has actually happened
		// so don't notify.
		$update->notify = false;

		$update->completed = [];
		foreach ($player->completedQuests as $name => $timestamp)
		{
			$update->completed[$name] = date('m/d/y', $timestamp);
		}
		$discardComplete = true;
	}
	else
	{
		// We were told to update a specific quest(s); something probabaly
		// actually happened; we should update.
		$update->notify = true;
	}

	foreach ($quests as $key => $quest)
	{
		if ($discardComplete && $quest->complete)
		{
			unset($player->quests[$key]);
			continue;
		}
		if (!$quest->hidden) $update->quests[] = $quest->getAjaxObject();
	}

	update(UPD_QUESTS, $update);
}

function update_task(Task $task)
{
	$update = new stdClass();

	$update->tasks = [];

	$update->tasks[] = $task->getAjaxObject();

	update(UPD_TASKS, $update);
}

function update_skills()
{
	global $player;

//	if ($player instanceof EditorPlayer2)
//	{
//		update_skills_EDITOR();
//		return;
//	}

	$update = [];

	foreach ($player->skills as $index => $skill)
	{
		$s = new stdClass();

		$s->name = $skill->name;
		$s->level = $skill->level;
		if ($skill->SKLS) $s->SKLS = $skill->SKLS;

		$update["{$index}"] = $s;
	}

	update(UPD_SKILLS, $update);
}

function update_passives ()
{
	global $player;

	$update = [];

	foreach ($player->passives as $index => $passive)
	{
		$p = new stdClass();

		$p->name = $passive->name;
		$p->level = $passive->level;

		$update["{$index}"] = $p;
	}

	update(UPD_PASSIVES, $update);
}

function update_skillInfo(Skill $skill)
{
	global $view;

	$update = new stdClass();

	$update->name	= $skill->name;
	$update->desc	= $skill->getDescription();

	$update->sprite	= $view->addClientSprite($skill->sprite)->key;

	$update->level	= $skill->level;
	$update->id		= $skill->key;

	if ($skill->epCost)		$update->ep			= $skill->epCost;
	if ($skill->hpCost)		$update->hp			= $skill->hpCost;
	if ($skill->cooldown)	$update->cooldown	= $skill->cooldown;
	if ($skill->range)		$update->range		= $skill->range;

	update(UPD_SKILL_INFO, $update);
}

function update_passiveInfo(Passive $passive)
{
	global $view;

	$update = new stdClass();

	$update->name	= $passive->name;
	$update->desc	= $passive->getDescription();

	$update->sprite	= $view->addClientSprite($passive->sprite)->key;

	$update->level	= $passive->level;
	$update->id		= $passive->key; //get_class($skill);

	if ($passive->cooldown)	$update->cooldown	= $passive->cooldown;

	update(UPD_PASSIVE_INFO, $update);
}

function update_bindings()
{
	global $player;
	global $view;

	$update = [];

	foreach ($player->bindings as $SKLS => $binding) // $action will be a skill or (eventually) an item
	{
		$b = new stdClass();
		$action = $binding->getSubject();

		if ($view) $b->sprite = $view->addClientSprite($action->sprite)->key;
		$b->desc = isset($action->description) ? $action->description : $action->getDescription();

		$update[$SKLS] = $b;
	}

	update(UPD_BINDINGS, $update);
}

function update_technique ()
{
	global $player;

	$update = [
		TEQT_MELEE		=> $player->getTEQbiases(TEQT_MELEE,	true),
		TEQT_RANGED	=> $player->getTEQbiases(TEQT_RANGED,	true),
		TEQT_MAGIC		=> $player->getTEQbiases(TEQT_MAGIC,	true),
	];

	update(UPD_TECHNIQUE, $update);
}

function update_boons ()
{
	global $player;

	$update = new stdClass();

	$update->boons = [];
	$update->pending = $player->pendingBoonCount;

	foreach ($player->pendingBoons as $index => $boon)
	{
		$update->boons[] = [
			'key' => $index,
			'name' => $boon->name,
			'desc' => $boon->description,
		];
	}

	update(UPD_BOONS, $update);
}

function update_sound ($SND)
{
	update(UPD_SOUNDS, func_get_args());
}