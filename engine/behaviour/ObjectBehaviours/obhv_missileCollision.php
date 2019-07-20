<?php

class obhv_missileCollision extends ObjectBehaviour
{
	public $spawner;

	public $attack;
	public $dbhv_takeDamageOverTime;
	public $status;

	public function __construct($spawner, $attack = null, $dbhv_takeDamageOverTime = null, $status = null)
	{
		$this->onCollision = true;
		$this->onReaction = true;

		$this->spawner = $spawner;

		$this->attack = $attack;
		$this->dbhv_takeDamageOverTime = $dbhv_takeDamageOverTime;
		$this->status = $status;

		parent::__construct('Deploys an attack on collision.', BHVK_PRIMARY);
	}

	public function onCollision(AsObject $receiver, $DIR)		{ $this->deploy($receiver, $DIR); }
	public function onReaction(AsObject $instigator, $DIR)	{ $this->deploy($instigator, $DIR); }

	public function deploy (AsObject $object, $DIR)
	{

		if ($object instanceof Dude)
		{
			if ($this->attack)					$this->attack->execute($object);
			if ($this->dbhv_takeDamageOverTime)	$object->addBehaviour($this->dbhv_takeDamageOverTime);
			if ($this->status)					$object->addStatus($this->status);
		}

		if ($object->layer === LAYER_DUDE) $this->owner->destroy();
	}
}