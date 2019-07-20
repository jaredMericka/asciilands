<?php if (false) { ?> <script> <?php }
header("content-type: application/javascript");

$rootPath = '../../';
require "{$rootPath}engine/core/constants.php";

?>

function applyUpdates()
{
	var updates = JSON.parse(updateString);

	for (key in updates)
	{
		var update = updates[key];

		if (update === "<?php echo UPDB_CLEAR; ?>")
		{
			var panel = document.getElementById(key);
			if (!panel) continue;
			panel.innerHTML = '';
			panel.clearAnnex();
		}
		else  if (isFunction('update_' + key))
		{
			try {
				window['update_' + key](update);
			} catch(e) { }
		}
		else
		{
			element = document.getElementById(key);
			if (element !== undefined && element !== null)
			{
				element.innerHTML = update;
			}
			else
			{
				console_echo('Update sent for <span style="color:#ffa;">"' + key + '"</span> but we can\'t figure out what to do with it.', '#faa');
			}
		}
	}
}


//////////////////////////////////////
// CONVERSATION
//////////////////////////////////////

function update_<?php echo UPD_CONVERSATION; ?>(update)
{
	var convDiv = document.getElementById("<?php echo UPD_CONVERSATION ?>");

	for (index in update.lines)
	{
		var line = update.lines[index];

		var lineDiv = document.createElement('div');
		lineDiv.className = 'convLine';

		var lineHeader = document.createElement('span');
		lineHeader.style.color = line.colour;
		lineHeader.innerHTML = line.header + ": ";

		lineDiv.appendChild(lineHeader);
		lineDiv.innerHTML = lineDiv.innerHTML + line.body;

		convDiv.appendChild(lineDiv);
	}

	if (convDiv.childNodes.length > 10)
	{
		var lines = [].slice.call(convDiv.childNodes, -10);
		convDiv.innerHTML = '';
		for (index in lines)
		{
			convDiv.appendChild(lines[index]);
		}
	}

	convDiv.scrollTop = convDiv.scrollHeight;
	convDiv.notify();
}

//////////////////////////////////////
// OVERLAY
//////////////////////////////////////

function update_<?php echo UPD_OVERLAY; ?>(update)
{
	var overlay = document.getElementById('overlay');
	overlay.style.background = update.colour;
	overlay.style.opacity = update.opacity;
}

//////////////////////////////////////
// REFRESH RATE
//////////////////////////////////////

function update_<?php echo UPD_REFRESHRATE; ?>(update)
{
	console_echo('(REDUNDANT) Setting refresh interval to ' + update, '#afa');
//	setIdleTimer(update);
}

//////////////////////////////////////
// SPRITE
//////////////////////////////////////

function update_<?php echo UPD_SPRITE; ?>(update)
{
	if (update instanceof Array)
	{
		for(var index in update)
		{
			changeSprite(update[index]);
		}
	}
	else
	{
		changeSprite(update);
	}
}

function changeSprite(update)
{
	var sprite = spriteKey[update.key];
	var overSprite = update.sprite;

	if (update.augment === true)
	{
		for (var f = 0; f <= sprite.f-1; f ++)
		{
			var overFrame = overSprite['f'+((f+1) % overSprite.f)];
			if (overFrame === undefined) continue;

			for (var e = 0; e <= 5; e ++)
			{
				var element = overFrame['e' + e];
				if (element !== undefined)
				{
					sprite['f'+f]['e'+e] = element;
				}
			}
		}
	}
	else
	{
		spriteKey[update.key] = overSprite;
	}
}

//////////////////////////////////////
// TEXT
//////////////////////////////////////


function update_<?php echo UPD_COMMS; ?>(update)
{
	var stream = document.getElementById('commStream');
	var last = document.getElementById('commLast');

	for (index in update)
	{
		var item = update[index];

		var streamDiv = document.createElement('div');
		var lastDiv	= document.createElement('div');

		switch (item.commType)
		{
			case 'text':
				var id = 'texItem' + item.body[0];
				var existing = document.getElementById(id);
				if (existing)
				{
					streamDiv = existing;
					lastDiv = streamDiv.lastDiv;
					last.innerHTML = '[ ' + item.header + ' ]';
					break;
				}

				var textDiv = document.createElement('div');
				var headerDiv = document.createElement('div');

				var textBody = document.createElement('div');
				var pageManager = document.createElement('div');
				var pm_next = document.createElement('span');
				var pm_prev = document.createElement('span');
				var pm_page = document.createElement('span');

				streamDiv.id = id;

				headerDiv.innerHTML = item.header + ':';

				textDiv.className = 'commText';
				textBody.className = 'body';
				pageManager.className = 'pages';

				textDiv.style.backgroundColor = item.bg;
				textDiv.style.color = item.fg;

				textBody.innerHTML = item.body[0];

				if (item.width) textDiv.style.width = ((item.width + 2) * <?php echo CHAR_WIDTH; ?>) + 'px';
				if (item.height) textBody.style.height = ((item.height ) * <?php echo CHAR_HEIGHT; ?>) + 'px';

				pageManager.pages = item.body;
				pageManager.page = 0;
				pageManager.lastPage = item.body.length -1;
				pageManager.bodyDiv = textBody;
				pageManager.pageSpan = pm_page;

				pm_prev.innerHTML = '&#x25c4;';
				pm_page.innerHTML = (pageManager.page + 1) + ' of ' + (pageManager.lastPage + 1);
				pm_next.innerHTML = '&#x25ba;';
				pm_next.style.position = 'absolute';
				pm_next.style.right = '0px';

				pm_prev.pm = pageManager;
				pm_next.pm = pageManager;

				pm_next.onclick = nextPage;
				pm_prev.onclick = prevPage;

				pageManager.appendChild(pm_prev);
				pageManager.appendChild(pm_page);
				pageManager.appendChild(pm_next);

				textDiv.appendChild(textBody);
				if (pageManager.lastPage > 0) textDiv.appendChild(pageManager);

				streamDiv.appendChild(headerDiv);
				streamDiv.appendChild(textDiv);

				streamDiv.lastDiv = lastDiv;
				last.innerHTML = '[ ' + item.header + ' ]';

				break;

			case 'speech':
				var nameSpan = document.createElement('span');
				nameSpan.style.color = item.nameColour;
				nameSpan.innerHTML = item.name + ': ';
				streamDiv.appendChild(nameSpan);

				last.innerHTML = streamDiv.innerHTML;
				streamDiv.innerHTML = streamDiv.innerHTML + item.body;

				typeSpeech(item.body, lastDiv);
				break;
		}

		stream.appendChild(streamDiv);
	}

	while (stream.childNodes.length > 10)
	{
		stream.removeChild(stream.firstChild);
	}

//	last.innerHTML = lastDiv.outerHTML;
}

function nextPage()
{
	this.pm.page++;
	if (this.pm.page > this.pm.lastPage) this.pm.page = 0;

	this.pm.bodyDiv.innerHTML = this.pm.pages[this.pm.page];
	this.pm.pageSpan.innerHTML = (this.pm.page + 1) + ' of ' + (this.pm.lastPage + 1);
}

function prevPage()
{
	this.pm.page--;
	if (this.pm.page < 0) this.pm.page = this.pm.lastPage;

	this.pm.bodyDiv.innerHTML = this.pm.pages[this.pm.page];
	this.pm.pageSpan.innerHTML = (this.pm.page + 1) + ' of ' + (this.pm.lastPage + 1);
}

function turnPage(pages)
{
	var textPageNumber = document.getElementById('textPageNumber');
	var textBody = document.getElementById('textBody');

	textPage = textPage + pages;

	if (textPage < 0) textPage = textPages - 1;
	if (textPanelData[textPage] === undefined) textPage = 0;

	textBody.innerHTML = textPanelData[textPage];
	textPageNumber.innerHTML = (textPage + 1) + ' of ' + textPages;
}

var speechTimer = null;
var speechArray;
var speechIndex = 0;
var lastSpeechDiv;

function typeSpeech(text, div)
{
	var last = document.getElementById('commLast');
	if (text !== undefined)
	{
		if (speechTimer !== null)
		{
			cancelTimeout(speechTimer);
		}

		speechArray = text.split(' ');
		speechIndex = 0;

		setTimeout(typeSpeech, 100);
		return;
	}

	if (speechArray[speechIndex] === undefined)
	{
		speechArray = [];
		speechIndex = 0;
		return;
	}

	last.innerHTML = last.innerHTML + ' ' + speechArray[speechIndex];
	speechIndex++;

	update_<?php echo UPD_SOUNDS; ?>([SND_COMMS]);

	setTimeout(typeSpeech, 100);
}

//////////////////////////////////////
// ITEMS
//////////////////////////////////////

function update_<?php echo UPD_ITEMS; ?>(update)
{
	updateInventory('<?php echo UPD_ITEMS; ?>', update);
//	document.getElementById('<?php echo UPD_ITEMS; ?>').notify();
}

function update_<?php echo UPD_ITEM_INFO; ?>(update)
{
	var infoPanel = document.getElementById("<?php echo UPD_ITEM_INFO; ?>");
	var info = '';

//	if (infoPanel.spriteDiv) clearInterval(infoPanel.spriteDiv.timer);

	info = info + update.name;

	if (update.level) info = info + ' <span class="fade">(' + update.level + ')</span><br>';

	if (update.dur !== undefined)
	{
		info = info + '<br>Durability: ' + update.dur + ' / ' + update.durMax + '<br>';
	}

	if (update.DSs_req)
	{
		info = info + '<br>Requirements:<br><span style="color:#88f;">';
		for (var DS in update.DSs_req)
		{
			update.DSs_req[DS] = colouriseNumber(update.DSs_req[DS]);

			info = info + DS_names[DS] + ': ' + update.DSs_req[DS] + '<br>';
		}
		info = info + '</span>';
	}

	if (update.DSs)
	{
		info = info + '<br>Stats:<br><span style="color:#88f;">';
		for (var DS in update.DSs)
		{
			update.DSs[DS] = colouriseNumber(update.DSs[DS]);

			if (update.DSs[DS] % 100 < 50) { info = info + '<span style="color:#f80">&bull;</span> '; }
			else { info = info + '<span style="color:#666">&bull;</span> '; }
			info = info + DS_names[DS] + ': ' + update.DSs[DS] + '<br>';
		}
		info = info + '</span>';
	}

	if (update.DMGs_def)
	{
		info = info + '<br>Defence:<br><span style="color:#88f;">';
		for (var DMG in update.DMGs_def)
		{
			update.DMGs_def[DMG] = colouriseNumber(update.DMGs_def[DMG]);

			if (DMG_names[DMG] !== undefined)
			{
				info = info + '<span style="color:#666">&bull;</span> '
					+ DMG_names[DMG] + ' ' + update.DMGs_def[DMG] + '<br>';
			}
			else
			{
				info = info + '<span style="color:#f80">&bull;</span> '
					+ DMGDL_names[DMG] + ': ' + update.DMGs_def[DMG] + '<br>';
			}
		}
		info = info + '</span>';
	}

	if (update.DMGs)
	{
		info = info + '<br>Damage:<br><span style="color:#88f;">';
		for (var DMG in update.DMGs)
		{
			update.DMGs[DMG] = colouriseNumber(update.DMGs[DMG]);

			info = info + DMG_names[DMG] + ' ' + update.DMGs[DMG] + '<br>';
		}
		info = info + '</span>';
	}

	if (update.behaviours)
	{
		info = info + '<br>';
		for (var bhv in update.behaviours)
		{
			 info = info + '<span style="color:#666">&bull;</span> ' + update.behaviours[bhv] + '<br>';
		}
	}

	info = info +  '<br>' + update.description;

	infoPanel.innerHTML = info;

	if (update.sprite !== undefined)
	{
		update.sprite = spriteKey[update.sprite];
//		infoPanel.spriteDiv = spriteDiv(update.sprite);
		infoPanel.spriteDiv = bindingSpriteDiv(update.sprite, update.id);
		infoPanel.spriteDiv.className = infoPanel.spriteDiv.className + ' rah';
		infoPanel.appendChild(infoPanel.spriteDiv);

		infoPanel.style.minHeight = '<?php echo 4 * CHAR_HEIGHT; ?>px';
	}
	infoPanel.notify();
}

function colouriseNumber(value)
{
	value = value.replace('{r}', '<span style="color:#f88">');
	value = value.replace('{g}', '<span style="color:#8f8">');
	value = value.replace('{w}', '<span style="color:#ccc">');

	return value + '</span>';
}

//////////////////////////////////////
// MONEY
//////////////////////////////////////

function update_<?php echo UPD_MONEY; ?>(update)
{
	var moneyPanel	= document.getElementById("<?php echo UPD_MONEY; ?>");
	moneyPanel.innerHTML = '';

	for (index in update.currencies)
	{
		var currency = update.currencies[index];

		var currencyDiv = document.createElement('div');
		currencyDiv.innerHTML = currency.symbol;
		currencyDiv.title = currency.name;

		var amountSpan = document.createElement('span');
		amountSpan.className = 'ra';
		amountSpan.innerHTML = currency.amount;
		currencyDiv.appendChild(amountSpan);

//		subPanel = createSubPanel(currency.symbol, 'mon_'+currency.symbol, false);
//		subPanel.appendChild(currencyDiv);

		moneyPanel.appendChild(currencyDiv);
//		subPanel.appendTo(moneyPanel);
	}

	moneyPanel.notify();
}

//////////////////////////////////////
// AVAILABLE
//////////////////////////////////////

function update_<?php echo UPD_AVAILABLE; ?>(update)
{
	avPanel = document.getElementById("<?php echo UPD_AVAILABLE; ?>");
//	avPanel.panelHeader.annex.innerHTML = ' - ' + update.header;
	avPanel.setAnnex(update.header);
	updateInventory('<?php echo UPD_AVAILABLE; ?>', update, true);
//	avPanel.notify();
}

//////////////////////////////////////
// COMBAT
//////////////////////////////////////

var combatEvents = 20;

function update_<?php echo UPD_COMBAT; ?>(update)
{
	var combatPanel = document.getElementById("<?php echo UPD_COMBAT ?>");

//	update.events[0].body = '<br>' + update.events[0].body;

	for (index in update.events)
	{
		var event = update.events[index];

		var eventDiv = document.createElement('div');
		eventDiv.style.marginBottom = "<?php echo CHAR_HEIGHT; ?>px";
		eventDiv.innerHTML = event.body;

		combatPanel.insertBefore(eventDiv, combatPanel.firstChild);
	}

	if (combatPanel.childNodes.length > combatEvents)
	{
		var events = [].slice.call(combatPanel.childNodes, 0, combatEvents);
		combatPanel.innerHTML = '';
		for (index in events)
		{
			combatPanel.appendChild(events[index]);
		}
	}

	combatPanel.scrollTop = combatPanel.scrollHeight;
	combatPanel.notify();
}

//////////////////////////////////////
// JAVASCRIPT KEYS
//////////////////////////////////////

function update_<?php echo UPD_JVS_KEYS; ?>(update)
{
	var playerSprite = spriteKey['player'];
	tileKey = update['tileKey'];
	spriteKey = update['spriteKey'];
	spriteKey['player'] = playerSprite;
	document.getElementById('style').innerHTML = update.css;
	mapName = update['mapName'];
}

//////////////////////////////////////
// PLAYER HP
//////////////////////////////////////

function update_<?php echo UPD_HP; ?>(update)
{
	var barHeight = Math.round((10 / update.hpMax) * update.hp);
	document.getElementById('hp').style.height = (barHeight * CHAR_HEIGHT) + 'px';
	document.getElementById('hpLabel').innerHTML = update.hp + ' / ' + update.hpMax;
}

function update_<?php echo UPD_EP; ?>(update)
{
	var barHeight = Math.round((10 / update.epMax) * update.ep);
	document.getElementById('ep').style.height = (barHeight * CHAR_HEIGHT) + 'px';
	document.getElementById('epLabel').innerHTML = update.ep + ' / ' + update.epMax;
}

//////////////////////////////////////
// PLAYER XP
//////////////////////////////////////

function update_<?php echo UPD_XP; ?>(update)
{
	var bar = document.getElementById('<?php echo UPD_XP; ?>_bar');

	bar.maxValue = update.xpMax;
	bar.update(update.xp);

	if (update.lvl)
	{
		var charLevel  = document.getElementById('charLevel');
		charLevel.innerHTML = update.lvl;
	}
}

//////////////////////////////////////
// OPPONENT STATUS
//////////////////////////////////////

function update_<?php echo UPD_OPPONENT; ?>(update)
{
	var panel = document.getElementById('<?php echo UPD_OPPONENT; ?>');
	panel.innerHTML = '';

	var nameDiv = document.createElement('div');
	nameDiv.innerHTML = update.name + ' <span class="fade ra">(' + update.lvl + ')</span>';

	panel.appendChild(nameDiv);
	panel.appendChild(document.createTextNode('Life:'));

	panel.appendChild(drawBar('oppHp', update.hp, update.hpMax, '#62a'));

	if (update.statuses)
	{
		var statusDiv = document.createElement('div');
		panel.appendChild(statusDiv);

		for (var index in update.statuses)
		{
			var sts = spriteDiv(spriteKey[update.statuses[index].spr]);
			sts.style.display = 'inline-block';
			sts.style.marginRight = '<?php echo CHAR_WIDTH; ?>px';
			sts.title = update.statuses[index].desc;

			statusDiv.appendChild(sts);
		}
	}

	var hcDiv = getSplitDiv('Chance to hit:', update.hc + '%');
	hcDiv.title = 'Bias: ' + update.hcB;

	var dcDiv = getSplitDiv('Chance to dodge:', update.dc + '%');
	dcDiv.title = 'Bias: ' + update.dcB;

	var ccDiv = getSplitDiv('Chance to crit:', update.cc + '%');
	ccDiv.title = 'Bias: ' + update.ccB;

	panel.appendChild(hcDiv);
	panel.appendChild(dcDiv);
	panel.appendChild(ccDiv);
}

//////////////////////////////////////
// PLAYER STATS
//////////////////////////////////////

function update_<?php echo UPD_STATS; ?>(update)
{
	if (update.rebuild)
	{
		for (var DS_base in DS_types_core)
		{
			var panel = document.getElementById("statType_" + DS_base);
			panel.innerHTML = '';
//
//			panel.panelHeader.annex.innerHTML = '&nbsp;' + update.deep[DS_base];

			panel.appendChild(getStatThing(update, DS_base));

			for (var index in DS_types_core[DS_base])
			{
				var DS = DS_types_core[DS_base][index];
				panel.appendChild(getStatThing(update, DS));
			}

			for (var index in DS_types_subs[DS_base])
			{
				var DS = DS_types_subs[DS_base][index];
				panel.appendChild(getStatThing(update, DS, true));
			}
		}
	}
	else
	{
		for (DS in update.shallow)
		{
			var statDiv = document.getElementById('stat_' + DS);

			statDiv.parentNode.insertBefore(getStatThing(update, DS), statDiv);
			statDiv.parentNode.removeChild(statDiv);
		}
	}
}

function getStatThing(update, DS, fade)
{
	var statDiv = document.createElement('div');
	var span = document.createElement('span');

	statDiv.className = 'statDiv cap' + (fade ? ' fade' : '');
	statDiv.id = 'stat_' + DS;
	statDiv.title = DS_descriptions[DS];
	statDiv.appendChild(document.createTextNode(DS_names[DS]));

	var baseVal = update.shallow[DS];
	var diffVal = update.deep[DS] - update.shallow[DS];

	var workingsSpan = document.createElement('span');
	workingsSpan.className = 'statWorking';
	workingsSpan.innerHTML = baseVal + ' + ' + diffVal + ' = ';

	span.className = 'ra';
	span.appendChild(workingsSpan);
	span.appendChild(document.createTextNode(
		update.deep[DS]
	));

	statDiv.appendChild(span);

	return statDiv;
}

function update_<?php echo UPD_TECHNIQUE; ?> (update)
{
	for (var DS in DS_names)
	{
		var div = document.getElementById('stat_' + DS);

		if (!div) continue;

		div.title = DS_descriptions[DS];
		if (div.firstChild.className === 'teqBulls')
		{
			div.removeChild(div.firstChild);
		}

		var teqBulls = document.createElement('span');
		teqBulls.className = 'teqBulls';

		for (var TEQT in update)
		{
			TEQTarray = update[TEQT];

			if (TEQTarray[DS])
			{
				var teqBull = document.createElement('span');
				switch (TEQT)
				{
					case '<?php echo TEQT_MELEE; ?>':
						teqBull.style.color = '#f60';
						teqBull.innerHTML = '&#x25bc;';
						break;
					case '<?php echo TEQT_RANGED; ?>':
						teqBull.style.color = '#0f0';
						teqBull.innerHTML = '&#x25b2;';
						break;
					case '<?php echo TEQT_MAGIC; ?>':
						teqBull.style.color = '#0af';
						teqBull.innerHTML = '&#x2666;';
						break;
				}

				teqBulls.appendChild(teqBull);

				for (var TEQ in TEQTarray[DS])
				{
					div.title = div.title + ' | ' +
						(TEQTarray[DS][TEQ] * 100) + '% of this stat contributes to ' + TEQ_names[TEQ];
				}
			}
		}

		if (teqBulls.children.length > 0)
		{
			teqBulls.innerHTML = teqBulls.innerHTML + '&nbsp;';
			div.innerHTML = teqBulls.outerHTML + div.innerHTML;
		}
	}
}

//////////////////////////////////////
// DAMAGE / DEFENCE
//////////////////////////////////////

function update_<?php echo UPD_DMG_DEF; ?>(update)
{
	var ddPanel = document.getElementById("<?php echo UPD_DMG_DEF; ?>");
	ddPanel.innerHTML = 'Damage:';

	for (DMG in update.DMGs)
	{
		var div = getSplitDiv('&nbsp;&nbsp;' + DMG_names[DMG], update.DMGs[DMG]);
		div.title = DMG_names[DMG] + ' damage';

		ddPanel.appendChild(div);
	}
	ddPanel.appendChild(document.createTextNode('via ' + DMGDL_names[update.DMGDL]));
	ddPanel.appendChild(document.createElement('br'));
	ddPanel.appendChild(document.createElement('br'));

	var div = getSplitDiv('APS', update.APS);
	div.title = 'Attacks per second';
	ddPanel.appendChild(div);
	var div = getSplitDiv('DPS', update.DPS);
	div.title = 'Damage per second';
	ddPanel.appendChild(div);

	ddPanel.appendChild(document.createElement('br'));

	ddPanel.appendChild(document.createTextNode('Defence:'));



	var defended = false;
	for (DMG in update.DMGs_def)
	{
		var dmg_name = DMG_names[DMG] ? DMG_names[DMG] : DMGDL_names[DMG];
		var div = getSplitDiv('&nbsp;&nbsp;' + dmg_name, update.DMGs_def[DMG]);

		ddPanel.appendChild(div);
		defended = true;
	}

	if(!defended)
	{
		var div = document.createElement('div');

		div.className = 'fade';

		div.innerHTML = '&nbsp;None';

		ddPanel.appendChild(div);
	}

	ddPanel.notify();
}

//////////////////////////////////////
// STATUSES
//////////////////////////////////////

function update_<?php echo UPD_STATUS; ?>(update)
{
	var panel = document.getElementById("<?php echo UPD_STATUS; ?>");
	panel.innerHTML = '';

	for (var index in update)
	{
		var upd = update[index];
		var statusSprite = spriteKey[upd.sprite];
		var statusSpriteDiv = spriteDiv(statusSprite);
		var spriteString = '';

		statusSpriteDiv.className = statusSpriteDiv.className +  ' statusSprite';
		statusSpriteDiv.title = upd.description;

		if (panel.firstChild)
		{
			panel.insertBefore(statusSpriteDiv, panel.firstChild);
		}
		else
		{
			panel.appendChild(statusSpriteDiv);
		}
	}
}


//////////////////////////////////////
// QUESTS
//////////////////////////////////////

function update_<?php echo UPD_QUESTS; ?>(update)
{
	var questsPanel = document.getElementById("<?php echo UPD_QUESTS; ?>");
	var taskPanel = document.getElementById("<?php echo UPD_TASKS; ?>");

	for (var index in update.quests)
	{
		var quest = update.quests[index];
		var questPanelID = "<?php echo UPD_QUESTS; ?>_" + quest.name.replace(' ', '_');
		var questPanel = document.getElementById(questPanelID);

		if (questPanel === null)
		{
			questPanel = createSubPanel(quest.name, questPanelID);
			questPanel.appendTo(questsPanel);
		}

		questPanel.innerHTML = '';

		var descDiv = document.createElement('div');

		descDiv.appendChild(document.createTextNode(quest.desc));
		descDiv.style.marginBottom = '<?php echo CHAR_HEIGHT; ?>px';

		if (quest.complete) questPanel.setAnnex('Complete');
		if (quest.failed) questPanel.setAnnex('Failed');

		questPanel.appendChild(descDiv);

		if (taskPanel.children.length === 1 && taskPanel.firstChild.id === 'NPH')
		{
			taskPanel.removeChild(taskPanel.firstChild);
		}

		var emptyDiv = getNothingPlaceholder('None');
//		var emptyDiv = document.createElement('div');
//		emptyDiv.className = 'fade';
//		emptyDiv.innerHTML = 'None';
//		emptyDiv.id = 'NPH';

		for (var taskIndex in quest.tasks)
		{
			var task = quest.tasks[taskIndex];
			var taskDiv = document.createElement('div');
			taskDiv.id = questPanelID + taskIndex;

			var bullet = '&bull; ';
			if (task.comp)
			{
				bullet = task.fail ? '<span style="color:#f22">X</span> ' : '<span style="color:#0a0">&#x221a;</span> ';
			}

			if (task.actv)
			{
				if (task.fail) taskDiv.className = 'fail';
			}
			else
			{
				taskDiv.className = 'fade';
			}

			taskDiv.innerHTML = bullet + task.desc;

			questPanel.appendChild(taskDiv);

			var t_taskDiv = document.getElementById('t_' + taskDiv.id);
			if (!t_taskDiv)
			{
				if (task.actv)
				{
					var t_taskDiv = document.createElement('div');
					t_taskDiv.id = 't_' + taskDiv.id;
					t_taskDiv.innerHTML = taskDiv.innerHTML;
					t_taskDiv.className = taskDiv.className;

					taskPanel.appendChild(t_taskDiv);
				}
			}
			else
			{
				if (!task.actv)
				{
					t_taskDiv.parentNode.removeChild(t_taskDiv);
				}
			}
		}

		if (taskPanel.children.length < 1)
		{
			taskPanel.appendChild(emptyDiv);
		}

		if (quest.rwd)
		{
			var rewardDiv = document.createElement('div');
			rewardDiv.style.marginTop = '<?php echo CHAR_HEIGHT; ?>px';
			rewardDiv.innerHTML = 'Reward: ' + quest.rwd;
			if (quest.complete) rewardDiv.style.color = '#8f8';
			if (quest.failed) rewardDiv.className = 'fade';
			questPanel.appendChild(rewardDiv);
		}

		if (quest.pnlt)
		{
			var penaltyDiv = document.createElement('div');
			penaltyDiv.style.marginTop = '<?php echo CHAR_HEIGHT; ?>px';
			penaltyDiv.innerHTML = 'Penalty: ' + quest.pnlt;
			if (quest.failed) penaltyDiv.style.color = '#f88';
			if (quest.complete) penaltyDiv.className = 'fade';
			questPanel.appendChild(penaltyDiv);
		}

		if (update.notify) questPanel.notify();
	}

	if (update.completed)
	{
		var completedDiv = document.getElementById("<?php echo UPD_QUESTS_C; ?>");
		completedDiv.innerHTML = '';
		for (var questName in update.completed)
		{
			var compDiv = document.createElement('div');
			compDiv.appendChild(document.createTextNode(questName));
			compDiv.title = 'Completed: ' + update.completed[questName];
			completedDiv.appendChild(compDiv);
		}
	}
}

//////////////////////////////////////
// TASKS
//////////////////////////////////////
// This handler is different froms quests because it deals in updating individual
// task elements when their information changes in more specific ways
// (e.g., incrementing counts or something).

function update_<?php echo UPD_TASKS; ?>(update)
{
	for (var id in update.tasks)
	{
		var task = update.tasks[id];
		var taskDivId = '<?php echo UPD_QUESTS; ?>_' + task.qst.replace(' ', '_') + task.num;


		taskDiv = document.getElementById(taskDivId);

		if (taskDiv === null)
		{
			console_echo('Trying to update a task div where none exists :(', '#faa');
			return;
		}

		var bullet = task.comp ? '&#x221a; ' : '&bull; ';
		if (task.actv)
		{
			if (task.fail) taskDiv.className = 'fail';
		}
		else
		{
			taskDiv.className = 'fade';
		}
		taskDiv.innerHTML = bullet + task.desc;

		var t_taskDiv = document.getElementById('t_' + taskDivId);

		t_taskDiv.innerHTML = taskDiv.innerHTML;

	}
}

//////////////////////////////////////
// SKILLS
//////////////////////////////////////

function update_<?php echo UPD_SKILLS; ?> (update)
{
	var skillsDiv = document.getElementById("<?php echo UPD_SKILLS; ?>");

	skillsDiv.innerHTML = '';

	for (var index in update)
	{
		var skill = update[index];
		var string = '';
		if (skill.SKLS)
		{
			if (skill.SKLS > 9) skill.SKLS = 'M' + (skill.SKLS - 19);
			string = string + '<span class="bound">' + skill.SKLS + '</span> ';
		}
		string = string + skill.name + ' <span class="fade">(' + skill.level + ')</span>';
		createClickablePanelItem("<?php echo UPD_SKILLS; ?>", index, string);
	}
}

function update_<?php echo UPD_PASSIVES; ?> (update)
{
	var passivesDiv = document.getElementById("<?php echo UPD_PASSIVES; ?>");

	passivesDiv.innerHTML = '';

	for (var index in update)
	{
		var passive = update[index];
		string = passive.name + ' <span class="fade">(' + passive.level + ')</span>';
		createClickablePanelItem("<?php echo UPD_PASSIVES; ?>", index, string);
	}
}

function update_<?php echo UPD_SKILL_INFO; ?> (update)
{
	var infoDiv = document.getElementById("<?php echo UPD_SKILL_INFO; ?>");
	infoDiv.innerHTML = '';

	// NAME & LEVEL

	var nameDiv = document.createElement('div');
	nameDiv.appendChild(document.createTextNode(update.name));

	var levelSpan = document.createElement('span');
	levelSpan.className = 'fade';
	levelSpan.appendChild(document.createTextNode(' (' + update.level + ')'));

	nameDiv.appendChild(levelSpan);

	infoDiv.appendChild(nameDiv);

	// DESCRIPTION

	var descriptionDiv = document.createElement('div');
	descriptionDiv.style.marginTop = "<?php echo CHAR_HEIGHT; ?>px";
	descriptionDiv.style.marginRight = "<?php echo CHAR_WIDTH * 5; ?>px";
	descriptionDiv.appendChild(document.createTextNode(update.desc));

	infoDiv.appendChild(descriptionDiv);

	// Sprite

//	var skillSpriteDiv = spriteDiv(spriteKey[update.sprite]);
	var skillSpriteDiv = bindingSpriteDiv(spriteKey[update.sprite], update.id);
	skillSpriteDiv.className = skillSpriteDiv.className + ' rah';

	infoDiv.appendChild(skillSpriteDiv);

	// COSTS

	var costDiv = document.createElement('div');
	costDiv.style.marginTop = "<?php echo CHAR_HEIGHT; ?>px";
	costDiv.appendChild(document.createTextNode('Costs'));

	if (update.ep)
	{
		var epDiv = document.createElement('div');
		epDiv.appendChild(document.createTextNode('EP: '));

		var epValSpan = document.createElement('span');
		epValSpan.style.color = "#fd6";
		epValSpan.appendChild(document.createTextNode(update.ep));

		epDiv.appendChild(epValSpan);

		costDiv.appendChild(epDiv);
	}
	if (update.hp)
	{
		var hpDiv = document.createElement('div');
		hpDiv.appendChild(document.createTextNode('HP: '));

		var hpValSpan = document.createElement('span');
		hpValSpan.style.color = "#f66";
		hpValSpan.appendChild(document.createTextNode(update.hp));

		hpDiv.appendChild(hpValSpan);

		costDiv.appendChild(hpDiv);
	}
	if (update.cooldown)
	{
		var cdDiv = document.createElement('div');
		cdDiv.appendChild(document.createTextNode('cooldown: '));

		var cdValSpan = document.createElement('span');
		cdValSpan.style.color = "#6cf";
		cdValSpan.appendChild(document.createTextNode(update.cooldown));

		cdDiv.appendChild(cdValSpan);
		cdDiv.appendChild(document.createTextNode(' seconds'));

		costDiv.appendChild(cdDiv);
	}

	infoDiv.appendChild(costDiv);

	if (update.range)
	{
		var rangeDiv = document.createElement('div');
		rangeDiv.style.marginTop = "<?php echo CHAR_HEIGHT; ?>px";

		rangeDiv.appendChild(document.createTextNode('Range: ' + update.range + ' paces'));

		infoDiv.appendChild(rangeDiv);
	}

	infoDiv.notify();
}

function update_<?php echo UPD_PASSIVE_INFO; ?> (update)
{
	var infoDiv = document.getElementById("<?php echo UPD_SKILL_INFO; ?>");
	infoDiv.innerHTML = '';

	// NAME & LEVEL

	var nameDiv = document.createElement('div');
	nameDiv.appendChild(document.createTextNode(update.name));

	var levelSpan = document.createElement('span');
	levelSpan.className = 'fade';
	levelSpan.appendChild(document.createTextNode(' (' + update.level + ') passive'));

	nameDiv.appendChild(levelSpan);

	infoDiv.appendChild(nameDiv);

	// DESCRIPTION

	var descriptionDiv = document.createElement('div');
	descriptionDiv.style.marginTop = "<?php echo CHAR_HEIGHT; ?>px";
	descriptionDiv.style.marginRight = "<?php echo CHAR_WIDTH * 5; ?>px";
	descriptionDiv.appendChild(document.createTextNode(update.desc));

	infoDiv.appendChild(descriptionDiv);

	// Sprite

//	var skillSpriteDiv = spriteDiv(spriteKey[update.sprite]);
	var skillSpriteDiv = spriteDiv(spriteKey[update.sprite]);
	skillSpriteDiv.className = skillSpriteDiv.className + ' rah';

	infoDiv.appendChild(skillSpriteDiv);

	infoDiv.notify();
}

//////////////////////////////////////
// BINDINGS
//////////////////////////////////////

function update_<?php echo UPD_BINDINGS; ?> (update)
{
	if (bindingID !== null) { stopBindingMode(); }

	var bindingPanel = document.getElementById("<?php echo UPD_BINDINGS; ?>");
	bindingPanel.innerHTML = '';

	var mouseBindings = document.createElement('div');
//	mouseBindings.className = 'ra';
	mouseBindings.style.float = 'right';
	bindingPanel.appendChild(mouseBindings);

	var zeroBinding;

//	for (var skls = 0; skls <= 11; skls ++)
	for (var sklsIndex in SKLS_values)
	{
		var skls = SKLS_values[sklsIndex];
		var bindingDiv = document.createElement('div');

		if (skls === 0) zeroBinding = bindingDiv;

		bindingDiv.className = 'binding';
		bindingDiv.SKLS = skls;

		bindingDiv.onclick = binding__onclick;
		bindingDiv.oncontextmenu = binding__oncontextmenu;

		var numDiv = document.createElement('div');
		if (skls > 9)
		{
			numDiv.appendChild(document.createTextNode(skls === UIN_CLICK ? 'M1' : 'M2'));
		}
		else
		{
			numDiv.style.paddingLeft = "<?php echo CHAR_WIDTH; ?>px";
			numDiv.appendChild(document.createTextNode(' ' + skls));
		}
		bindingDiv.appendChild(numDiv);

		if (update[skls] !== undefined)
		{
			var binding = update[skls];

			bindingDiv.appendChild(spriteDiv(spriteKey[binding.sprite]));
			bindingDiv.title = binding.desc;
		}
		else
		{
			var emptySprite = document.createElement('div');

			emptySprite.className = 'emptyBindingSprite';
			emptySprite.innerHTML = '\\V/<br>/&#X039B;\\';
			emptySprite.title = 'To bind a skill or item to this slot, inspect the item or skill then click on its sprite. Assign the sprite to the slot you wish to bind it to.';

			bindingDiv.appendChild(emptySprite);
		}

		var openSprite = document.createElement('div');

		openSprite.className = 'openBindingSprite';
		openSprite.innerHTML = '/&#X00AF;\\<br>\\_/';
		bindingDiv.appendChild(openSprite);

		if (skls > 9)
		{
			mouseBindings.appendChild(bindingDiv);
		}
		else bindingPanel.appendChild(bindingDiv);
	}

	bindingPanel.appendChild(zeroBinding);
}

function binding__onclick ()
{
	notifyServer('<?php echo UPD_BINDINGS; ?>', (ctrlDown ? UIN_CTRL_CLICK : UIN_CLICK), this.SKLS);
}

function binding__oncontextmenu ()
{
	notifyServer('<?php echo UPD_BINDINGS; ?>', (ctrlDown ? UIN_CTRL_RIGHT_CLICK : UIN_RIGHT_CLICK), this.SKLS);
}

function binding__onclick_bind ()
{
	notifyServer('BIND', UIN_CLICK, this.SKLS + ',' + bindingID);
	stopBindingMode();
}

///////////////////////////////////////
// BOONS
///////////////////////////////////////

function update_<?php echo UPD_BOONS; ?> (update)
{
	var panel = document.getElementById('<?php echo UPD_BOONS; ?>');
	panel.innerHTML = '';

	var pendingDiv = document.createElement('div');
	pendingDiv.innerHTML = "Pending: " + update.pending + "<br><br>";
	panel.appendChild(pendingDiv);

	for (var i in update.boons)
	{
		var boonDiv = createClickablePanelItem('<?php echo UPD_BOONS; ?>', update.boons[i].key, update.boons[i].name);
		boonDiv.title = update.boons[i].desc;
	}
}

///////////////////////////////////////
// SOUNDS
///////////////////////////////////////

function update_<?php echo UPD_SOUNDS; ?> (update)
{
	for (index in update)
	{
		var path = SOUND_PATH + SND_files[update[index]];

		var sound = new Audio();
		sound.src = path;
		sound.play();
	}
}

///////////////////////////////////////
// INTERACTIONS
///////////////////////////////////////

function update_<?php echo UPD_INTERACTIONS; ?> (update)
{
	if (update.type === undefined) // If it doesn't have a type, it must be an array up updates. Shakey but hopefully workable?
	{
		for (var index in update)
		{
			update_<?php echo UPD_INTERACTIONS; ?> (update[index]);
		}
		return;
	}

	var updateType = update.type ? update.type : false;
	var panel = document.getElementById('<?php echo UPD_INTERACTIONS; ?>');

	if (update.type && update.gentle && !document.getElementById(update.type)) return;

	switch (updateType)
	{
		case 'init':
			panel.subPanel = undefined;
			panel.innerHTML = '';

			var notify = false;

			for(var key in update.NPCIs)
			{
				notify = true;
				var interaction = update.NPCIs[key];

				var panelItem = createClickablePanelItem(panel.id, interaction.key, interaction.name);
				panelItem.id = interaction.key + '_choice';
				panelItem.title = update.description;
			}

			if (notify) panel.notify();

			break;
		case 'npci_buy':
			var subPanel = getInteractionSubPanel(updateType, 'Buy');
			updateInventory(updateType, update, true, panel.id, updateType + '#');
			break;
		case 'npci_sell':
			var subPanel = getInteractionSubPanel(updateType, 'Sell');
			updateInventory(updateType, update, true, panel.id, updateType + '#');
			break;
		case 'npci_exchange':

			var subPanel = getInteractionSubPanel(updateType, 'Exchange');
			subPanel.innerHTML = '';

			for (var index in update.currencies)
			{
				var currency = update.currencies[index];

				var panelString = 'Exchange your ' + currency.name + ' <span class="yellow">' + currency.sym + '</span>';
				subPanel.appendChild(createClickablePanelItem(panel.id, subPanel.id + '#' + currency.CUR, panelString));
			}

			if (!subPanel.children.length)
			{
				subPanel.appendChild(getNothingPlaceholder('No tradable currencies'));
			}

			break;
		case 'npci_exchange_CUR':

			var subPanel = getInteractionSubPanel(updateType, 'Exchange');
			subPanel.innerHTML = '';

			for (var index in update.currencies)
			{
				var currency = update.currencies[index];

				var panelString = '<span class="yellow">' + update.sym + '</span> ' + currency.amount_in + ' &#x2192; <span class="yellow">' + currency.sym + '</span> ' + currency.amount_out;
				var titleString = 'Exchange ' + currency.amount_in + ' ' + update.name + ' for ' + currency.amount_out + ' ' + currency.name;

				var cpi = createClickablePanelItem(panel.id, 'npci_exchange' + '#' + update.CUR + '#' + currency.CUR + '#' + currency.amount_in, panelString);
				cpi.title = titleString;

				subPanel.appendChild(cpi);
			}

			if (!subPanel.children.length)
			{
				subPanel.appendChild(getNothingPlaceholder());
			}
			break;

		case 'npci_repair':
			var subPanel = getInteractionSubPanel(updateType, 'Repair');

			update.eff = parseInt(update.eff);

			if (update.eff && update.items.length > 0)
			{
				subPanel.innerHTML = '';
				var header = document.createElement('div');
				header.className = 'red';
				header.appendChild(document.createTextNode('Repair efficiency: ' + update.eff + '%'));
				header.title = update.eff + '% of the item\'s damage is repaired; ' + (100 - update.eff) + '% of the damage becomes permanent.';

				subPanel.appendChild(header);
			}

			for (var index in update.items)
			{
				var item = update.items[index];

				if (item.delete)
				{
					var itemDiv = document.getElementById(updateType + '#' + item.id);
					if (itemDiv) subPanel.removeChild(itemDiv);
					continue;
				}

				var lhs = item.name;
				var rhs = '<span class="fade">(' + item.dur + '/' + item.durMax + ')</span> <span class="yellow">' + update.curSym + '</span> ' + item.price;

				var content = getSplitDiv(lhs, rhs).innerHTML;

				var itemDiv = createClickablePanelItem(
						panel.id,
						updateType + '#' + item.id,
						content
					);

				subPanel.appendChild(itemDiv);
			}

			if (!subPanel.children.length)
			{
				subPanel.appendChild(getNothingPlaceholder('No damaged items'));
			}

			break;
	}
}

function getInteractionSubPanel (id, header)
{
	var interactionsPanel = document.getElementById('<?php echo UPD_INTERACTIONS; ?>');

	if (interactionsPanel.subPanel)
	{
		if (interactionsPanel.subPanel.id === id) return interactionsPanel.subPanel;
		interactionsPanel.subPanel.remove();
	}

	var panel = createSubPanel(header, id);
	panel.appendTo(interactionsPanel);
	interactionsPanel.subPanel = panel;

	return panel;
}























































































///////////////////////////////////////
// HELPERS
///////////////////////////////////////

function getSplitDiv(lhs, rhs)
{
	var div = document.createElement('div');
	var valSpan = document.createElement('span');

	div.className = 'cap';
	valSpan.className = 'ra';

	div.innerHTML = lhs;
	valSpan.innerHTML = rhs;

	var rhs_width = getTextLength(valSpan.innerHTML);
	var lhs_width = getTextLength(div.innerHTML);

	var max_lhs_width = WING_WIDTH - rhs_width;

	if (max_lhs_width < lhs_width)
	{

	}

	div.appendChild(valSpan);

	return div;
}


function getTextLength(text)
{
	var container = document.createElement('div');

	container.innerHTML = text;

	return container.innerText.length;
}

function truncateText(text, maxLength)
{
	if (text.length <= maxLength) return text;

	return text.substring(0, maxLength - 1).trim() + '&#x2026;';
}

function drawBar(id, value, max, colour)
{
	var bar		= document.createElement('div');
	bar.slider	= document.createElement('div');
	bar.value	= document.createElement('div');

	if (value > max) value = max;
	if (value < 0) value = 0;

	bar.id = id + '_bar';
	bar.className = 'bar';
	bar.maxValue = max;

	bar.slider.id = id + '_sld';
	bar.slider.style.backgroundColor = colour;
	bar.slider.style.width = (Math.ceil(<?php echo WING_WIDTH; ?> * (value / max)) * <?php echo CHAR_WIDTH; ?>) + 'px';

	bar.value.id = id + '_bvl';
	bar.value.style.marginLeft = '<?php echo CHAR_WIDTH; ?>px';
	bar.value.innerHTML = (Math.round(value * 10) / 10) + ' / ' + max; // Oosenupt - this might be bad and mask errors. If stuff is dying with 0.1 health, check this first.

	bar.appendChild(bar.slider);
	bar.appendChild(bar.value);

	bar.update = bar__update;

	return bar;
}

function bar__update(value)
{
	if (value > this.maxValue) value = this.maxValue;
	if (value < 0) value = 0;

	this.slider.style.width = (Math.ceil(<?php echo WING_WIDTH; ?> * (value / this.maxValue)) * <?php echo CHAR_WIDTH; ?>) + 'px';

	this.value.innerHTML = (Math.round(value * 10) / 10) + ' / ' + this.maxValue; // Oosenupt - this might be bad and mask errors. If stuff is dying with 0.1 health, check this first.
}

function updateInventory(panelId, update, flat, notificationKey, contentPrefix)
{
	if (flat === undefined) flat = false;
	if (contentPrefix === undefined) contentPrefix = '';
	if (notificationKey === undefined) notificationKey = panelId;

	var inventoryPanel	= document.getElementById(panelId);

	if (inventoryPanel.children.length === 1 && inventoryPanel.firstChild.id === 'NPH')
	{
		inventoryPanel.removeChild(inventoryPanel.firstChild);
	}

	var emptyDiv = document.createElement('div');
	emptyDiv.className = 'fade';
	emptyDiv.innerHTML = 'Nothing';
	emptyDiv.id = 'NPH';

	var subInvs = new Array();
	if (update.refresh) inventoryPanel.innerHTML = '';

	for (var itemIndex in update.items)
	{
		var item = update.items[itemIndex];
		var existingDiv = document.getElementById(panelId + '_' + item.id);

		if (item.delete)
		{
			existingDiv.parentNode.removeChild(existingDiv);
			continue;
		}

		var toolTip = item.name;

		var prefix = '';
		var suffix = '';

		if (item.SKLS)		prefix = prefix + '<span class="bound">' + item.SKLS + '</span>';
		if (item.equipped)	prefix = prefix + '<span class="fade">&#x25ba;</span>';

		prefix = prefix + ' ';

		var details = ''; // Level and/or quantitiy

		if (item.level && item.price === undefined)	details = details + ' (' + item.level + ')';
		if (item.quantity > 1)						details = details + ' &#x00D7;' + item.quantity;

		if (details !== '')				suffix = suffix + '<span class="fade">' + details + '</span>';
		if (item.price !== undefined)	suffix = suffix + '<span class="ra yellow">' + update.CURsym + '&nbsp;' + item.price + '</span>';

		var maxWidth = WING_WIDTH - getTextLength(suffix) - getTextLength(prefix) - 1; // the -1 is for the space between the ellipsis and the price.

		var displayName = prefix + truncateText(item.name, maxWidth) + suffix;

		var panelItem = createClickablePanelItem(
			notificationKey,
			contentPrefix + item.id,
			displayName
		);

		panelItem.id = panelId + '_' + item.id;
		panelItem.title = toolTip;

		if (item.INV && !flat)
		{
			var subInvId = 'INV_' + panelId + '_' + item.INV;
			// Get the panel that is the sub-inventory for this time type.
			var subInv = document.getElementById(subInvId);
			if (!subInv)
			{
				subInv = createSubPanel(item.INV, subInvId);
				subInv.appendTo(inventoryPanel);
				subInvs.push(subInv);
			}
			subInv.insertBefore(panelItem, subInv.firstChild);
		}
		else
		{
			inventoryPanel.insertBefore(panelItem, inventoryPanel.firstChild);
		}

		if (existingDiv)
		{
			existingDiv.parentNode.insertBefore(panelItem, existingDiv);
			existingDiv.parentNode.removeChild(existingDiv);
		}
	}

	for (var index = 0; index < subInvs.length; index++)
	{
		subInvs[index].appendTo(inventoryPanel);
	}

	if (inventoryPanel.children.length < 1)
	{
		inventoryPanel.appendChild(emptyDiv);
	}
	else
	{
		inventoryPanel.notify();
	}

}

function spriteDiv(sprite)
{
	var spriteDiv		= document.createElement('div');
	spriteDiv.className	= 'sprite' + (sprite['f'] > 1 ? '' : '_s'); // If the sprite only has one frame, change the class so it doesn't get updated.
	spriteDiv.sprite	= sprite;
	spriteDiv.frame		= 0;
	spriteDiv.id		= 'spriteDiv';

	spriteDiv.update = spriteDiv__update;

	spriteDiv.update();

	if (sprite['f'] <= 1) spriteDiv.update = null;

	return spriteDiv;
}

function spriteDiv__update()
{
	var spriteString = '';

	for (var i = 0; i <= 5; i++)
	{
		var frameKey = 'f' + this.frame % this.sprite['f'];

		if (this.sprite[frameKey]['e' + i.toString()] !== undefined && this.sprite[frameKey]['e' + i.toString()] !== '')
		{
			spriteString = spriteString + this.sprite[frameKey]['e' + i.toString()];
		}
		else
		{
			spriteString = spriteString + '&nbsp;';
		}

		if (i === 2) spriteString  = spriteString + '<br>';
	}
	this.innerHTML = spriteString;
	this.frame++;
}

function updateSpriteDivs()
{
	var spriteDivs = document.getElementsByClassName('sprite');

	for (var i = 0; i < spriteDivs.length; i++)
	{
		spriteDivs[i].update();
	}
}

function bindingSpriteDiv (sprite, id)
{
	var div = spriteDiv(sprite);

	div.sprite = sprite;
	div.bindId = id;
	div.onclick = bindingSpriteDiv__onclick;

	return div;
}

function bindingSpriteDiv__onclick ()
{
	if (bindingID === null)
	{
		startBindingMode(this.bindId);
		setCursorSprite(this.sprite);
	}
	else
	{
		stopBindingMode();
		clearCursorSprite();
	}
}

//////////////////////////////////////
// EDITOR BEYOND THIS POINT
//////////////////////////////////////

function update_saveStatus(update)
{
	document.getElementById('saveStatus').innerHTML = update;
	setTimeout('document.getElementById("saveStatus").innerHTML = "";', 3000);
}

function update_clipboard(update)
{
	document.getElementById('clipboard').innerHTML = update;
}

//////////////////////////////////////
// EDITOR2
//////////////////////////////////////

function update_<?php echo UPD_EDITOR_SKILLS; ?> (update)
{
	var assetsDiv = document.getElementById('assets');

	var sceneryGapSwitch = false;
	var toolGapSwitch = false;

	assetsDiv.innerHTML = '';

	var tileHeader = document.createElement('div');
	tileHeader.className = 'editorSubHeader';
	tileHeader.innerHTML = 'TILES';
	assetsDiv.appendChild(tileHeader);

	var type;

	for (var skill in update)
	{
		skill = update[skill];

		type = skill.type;
		var sprite = spriteKey[skill.sprite];

		var sprite = bindingSpriteDiv(sprite, skill.key);
		sprite.id = skill.key;
		sprite.oncontextmenu = editorAssetRightClick;

		sprite.className = sprite.className + ((skill.type === 'skl_EDITOR_placeAsset') ? ' editorAsset' : ' editorTool');

		if (skill.class)
		{
			sprite.className = sprite.className + skill.class;
		}
		else
		{

			if (!sceneryGapSwitch)
			{
				assetsDiv.appendChild(sprite);
				sceneryGapSwitch = true;
				var sceneryHeader = document.createElement('div');
				sceneryHeader.className = 'editorSubHeader';
				sceneryHeader.innerHTML = 'SCENERY';
				assetsDiv.appendChild(sceneryHeader);
				continue;
			}
		}





		if (skill.type !== 'skl_EDITOR_placeAsset' && !toolGapSwitch)
		{
			toolGapSwitch = true;
			var toolHeader = document.createElement('div');
			toolHeader.className = 'editorSubHeader';
			toolHeader.innerHTML = 'TOOLS';
			assetsDiv.appendChild(toolHeader);
		}

		assetsDiv.appendChild(sprite);
	}
}

function editorAssetRightClick ()
{
	notifyServer('<?php echo UPD_SKILLS; ?>', UIN_RIGHT_CLICK, this.id);
}

function update_<?php echo UPD_EDITOR_CLIPBOARD ?> (update)
{
	var panel = document.getElementById(update.tiles ? 'tileClipboard' : 'sceneryClipboard');

	panel.innerHTML = update.body;
}