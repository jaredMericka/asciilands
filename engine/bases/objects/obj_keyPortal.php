<?php

class obj_keyPortal extends obj_portal
{
	public function __construct($name,
		$spriteSet,
		$dest_n_offset,		$dest_w_offset,		$dest_map,
		$key_dest_n_offset,	$key_dest_w_offset,	$key_dest_map,
		$keyItem)
	{
		$this->addBehaviour(
			new obhv_addTeleporterWithKey($key_dest_n_offset, $key_dest_w_offset, $key_dest_map, $keyItem, $spriteSet[SPRI_ACTIVE])
		);

		parent::__construct($name, $spriteSet, $dest_n_offset, $dest_w_offset, $dest_map);
	}
}


