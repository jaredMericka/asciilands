<?php

abstract class NPCInteraction extends DudeBehaviour
{
	public $name;
	public $description;
	public $key;

	public $owner;

	public function __construct($name, $description)
	{
		$this->name			= $name;
		$this->description	= $description;
		$this->key			= get_class($this);

		parent::__construct($description, $this->key);
	}

	/**
	 * onInovoke should be triggered when the interaction is chosen.
	 * e.g., Clicking "buy" should trigger onInvoke(); clicking "steel sword" should trigger onUse().
	 */
//	abstract function onInvoke();
	function onClick($UIN) { }

	/**
	 * onUse should be triggered when the player triggers options made available by the interaction type.
	 * e.g., Clicking "buy" should trigger onInvoke(); clicking "steel sword" should trigger onUse().
	 */
//	abstract function onUse ();
	function onItemClick ($UIN, $content) { }


	// Other triggers

	function onRegister() { }

	function onGainItem(Item $item) { }
	function onLoseItem(Item $item) { }
}