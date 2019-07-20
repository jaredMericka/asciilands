<?php

abstract class Material
{
	public $name;
	public $description;
	public $colour;
	public $sprite;

	public $durability	= 1;
	public $goldValue	= 1;

	private $DMGs		= [];
	private $DMGDLs		= [];
	private $DMGs_def	= [];
	private $DSs		= [];
	private $DSs_req	= [];

	public function __construct($name, $description, $colour,
		$DMGs		= null,
		$DMGDLs		= null,
		$DMGs_def	= null,
		$DSs		= null,
		$DSs_req	= null
		)
	{
		$this->name			= $name;
		$this->description	= $description;
		$this->colour		= $colour;
		$this->sprite		= $this->getSprite();

		$arrayNames = [
			'DMGs',
			'DMGDLs',
			'DMGs_def',
			'DSs',
			'DSs_req',
		];


		foreach ($arrayNames as $array)
		{
			if (!isset($$array)) $$array = [];

			$baseArray = $this->getArray($array);
			$resultArray = $$array + $baseArray;
			foreach ($baseArray as $key => $value)
			{
				if (is_string($value)) $resultArray[$key] = "{$resultArray[$key]}";
			}
			$this->{"_{$array}"} = $resultArray; // Terrible
		}
	}

	function __toString()
	{
		return $this->name;
	}

	function getArray($array) { return $this->$array; }

	abstract function getSprite();

	function __get($name)
	{
		if (isset($this->{"_{$name}"}))
		{
			return $this->{"_{$name}"};
		}
	}
}

trait MaterialTrait
{
	function getArray($array)
	{
		$thisArray = isset($this->$array) ? $this->$array : [];
		$parentArray = parent::getArray($array);
		$returnArray = $thisArray + $parentArray;
		foreach ($parentArray as $key => $value)
		{
			if (is_string($value)) $returnArray[$key] = "{$returnArray[$key]}";
		}
		return $returnArray;
	}
}

class itm_material extends Item
{
	public function __construct(Material $material)
	{
		$this->materials = [$material];

		parent::__construct(
			$material->name,
			$material->description,
			$material->sprite
		);
	}
}

class mat_fabric extends Material
{
	use MaterialTrait;

	public $durability = 1;

	private $DMGs_def =
	[
		DMG_COLD => '1.5',
	];
	private $DSs =
	[
		DS_AGILITY => 1.2,
	];

	function getSprite()
	{
		return new Sprite([
			0 => new SpriteElement(tint($this->colour, -1), '#fff', '@'),
			1 => new SpriteElement(tint($this->colour, 1), $this->colour, '&#x2584;'),
			2 => new SpriteElement(tint($this->colour, 1), $this->colour, '&#x2584;'),
			4 => new SpriteElement($this->colour, '#fff', '&#x2026;'),
			5 => new SpriteElement($this->colour, '#fff', '&#x2026;'),
		]);
	}
}

class mat_lightFabric extends mat_fabric
{
	use MaterialTrait;

	public $durability = 0.7;
}

class mat_heavyFabric extends mat_fabric
{
	use MaterialTrait;

	public $durability = 1.4;

	private $DMGs_def =
	[
		DMGDL_CUT => 1.5,
	];
}

class mat_skin extends mat_heavyFabric
{
	use MaterialTrait;

	public $durability = 1.7;

	private $DMGs_def = [
		DMG_WATER => '2.0',
	];

	function getSprite()
	{
		return new Sprite([
			0 => new SpriteElement($this->colour, '#000', '&#x201a;'),
			1 => new SpriteElement(null, $this->colour, '&#x2584;'),
			2 => new SpriteElement($this->colour, null, null),
			3 => new SpriteElement($this->colour, '#000', '&#x2019;'),
			4 => new SpriteElement(null, $this->colour, '&#x2580;'),
			5 => new SpriteElement($this->colour, null, null),
			]);
	}
}

class mat_metal extends Material
{
	use MaterialTrait;

	public $durability = 2.5;

	private $DMGs_def =[
		DMGDL_CUT		=> '2.0',
		DMGDL_BLUNT		=> '2.0',
		DMGDL_POINT		=> '2.0',

		DMG_ELECTRIC	=> -0.5,
	];
	private $DSs =[
		DS_RESILIENCE	=> '1.8',
		DS_FORCE		=> 1.7,
	];

	function getSprite()
	{
		return new Sprite([
			0 => new SpriteElement(null, $this->colour, '&#x2584;'),
			1 => new SpriteElement(null, $this->colour, '&#x2584;'),
			2 => new SpriteElement(null, $this->colour, '&#x2584;'),
			3 => new SpriteElement(tint($this->colour, -2), $this->colour, '&#x2500;'),
			4 => new SpriteElement(tint($this->colour, -2), $this->colour, '&#x2500;'),
			5 => new SpriteElement(tint($this->colour, -2), $this->colour, '&#x2500;'),
		]);
	}
}

class mat_wood extends Material
{
	use MaterialTrait;

	public $durability = 2;

	private $DMGs_def = [
		DMG_FIRE	=> '-0.5',
		DMGDL_BLUNT	=> '1.8',
		DMGDL_CUT	=> '1.5',
		DMGDL_POINT	=> '1.6',
	];
	private $DMGDLs =[
		DMGDL_CUT	=> 0.1,
		DMGDL_BLUNT	=> 1.1,
		DMGDL_POINT	=> 1.3,
	];

	function getSprite()
	{
		$tint = tint($this->colour, -2);
		return new Sprite([
			0 => new SpriteElement(null, $this->colour, '&#x2584;'),
			1 => new SpriteElement(null, $this->colour, '&#x2584;'),
			2 => new SpriteElement(null, $this->colour, '&#x2584;'),
			3 => new SpriteElement($this->colour, $tint, '&#x2584;'),
			4 => new SpriteElement($this->colour, $tint, '&#x2584;'),
			5 => new SpriteElement($this->colour, $tint, '&#x2584;'),
		]);
	}

}

class mat_bone extends Material
{
	use MaterialTrait;

	public $durability = 1.9;

	function getSprite()
	{
		return new Sprite([
			1 => new SpriteElement(null, $this->colour, '_'),
			2 => new SpriteElement(null, $this->colour, '_'),
			3 => new SpriteElement(null, $this->colour, '('),
			4 => new SpriteElement(null, $this->colour, '('),
			5 => new SpriteElement(null, $this->colour, '('),
		]);
	}
}

class mat_stone extends Material
{
	use MaterialTrait;

	public $durability = 2.9;

	function getSprite()
	{
		$top		= tint($this->colour, 3, true);
		$topDetail	= tint($this->colour, 6, true);

		return new Sprite([
			new SpriteElement($top, $topDetail, 'L'),
			new SpriteElement($top, $topDetail, '_'),
			new SpriteElement($top, $topDetail, '_'),
			new SpriteElement($this->colour, $top, 'L'),
			new SpriteElement($this->colour, $top, '_'),
			new SpriteElement($this->colour, $top, '_')
		]);
	}
}

class mat_gem extends mat_stone
{
	use MaterialTrait;

	public $durability = 3.5;

//	function getSprite()
//	{
//		// Need a sprite lol
//	}
}