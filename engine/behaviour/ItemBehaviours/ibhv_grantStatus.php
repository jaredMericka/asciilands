<?php

class ibhv_grantStatus extends ItemBehaviour // Oosenupt - incomplete
{
	public $status;

	public function __construct (Status $status, $cooldown = 0)
	{
		$description = '';

		if ($status->DSs)
		{
			foreach ($status->DSs as $DS => $value)
			{

			}
		}

		if ($status->DMGs)
		{

		}

		parent::__construct($description, $key, $cooldown);
	}
}