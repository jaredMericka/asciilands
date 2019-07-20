<?php if (false) { ?> <script> <?php }
header("content-type: application/javascript");

?>

function createTab(wingId)
{
	var wing = document.getElementById(wingId);

	var tabBar = document.createElement('div');
	tabBar.className = 'tabBar';
	wing.insertBefore(tabBar, wing.firstChild);

	wing.tabs = Array();
	wing.tabBodies = wing.getElementsByClassName('tabBody');

	for (var i = 0; i < wing.tabBodies.length; i++)
	{
		var tabBody = wing.tabBodies[i];
		var tab = document.createElement('div');

		tab.showing = false;
		tabBody.tab = tab;
		tab.tabBody = tabBody;
		tab.className = 'tab';
		tab.wing = wing;
		tab.onclick = tab__show;
		tab.show = tab__show;
		tab.innerHTML = tabBody.getAttribute('header');
		tab.id = tabBody.getAttribute('header');
		tab.title = tabBody.title;
		tabBody.title = '';

		tab.notification = document.createElement('span');
		tab.notification.className = 'notification';
		tab.notification.innerHTML = '!';
		tab.appendChild(tab.notification);
		tab.notify = tab__notify;

		wing.tabs.push(tab);
		tabBar.appendChild(tab);
	}

	wing.tabs[0].onclick();
}

function tab__show()
{
	for (var i = 0; i < this.wing.tabs.length; i++)
	{
		this.wing.tabs[i].tabBody.style.display = 'none';
		this.wing.tabs[i].setAttribute('selected', 'false');
		this.wing.tabs[i].isOpen = false;
	}
	this.tabBody.style.display = 'block';
	this.setAttribute('selected', 'true');
	this.isOpen = true;
	this.notification.style.display = 'none';

}

function tab__notify()
{
	if (!this.isOpen) this.notification.style.display = 'inline-block';
}

//function tab__clearNotifications()
//{
//	var panels = this.getElementsByClassName('panel');
//
//	for (var index in panels)
//	{
//		if (panels[index].isOpen) panels[index].notification.style.display = 'none';
//	}
//}

function createPanelHeaders()
{
	var panelArray = document.getElementsByClassName('panel');
	for (var i = 0; i < panelArray.length; i ++)
	{
		var panelBody		= panelArray[i];
		var panelHeader		= document.createElement('div');

		panelBody.isOpen	= true;
		panelBody.parent = panelBody.parentNode.tab; // Gross. If something goes wrong, check this first.
		panelBody.notify = panelBody__notify;

		panelHeader.className	= 'panelHeader';
		panelHeader.innerHTML	= panelBody.getAttribute('header');
		panelHeader.setAttribute('child', panelBody.getAttribute('id'));


		panelHeader.indicator			= document.createElement('span');
		panelHeader.indicator.innerHTML = '-';
		panelHeader.indicator.className	= 'indicator ra';
		panelHeader.indicator.id		= 'ind';
		panelHeader.appendChild(panelHeader.indicator);

		panelHeader.notification = document.createElement('span');
		panelHeader.notification.className = 'notification';
		panelHeader.notification.innerHTML = '!';
		panelHeader.appendChild(panelHeader.notification);


		panelHeader.annex			= document.createElement('span');
		panelHeader.annex.className	= 'annex';
		panelHeader.appendChild(panelHeader.annex);

		panelBody.setAnnex			= panelBody__setAnnex;
		panelBody.clearAnnex		= panelBody__clearAnnex;

		panelHeader.onclick			= panelHeader__toggle;


		panelBody.panelHeader = panelHeader;
		panelHeader.panelBody = panelBody;

		panelBody.parentNode.insertBefore(panelHeader, panelBody);
	}
}

function panelHeader__toggle()
{
	if (this.panelBody.isOpen)
	{
		this.panelBody.style.display = 'none';
		this.indicator.innerHTML = '+';
		this.panelBody.isOpen = false;
	}
	else
	{

		this.panelBody.style.display = 'block';
		this.indicator.innerHTML = '-';
		this.panelBody.isOpen = true;
		this.notification.style.display = 'none';
	}
}

function panelBody__notify()
{
	if (!this.isOpen) this.panelHeader.notification.style.display = 'inline-block';
//	if (!this.parent.isOpen) this.parent.notification.style.display = 'inline-block';
	this.parent.notify();
}

function panelBody__setAnnex(text)
{
	if (text) this.panelHeader.annex.innerHTML = ' - ' + text; // Oosenupt - untested changes (redirection to clear on empty)
	else this.clearAnnex();
}

function panelBody__clearAnnex()
{
	this.panelHeader.annex.innerHTML = '';
}

function createSubPanel(header, id, isOpen)
{
	var panelBody		= document.createElement('div');
	var panelHeader		= document.createElement('div');

	panelBody.isOpen	= true;
	panelBody.notify	= panelBody__notify;
	panelBody.id		= id;
	panelBody.className	= 'subPanel';


	panelHeader.className	= 'subPanelHeader';
	panelHeader.innerHTML	= header;
	panelHeader.setAttribute('child', id);

	panelHeader.indicator			= document.createElement('span');
	panelHeader.indicator.innerHTML = '-';
	panelHeader.indicator.className	= 'indicator ra';
	panelHeader.indicator.id		= 'ind';
	panelHeader.appendChild(panelHeader.indicator);

	panelHeader.notification = document.createElement('span');
	panelHeader.notification.className = 'notification';
	panelHeader.notification.innerHTML = '!';
	panelHeader.appendChild(panelHeader.notification);

	panelHeader.annex			= document.createElement('span');
	panelHeader.annex.className	= 'annex';

	panelHeader.appendChild(panelHeader.annex);

	panelBody.setAnnex			= panelBody__setAnnex;
	panelBody.clearAnnex		= panelBody__clearAnnex;

	panelHeader.onclick			= panelHeader__toggle;

	panelBody.panelHeader = panelHeader;
	panelHeader.panelBody = panelBody;


//	panelBody.parentNode.insertBefore(panelHeader, panelBody);
	panelBody.appendTo	= subPanelBody__appendTo;
	panelBody.remove	= subPanelBody__remove;

	if (isOpen === false)
	{
		panelHeader.onclick();
	}

	return panelBody;
}

function subPanelBody__appendTo(parentNode)
{
	parentNode.appendChild(this.panelHeader);
	parentNode.appendChild(this);
	this.parent = parentNode;
}

function subPanelBody__remove()
{
	this.panelHeader.parentNode.removeChild(this.panelHeader);
	this.parentNode.removeChild(this);
}

bindingID = null;

function startBindingMode(id)
{
	bindingID = id;

	var bindingSlots = document.getElementsByClassName('binding');

	for (var i = bindingSlots.length -1; i >= 0; i--)
	{
		bindingSlots[i].old_onclick = bindingSlots[i].onclick;
		bindingSlots[i].onclick = binding__onclick_bind;

		bindingSlots[i].className = bindingSlots[i].className + '_open';
	}
}

function stopBindingMode()
{
	bindingID = null;

	var bindingSlots = document.getElementsByClassName('binding_open');

	for (var i = bindingSlots.length -1; i >= 0; i--)
	{
		bindingSlots[i].onclick = bindingSlots[i].old_onclick;
		bindingSlots[i].old_onclick = null;

		bindingSlots[i].className = bindingSlots[i].className.replace('_open', '');
	}

	clearCursorSprite();
}

function setCursorSprite (sprite)
{
	cursorSprite.innerHTML = '';
	cursorSprite.appendChild(spriteDiv(sprite));
	document.body.style.cursor = 'none';
}

function clearCursorSprite ()
{
	cursorSprite.innerHTML = '';
	document.body.style.cursor = null;
}

function createClickablePanelItem(panelId, itemKey, content, className)
{
	panelItem = document.createElement('div');
	panel = document.getElementById(panelId);

	panelItem.id = itemKey;
	panelItem.onclick = function() { notifyServer(panelId, 100 + (ctrlDown ? 10 : 0) + (altDown ? 1 : 0), itemKey); };
	panelItem.oncontextmenu = function() { notifyServer(panelId, 200 + (ctrlDown ? 10 : 0) + (altDown ? 1 : 0), itemKey); };
	panelItem.innerHTML = content;

	if (className !== undefined) panelItem.className = 'pi ' + className;
	else panelItem.className = 'pi';

	panel.appendChild(panelItem);

	return panelItem;
}

function getNothingPlaceholder (text, id)
{
	if (!text) text = 'Nothing';
	if (!id) id = 'NPH';

	var emptyDiv = document.createElement('div');
	emptyDiv.className = 'fade';
	emptyDiv.innerHTML = text;
	emptyDiv.id = id;

	return emptyDiv;
}