<?php

class ibhv_read extends ItemBehaviour
{
	public $text;
	public $bg;
	public $fg;
	public $forcedHeight;
	public $forcedWidth;

	public function __construct($text, $bg = null, $fg = null, $forcedHeight = null, $forcedWidth = null)
	{
		$this->onUse		= true;

		$this->goldValue = 2;

		$this->text			= $text;
		$this->bg			= $bg ? $bg : '#ffa';
		$this->fg			= $fg ? $fg : '#111';
		$this->forcedHeight	= $forcedHeight;
		$this->forcedWidth	= $forcedWidth;

		$description = "Displays text.";
		parent::__construct($description, BHVK_TEXT);
	}

	public function onUse()
	{
		console_echo('Reading a thing');
		update_text($this->owner->name, $this->text, $this->bg, $this->fg, $this->forcedHeight);
	}
}