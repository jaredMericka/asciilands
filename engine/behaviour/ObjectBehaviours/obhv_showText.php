<?php

class obhv_showText extends ObjectBehaviour
{
	public $title;
	public $text;

	public $bg;
	public $fg;

	public $forcedHeight;

	public function __construct($title, $text, $bg = null, $fg = null, $forcedHeight = null)
	{
		$this->onEngage = true;
		$this->onDisengage = true;

		$this->title = $title;
		$this->text = $text;

		$this->bg = ($bg ? $bg : '#ffd');
		$this->fg = ($fg ? $fg : '#000');

		$this->forcedHeight = $forcedHeight;

		$description = "Show {$title} text.";

		parent::__construct($description, BHVK_SHOWTEXT);
	}

	public function onEngage(Player $player)
	{
		update_text($this->title, $this->text, $this->bg, $this->fg, $this->forcedHeight);
	}

	public function onDisengage(Player $player)
	{
		console_echo('Clearing sign on disengage.', '#aff');		//XXX
		clearPanel(UPD_TEXT);
	}
}