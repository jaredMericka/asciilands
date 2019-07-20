<?php

class MapDirectory
{
	public $mapIndex = [];

	public function addMap($mapName, $level)
	{
		console_echo("Adding new map to the map directory: <<#fff>>\"{$mapName}\"<>", '#aaf');

		$map = new Map($mapName, $level);

		if (!isset($this->mapIndex[$map->mapPath]))
			$this->mapIndex[$mapName] = [];

		if (!isset($this->mapIndex[$map->mapPath][$map->level]))
			$this->mapIndex[$map->mapPath][$map->level] = [];

		$this->mapIndex[$map->mapPath][$map->level][] = $map->id;

		$_SESSION[$map->id] = $map;

		console_echo("New map created with the ID: <<#afa>>\"{$map->id}\"<>", '#fff');

		return $map->id;
	}

	public function getMapId($mapName, $idealLevel, $minLevel = null, $maxLevel = null)
	{
		$affix = ($minLevel && $maxLevel) ? " but will accept one between <<#fda>>{$minLevel}<> and <<#fda>>{$maxLevel}<>" : ''; //XXX
		console_echo("Looking for a cached version of the map <<#faf>>\"{$mapName}\"<> at level <<#afa>>{$idealLevel}<>{$affix}.");

		if ($id = $this->retreiveMap($mapName, $idealLevel)) return $id;

		console_echo('Ideal map level no currently in cache.', '#faa');

		if ($minLevel && $maxLevel)
		{
			for ($level = $minLevel; $level <= $maxLevel; $level ++)
			{
				if ($id = $this->retreiveMap($mapName, $idealLevel)) return $id;
			}

			console_echo('No acceptable map levels in cache.', '#faa');
		}

		console_echo("Creating new version of map <<#fff>>\"{$mapName}\"<>.", '#aff');

		return $this->addMap($mapName, $idealLevel);
	}

	private function retreiveMap($mapName, $level)
	{
		if (isset($this->mapIndex[$mapName][$level]))
		{
			$matches = $this->mapIndex[$mapName][$level];

			return ($matches[array_rand($matches)]);
		}
		else
		{
			return false;
		}
	}
}