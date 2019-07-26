<?php

$rootPath = '../';

require "{$rootPath}engine/core/include.php";

$key		= (isset($_POST['k']) ? $_POST['k'] : null);
$type		= (isset($_POST['t']) ? $_POST['t'] : null);
$content	= (isset($_POST['c']) ? $_POST['c'] : null);

if (empty($key))
{
	console_echo('UI event sent without panel reference.', '#faa');	//XXX
	DIE('0');
}

console_echo('UI event:', '#faf');			//XXX
console_echo("Key:\t{$key}", '#ddd');		//XXX
console_echo("Type:\t{$type}", '#ddd');		//XXX
console_echo("Cont:\t{$content}", '#ddd');	//XXX

switch($key)
{
	case '':
	case null:
		console_echo('Server notified without a key. Server says this: <<#faa>>I dunno what to do.<>', '#ffa');	//XXX
		break;
	case UPD_ITEMS:
		$item = $player->inventory->getItemByIndex($content)->onClick($type);
		break;

	case UPD_AVAILABLE:
		if (!isset($player->engagement) || !isset($player->engagement->inventory))
		{
			console_echo('Player has no enganement or engagement lacks an inventory; "Available" panel shouldn\'t be populated.', '#faa');	//XXX
			break;
		}
		$player->engagement->inventory->getItemByIndex($content)->onClick($type);
		break;

	case UPD_SKILLS:
		switch($type)
		{
			case UIN_CLICK:
				$player->skills[$content]->inspect();
				break;

			case UIN_RIGHT_CLICK:
				$player->useSkill($content);
				break;
		}

		break;

	case UPD_PASSIVES:
		$player->passives[$content]->inspect();
		break;

	case UPD_BINDINGS:
		switch ($type)
		{
			case UIN_CLICK; // Inspect whatever's bound there.
				if (!isset($player->bindings[$content])) break;

				$binding = $player->bindings[$content];

				switch (get_class($binding))
				{
					case 'ItemBinding':
					case 'SkillBinding':
						$binding->getSubject()->inspect();
						break;
				}

				break;
		}
		break;

	case UPD_BOONS:
//		$content = (int)$content;
		switch ($type)
		{
			case UIN_CLICK:
				break;

			case UIN_RIGHT_CLICK:
				$player->selectBoon($content);
				break;
		}
		break;

	case UPD_INTERACTIONS:
		console_echo("Invoking interaction with key <<#fff>>{$content}<>", '#aaf');
		if (!$player->engagement) break;

		$content = explode('#', $content, 2);

		console_var_dump($content);

		$NPCI = $content[0];
		$item = isset($content[1]) ? $content[1] : null;

		if (!isset($player->engagement->NPCIs[$NPCI])) break;

		if ($item)
		{
			console_echo('Clicking on an interaction item');
			$player->engagement->NPCIs[$NPCI]->onItemClick($type, $item);
		}
		else
		{
			console_echo('Clicking on an interaction');
			$player->engagement->NPCIs[$NPCI]->onClick($type);
		}
		break;

	case 'BIND':
		$content = explode(',', $content);

		$SKLS = (int)$content[0];
		$index = $content[1];

//		if ($player instanceof EditorPlayer2)
//		{
//			if ($SKLS > 9) break;
//		}

		if (isset($player->skills[$index]))
		{
			$binding = new SkillBinding($player, $index, $SKLS);
			$binding->bind();
		}
		elseif (isset($player->inventory->contents[$index]))
		{
			$binding = new ItemBinding($player, $index, $SKLS);
			$binding->bind();
		}
		else
		{
			console_echo("Trying to bind something with the ID <<#fff>>\"{$index}\"<> to SKLS {$SKLS}.", '#faa');
		}

		update_bindings();

		break;

	/******************************************\
		MAP CLICK
	\******************************************/

	case 'MAP_CLICK':
		$clickCoOrds = $view->viewPosToMapPos($content);
		if ($player instanceof EditorPlayer)
		{
			$player->move($clickCoOrds[0], $clickCoOrds[1]);
//			$player->w_offset = $newCoOrds[1];
		}
		else
		{
			console_echo('looking for items to inspect');
			if (isset($map->objects[$clickCoOrds[0]][$clickCoOrds[1]]))
			{
				foreach ($map->objects[$clickCoOrds[0]][$clickCoOrds[1]] as $object)
				{
					console_echo(get_class($object));
					if ($object instanceof obj_collectible)
					{
						$object->behaviours[BHVK_PRIMARY][0]->item->inspect(); // Oosenupt - This is ugly. Probably remove this feature.
					}
				}
			}
		}
		$view->forceUpdate = true;
		break;

	/******************************************\
		MAP KEY PRESS
	\******************************************/

	case 'MAP_KEY':
		$clickCoOrds = $content !== 'false' ? $view->viewPosToMapPos($content) : [null, null];

		if (isset($player->bindings[(int)$type]))
		{
			$binding = $player->bindings[(int)$type];

			switch (get_class($binding))
			{
				case 'ItemBinding':
					console_echo('Using ItemBinding!', '#faf');
					$binding->getSubject()->useItem();
					break;
				case 'SkillBinding':
					console_echo('Using SkillBinding!', '#aff');
					$player->useSkill((int)$type, $clickCoOrds[0], $clickCoOrds[1]);
					break;
			}
		}
		else																				//XXX
		{																					//XXX
//			$numSpriteE_1 = new SpriteElement('#000', '#f00', $type);						//XXX
//			$numSpriteE_2 = new SpriteElement('#000', '#0f0', $type);						//XXX
//			//																				//XXX
//			$numSprite = new Sprite([														//XXX
//				[0 => $numSpriteE_1, 5 => $numSpriteE_2],									//XXX
//				[1 => $numSpriteE_1, 4 => $numSpriteE_2],									//XXX
//				[2 => $numSpriteE_1, 3 => $numSpriteE_2],									//XXX
//				[5 => $numSpriteE_1, 0 => $numSpriteE_2],									//XXX
//				[4 => $numSpriteE_1, 1 => $numSpriteE_2],									//XXX
//				[3 => $numSpriteE_1, 2 => $numSpriteE_2],									//XXX
//			]);																				//XXX
//
//
//			$effect = new Effect(spr_DS_lower(DS_FAME), $clickCoOrds[0], $clickCoOrds[1], 12);		//XXX
//			$effect = new Effect(spr_DS_boost(DS_CHARISMA), $clickCoOrds[0], $clickCoOrds[1], 12);	//XXX
//
////			$effect = new Effect($numSprite, $clickCoOrds[0], $clickCoOrds[1], 12, true);	//XXX
//
//			$map->addEffects($effect);														//XXX
		}																					//XXX
		break;


	case 'init';
		if (!($player instanceof EditorPlayer || $player instanceof EditorPlayer2))
		{
			update_stats();
			update_readiness();
			update_items();
			update_money();
			update_playerHp();
			update_playerEp();
			update_playerXp();
			update_quests();
			update_skills();
			update_passives();
			update_bindings();
			update_technique();
			update_boons();
			$player->updateEquipmentSprite();
//			$player->changeSprite(SPRI_GEAR);
			$player->setSPRI(SPRI_GEAR);
		}
		elseif ($player instanceof EditorPlayer2)
		{
			update_skills_EDITOR();
			update_bindings();
			$player->sprite = $view->addClientSprite($player->sprite);
		}
		break;



	case 'console':
		if (!DEV_MODE) break;

		console_forceScroll();

		if ($type === 'STREAM')
		{
			switch($content)
			{
				case 'allOn': $allStreams = true; break;
				case 'allOff': $allStreams = false; break;
				default: console_toogleStream((int) $content); return;
			}

			foreach ($consoleStreams as $STREAM => $val)
			{
				$_SESSION['console']['streams'][$STREAM] = $allStreams;
			}

			$_SESSION['console']['updateStreams'] = true;

			return;
		}

		$commandParts = explode(' ', $content, 2);

		switch(strtolower($commandParts[0]))
		{
			case 'newmap':

				if (!isset($commandParts[1]))
				{
					console_echo('Gotta stick amap name in there.'. '#faa');
					break;
				}

				$newMapName = explode(' ', $commandParts[1], 2)[0];
				$newMapName = trim($newMapName, '\\/');


				if (!file_exists("{$rootPath}content/maps/{$newMapName}.map"))
				{
					$newMapTemplate = file_get_contents("{$rootPath}tools/editor_v2/_newMapTemplate.map");

					try
					{
						file_put_contents("{$rootPath}content/maps/{$newMapName}.map", $newMapTemplate);
						file_put_contents("{$rootPath}content/maps/{$newMapName}.mtl", ' ');
						file_put_contents("{$rootPath}content/maps/{$newMapName}.msl", ' ');
					}
					catch (Exception $e)
					{
						console_echo('Unable to create map. Make sure the folder exists first.', '#faa');
						break;
					}

					console_echo("New map created: <<#fff>>{$newMapName}<>.", '#afa');
				}
				else
				{
					console_echo('Can\'t make a new map with the same name as an existing map!');
				}
				break;

			case 'give':

				$itemFound = false;
				$itemList = [];

				if (!isset($commandParts[1])) $commandParts[1] = 'null item';
				if (strtolower($commandParts[1]) === 'aids') { console_echo('WTF is wrong with you?', '#aaf'); break; }

				foreach ($map->objects as $objectRow)
				{
					foreach ($objectRow as $objectLayer)
					{
						foreach ($objectLayer as $object)
						{
							if (isset($object->inventory))
							{
								foreach ($object->inventory->contents as $invItem)
								{
									$itemList[] = $invItem->name;

									if (strtolower($invItem->name) === strtolower($commandParts[1]))
									{
										$giveItem = clone $invItem;
										$player->inventory->add(clone $giveItem);
										console_echo("{$commandParts[1]} given!", '#afa');
										$itemFound = true;
										break;
									}
								}
							}
							elseif ($object instanceof obj_collectible)
							{
								$collItem = $object->behaviours[BHVK_PRIMARY]->item;

								$itemList[] = $collItem->name;

								if (strtolower($collItem->name) === strtolower($commandParts[1]))
								{
									$giveItem = clone $collItem;
									$player->inventory->add($giveItem);
									console_echo("{$commandParts[1]} given!", '#afa');
									$itemFound = true;
									break;
								}
							}
							if ($itemFound) break;
						}
						if ($itemFound) break;
					}
					if ($itemFound) break;
				}

				if (!$itemFound)
				{
					foreach ($itemList as $itemName) console_echo($itemName);
					console_echo("Couldn't find anything called \"{$commandParts[1]}\" in {$map->mapPath}.", '#fdd');
				}

				break;



			case 'kill':
				$dudesKilled = 0;
				$dudeName = (isset($commandParts[1]) ? strtolower($commandParts[1]) : null);

				$player->attack->alwaysHit = true;

				foreach ($map->objects as $objectRow)
				{
					foreach ($objectRow as $objectLayer)
					{
						foreach ($objectLayer as $object)
						{
							if ($object instanceof Player) continue;

							if ($object instanceof Dude && ($dudeName === null || strtolower($object->name) === $dudeName))
							{
								//if (empty($player->attack)) $player->attack = new Attack($player);

								$object->hp = 0;
//								$player->doAttack($object);
								$object->death($player->attack);
								$player->attack->readyTime = 1;

								console_echo("{$object->name} killed.", '#afa');
								$dudesKilled ++;
							}
						}
					}
				}

				$player->attack->alwaysHit = null;

				if ($dudesKilled === 0 && $dudeName) console_echo("Coun't find anyone called {$dudeName}.", '#faa');

				console_echo("Killed {$dudesKilled} dudes.", '#afa');

				break;

			case 'eval':
			case 'sudo':

				if (isset($commandParts[1]))
				{
					$code = highlight_string('<?php '.$commandParts[1], true);

					$old = [
						'#000000',
						'#007700',
						'#DD0000',
						'#0000BB',
					];

					$new = [
						'#fff',
						'#f80',
						'#77f',
						'#ff5',
					];

					$code = str_replace($old, $new, $code);

					console_echo($code);
					eval($commandParts[1]);
					$view->forceUpdate = true;
				}

				break;


			case 'go':

				if (isset($commandParts[1]))
				{
					$coOrds = explode(' ', $commandParts[1]);
				}
				else
				{
					console_echo('RANDOM LOCATION!', '#faf');
					for ($i = 0; $i < 999; $i++)
					{
						$coOrds = [mt_rand(0,$map->height), mt_rand(0,$map->width)];

						if (isset($map->tiles[$coOrds[0]][$coOrds[1]]) &&
							$map->tiles[$coOrds[0]][$coOrds[1]]->canEnter &&
							(!isset($map->scenery[$coOrds[0]][$coOrds[1]]) ||
							$map->scenery[$coOrds[0]][$coOrds[1]]->canEnter)
							)break 1;
					}

					console_echo("It took {$i} retries to find a good spot to put you.");
				}

				if (isset($coOrds[0]) && isset($coOrds[1]) && is_numeric($coOrds[0]) && is_numeric($coOrds[1]))
				{
					$mapPath = isset($coOrds[2]) ? constant('MAP_' . strtoupper($coOrds[2])) : null;

					$moved = $player->move($coOrds[0], $coOrds[1], $mapPath);

					if (!$moved) $map->moveObject($player, $coOrds[0], $coOrds[1], true);
				}

				$view->forceUpdate = true;

				console_echo("Player now at {$player->n_offset}:{$player->w_offset} - {$player->MAP}", '#afa');

				break;

			case 'n':
			case 's':
			case 'e':
			case 'w':
				if (isset($commandParts[1]) && is_numeric($commandParts[1]))
				{
					switch($commandParts[0])
					{
						case 'n': $player->n_offset -= $commandParts[1]; break;
						case 's': $player->n_offset += $commandParts[1]; break;
						case 'w': $player->w_offset -= $commandParts[1]; break;
						case 'e': $player->w_offset += $commandParts[1]; break;
					}
					$view->forceUpdate = true;
				}
				break;

			case 'error':
				$x = $Undefined_variable;
				break;

			case 'move':
				$moveVars = explode(' ', $commandParts[1]);
				$objectName = strtolower(str_replace('_', ' ', $moveVars[0]));
				$new_n_offset = (int)$moveVars[1];
				$new_w_offset = (int)$moveVars[2];

				if (is_numeric($new_n_offset) && is_numeric($new_w_offset))
				{
					foreach ($map->objects as $objectRow)
					{
						foreach ($objectRow as $objectLayer)
						{
							foreach ($objectLayer as $object)
							{
								if (strtolower($object->name) === $objectName)
								{
									$old_n_offset = $object->n_offset;
									$old_w_offset = $object->w_offset;

									$object->n_offset = $new_n_offset;
									$object->w_offset = $new_w_offset;

									if (!isset($map->objects[$new_n_offset][$new_w_offset][$object->layer]))
									{
										$map->objects[$new_n_offset][$new_w_offset][$object->layer] = $object;
										$map->destroyObject($old_n_offset, $old_w_offset, $object->layer);
									}
									else
									{
										// collision
									}

									break 4;
								}
							}
						}
					}
				}

				break;

			case 'heal':
				$player->alterHp(999);
				update_thoughts('I feel way better now.');
				console_echo('You feel healthy and full of life!', '#faf');
				break;

			case 'rebirth':
				$player->alterHp(999);
				$player->alterEp(999);

				foreach ($player->inventory->contents as $item)
				{
					$player->inventory->pullItem($item);
				}
				update_thoughts('I\'m like a bebbeh now.');
				console_echo('Reborn like new!');
				break;

			case 'cash':
				update_thoughts('My pockets just got a whole lot heavier.');

				foreach ($currencies as $CUR => $crap)
				{
					$player->inventory->add(new itm_money($CUR, 1000));
				}
				break;

			case 'sertest':
				$preSer = microtime(true);
				$var = serialize($map);
				$var = unserialize($var);
				$postSer = microtime(true);
				$jam = round(($postSer - $preSer) * 1000000);
				console_echo("<<#afa>>Map:<>\t\t{$jam} &#x03bc;s", '#fff');

				$preSer = microtime(true);
				$var = serialize($map->objects);
				$var = unserialize($var);
				$postSer = microtime(true);
				$jam = round(($postSer - $preSer) * 1000000);
				console_echo("<<#afa>>Map objects:<>\t{$jam} &#x03bc;s", '#fff');

				$preSer = microtime(true);
				$var = serialize($map->tiles);
				$var = unserialize($var);
				$postSer = microtime(true);
				$jam = round(($postSer - $preSer) * 1000000);
				console_echo("<<#afa>>Map tiles:<>\t{$jam} &#x03bc;s", '#fff');

				$preSer = microtime(true);
				$var = serialize($map->scenery);
				$var = unserialize($var);
				$postSer = microtime(true);
				$jam = round(($postSer - $preSer) * 1000000);
				console_echo("<<#afa>>Map scenery:<>\t{$jam} &#x03bc;s", '#fff');

				$preSer = microtime(true);
				$var = serialize($player);
				$var = unserialize($var);
				$postSer = microtime(true);
				$jam = round(($postSer - $preSer) * 1000000);
				console_echo("<<#faf>>Player:<>\t\t{$jam} &#x03bc;s", '#fff');

				$preSer = microtime(true);
				$var = serialize($view);
				$var = unserialize($var);
				$postSer = microtime(true);
				$jam = round(($postSer - $preSer) * 1000000);
				console_echo("<<#ffa>>View:<>\t\t{$jam} &#x03bc;s", '#fff');

				break;

			case 'arm':
				$player->inventory->add(new eqp_axe($player->level));
				$player->inventory->add(new eqp_sword($player->level));
				break;

			case 'stuffs':
				$player->inventory->add(new eqp_bar($player->level));
				$player->inventory->add(new eqp_axe($player->level));
				$player->inventory->add(new eqp_sword($player->level));
				$player->inventory->add(new eqp_belt($player->level));
				$player->inventory->add(new eqp_boots($player->level));
				$player->inventory->add(new eqp_gloves($player->level));
				$player->inventory->add(new eqp_jacket($player->level));
				$player->inventory->add(new eqp_shield($player->level));
				break;

			case 'eqplvl':
				$vars = explode(' ', $commandParts[1]);
				console_var_dump($vars);

				$equipmentType = "eqp_{$vars[0]}";
				$bottomLevel = (int) $vars[1];
				$upperLevel = (int) $vars[2];

				if ($bottomLevel > $upperLevel)
				{
					$x = $bottomLevel;
					$bottomLevel = $upperLevel;
					$upperLevel = $x;
				}

				for ($lvl = $bottomLevel; $lvl <= $upperLevel; $lvl ++)
				{
					$player->inventory->add(new $equipmentType($lvl));
				}

				break;

			case 'breakstuff':

				foreach ($player->inventory->contents as $item)
				{
					$damage = mt_rand(1, $item->durability / 2);
					for ($i = 5; $i <= $damage; $i++)
					{
						$item->damageItem();
					}
				}

				break;

			case 'levelup':
				$player->levelUp();
				$player->xp = 0;
				break;

			case 'hitself':
				$player->doAttack($player);
				update_thoughts('Do not talk about Fight Club.');
				break;

			case 'killself':
				$player->death($player->attack);
				update_thoughts('Do not talk about Fight Club.');
				break;

			case 'phpinfo':
				ob_start();
				phpinfo();
				console_echo(ob_get_clean());
				break;

			case 'ce':
				$eval = "console_echo({$commandParts[1]}, '#fff');";
				eval($eval);
				break;

			case 'cvd':
				$eval = "console_var_dump({$commandParts[1]}, '#fff');";
				eval($eval);
				break;

			case 'evs':
				// Note to anyone looking at this case in the future:
				// Sometimes I just want to get shit done and doing it well is secondary.
				// That said, even when I write shit code, I like it to be shit only in
				// it's readibility; not also it its workings.
				// Judge that however you like.

				if (!($player instanceof EditorPlayer2) || !isset($commandParts[1])) break;

				$dims = explode(' ', $commandParts[1]);

				if (is_numeric($dims[0]))
				{
					$dims[0] = $dims[0] % 2 ? $dims[0] : $dims[0] + 1;
					$view->editorWidth = min([(int)$dims[0], 100]);

					if (isset($dims[1]) && is_numeric($dims[1]))
					{
						$dims[1] = $dims[1] % 2 ? $dims[1] : $dims[1] + 1; // TERRIBLE
						$view->editorHeight = min([(int)$dims[1], 100]);
					}
					else
					{
						$view->editorHeight = min([(int)$dims[0], 100]);
					}
				}

				break;

			case 'help':
				console_echo('<br>CONSOLE HELP', '#faf');
				console_echo('This console was designed specifically for debugging and monitoring the processing behind Asciilands.', '#fff');
				console_echo('Each frame produces a console entry and the console caches 200 entries.', '#fff');
				console_echo('Press the up arrow to pause auto-scrolling and the down arrow to resume auto-scrolling.', '#fff');
				console_echo('Entries will not be pruned (and will exceed 200) if auto-scroll is not active.', '#fff');
				console_echo('Right click on an entry to pin it to the top of the console.', '#fff');
				console_echo('Rolling over the faded blue panel at the top of the console will reveal the pinned entry to later reference.', '#fff');
				console_echo('Rolling over the green panel to the right will reveal the output of the trawler. The trawler aggregates all files used by each map.', '#fff');
				console_echo('Refreshing the console window will re-run the trawler.', '#fff');
				console_echo('This will rebuild the .req file and is necessary before most code-changes will be reflected in the browser.', '#fff');
				console_echo('The map will flicker and bug out during this process but return to normal upon successful ompletion.', '#fff');
				console_echo('In the lower right-hand corner is a latency vs time graph. Rolling over it will show a segment for each panel in the console.', '#fff');
				console_echo('Right clicking on a bar in the graph will pin the associated frame\'s output panel for examination.', '#fff');
				console_echo('This can be useful for identifying intensive, laggy operations.', '#fff');
				console_echo('Frames that produce an error will have their console panel and their graph bar reddened.', '#fff');

				console_echo('<br>LIST OF COMMANDS', '#faf');
				console_echo ('Commands are listed as follows: <span style="color:#afa;">command / alias {required param} [optional param]</span><br> - Commands are not case sensitive but parameters are.<br> - Required parameters are shown in braces and optional parameters are shown in quare brackets.', '#fff');
				console_echo ('<br>');
				console_echo ('<<#afa;>>newmap {map path}<><br> - Creates the map for the path specified. White space is trimmed but don\'t use characters that are incompatible with directory or file names. Re-run the build script after map creation.' , '#fff');
				console_echo (' - e.g., "newmap mapPath/mapName" would create the map "mapName.map" in the directory "mapPath".', '#aaa');
				console_echo ('<br>');
				console_echo ('<<#afa;>>go {north offset} {west offset} [map name]<><br> - Moves the player to the specified co-ordinates and (optionally) to the specified map.', '#fff');
				console_echo (' - e.g., "go 10 10" or "go 13 9 bluffCave" (map name is case sensitive)', '#aaa');
				console_echo ('<br>');
				console_echo ('<span style="color:#afa;">n / s / e / w {number}</span><br> - Moves the player the specified number of spaces in the compass direction in the command.', '#fff');
				console_echo (' - e.g., "e 25" (move 25 spaces to the east)', '#aaa');
				console_echo ('<br>');
				console_echo ('<span style="color:#afa;">give [name of item]</span><br> - Searches the map for an item with the given name and gives to player. If not found, returns list of items in the map.', '#fff');
				console_echo (' - e.g., "give" (for list of items) or "give silver axe" (item name is not case sensitive)', '#aaa');
				console_echo('<br>');
				console_echo ('<<#afa>>heal<><br> - Heals player to full health', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>rebirth<><br> - Fully heals and charges and clears out inventory', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>cash<><br> - Gives 1000 gold\'s worth of each currency.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>arm<><br> - Gives a random axe and sword.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>stuffs<><br> - Gives one of pretty much everything.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>eqplvl {bottom} {top}<><br> - Gives equipment for every level defined by "top" and "bottom" attributes.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>breakstuff<><br> - Damages all your stuff by a random amount.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>levelup<><br> - Grants a level-up.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>hitself<><br> - Perform your own primary attack on yourself.', '#fff');
				console_echo('<br>');
				console_echo ('<span style="color:#afa;">kill [name of dude]</span><br> - Kills all dudes with provided name. If no name is provided, kills all dudes.', '#fff');
				console_echo (' - e.g., "kill" (to kill everyone in the map) or "kill zombie" (name is not case sensitive)', '#aaa');
				console_echo('<br>');
				console_echo ('<<#afa>>sudo / eval {php code}<><br> - Runs php code in the context of a notifiyServer. Don\'t to anything stupid.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>ce / cvd {php code}<><br> - Runs either "console_echo" or "console_var_dump" on whatever is passed in. Since 99% of "sudo" commands seem be to this end, this should save a few seconds a day.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>error<><br> - Throws an error. This is just to test error handling.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>sertest<><br> - Displays data on the time it takes to serialise and unserialise various parts of the global objects.', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>phpinfo<><br> - Dumps phpinfo() data into the console (might look weird).', '#fff');
				console_echo('<br>');
				console_echo ('<<#afa>>evs [width] {height}<><br> - Editor view size - if just one value is given, it will be applied to both dimensions. Height and width can both be set.', '#fff');
				console_echo('<br>');
				break;



			default:
				console_echo("Unknown command \"{$content}\"", '#faa');
				console_echo('Type "<span style="color:#afa">help</span>" for a list of recognised commands', '#faa');
				break;
		}

		break;




	case 'EDITOR_TILE':									// EDITOR LINE
		switch ($type)									// EDITOR LINE
		{												// EDITOR LINE
			case UIN_CLICK:								// EDITOR LINE
				placeTile($content);					// EDITOR LINE
				break;									// EDITOR LINE
			case UIN_RIGHT_CLICK:						// EDITOR LINE
				brushTile($content);					// EDITOR LINE
				break;									// EDITOR LINE
			case UIN_CTRL_CLICK:						// EDITOR LINE
				tileFillArea($content);					// EDITOR LINE
				break;									// EDITOR LINE
		}												// EDITOR LINE
		break;											// EDITOR LINE
	case 'EYE_DROPPER':									// EDITOR LINE
		eyeDropper();									// EDITOR LINE
		break;											// EDITOR LINE
	case 'EDITOR_AREA':									// EDITOR LINE
		setSelection();									// EDITOR LINE
		break;											// EDITOR LINE
	case 'EDITOR_T_COPY':								// EDITOR LINE
		copyArea();										// EDITOR LINE
		break;											// EDITOR LINE
	case 'EDITOR_T_PASTE':								// EDITOR LINE
		pasteArea();									// EDITOR LINE
		break;											// EDITOR LINE
	case 'EDITOR_S_COPY':								// EDITOR LINE
		copyArea(false, true);							// EDITOR LINE
		break;											// EDITOR LINE
	case 'EDITOR_S_PASTE':								// EDITOR LINE
		pasteArea(false);								// EDITOR LINE
		break;											// EDITOR LINE
	case 'EDITOR_SCENERY':								// EDITOR LINE
		changeScenery($content);						// EDITOR LINE
		break;											// EDITOR LINE
	case 'EDITOR_SAVE':									// EDITOR LINE
		saveMap();										// EDITOR LINE
		break;											// EDITOR LINE


	case 'EDITOR_PASTE':
		if ($content === 'tiles')
		{
			$player->pasteAssets(true);
		}
		else
		{
			$player->pasteAssets(false);
		}
		break;
}

$_SESSION[$map->id] = serialize($map);

if (DEV_MODE)
{
	console_setFrameTime();
	if (count($_SESSION['console']) > 10) unset($_SESSION['console']);
}
