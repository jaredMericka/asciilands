<?php

class MapDirectory_session
{
	/**
	 * Adds a map to the map pool and stores its metadata in the MapDirectory.
	 *
	 * @param type $mapName
	 * @param type $level
	 * @return \Map
	 */
	public function addMap($mapName, $level)
	{
		console_echo("Adding new map to the map directory: <<#fff>>\"{$mapName}\"<>", '#aaf');

		$map = new Map($mapName, $level);

		$_SESSION[$map->id] = serialise($map);

		console_echo("New map created with the ID: <<#afa>>\"{$map->id}\"<>", '#fff');

		return $map;
	}

	private function findMap($mapName, $level)
	{
		$keys = array_keys($_SESSION);

		$match = "map_{$mapName}_{$level}_";
		$length = strlen($match);

		foreach ($keys as $key)
		{
			if (substr($key, 0, $length) === $match)
			{
				return $key;
			}
		}

		return false;
	}

	public function mountPlayerMap ()
	{
		if ($player->mapPath === $map->mapPath)
		{
			return;
		}

		global $player;
		global $map;
		global $view;

		if ($mapId = $this->findMap($player->mapPath, $player->level))
		{
			$newMap = unserialize($_SESSION[$mapId]);
		}
		else
		{
			$newMap = $this->addMap($player->mapPath, $player->level);
			$mapId = $newMap->id;
		}

		$player->mapId = $mapId;
		$_SESSION[$map->id] = serialize($map);
		$map = unserialize($player->mapId);
		$view = new View();
	}
}