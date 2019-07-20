<?php

class set_miscDecor extends AssetSet
{
	public function scn_candleArbra ($colour = null)
	{
		$colour = $colour ? $colour : '#aaa';

		return new Scenery(new Sprite([
			0 => new SpriteElement(null, $colour, '&#x2559;'),
			1 => new SpriteElement(null, $colour, '&#x256b;'),
			2 => new SpriteElement(null, $colour, '&#x255c;'),
			4 => new SpriteElement(null, $colour, '&#x2568;'),
		]), TPL_HIGHOBSTACLE);
	}

	public function scn_candleArbraFlames ()
	{
		return new Scenery(new Sprite([
			[
				3 => new SpriteElement(null, '#fa0', '&#x25b2;'),
				4 => new SpriteElement(null, '#fa0', '&#x2666;'),
				5 => new SpriteElement(null, '#fa0', '&#x25bc;'),
			],
			[
				3 => new SpriteElement(null, '#fa0', '&#x2666;'),
				4 => new SpriteElement(null, '#fa0', '&#x25bc;'),
				5 => new SpriteElement(null, '#fa0', '&#x25b2;'),
			],
			[
				3 => new SpriteElement(null, '#fa0', '&#x25bc;'),
				4 => new SpriteElement(null, '#fa0', '&#x25b2;'),
				5 => new SpriteElement(null, '#fa0', '&#x2666;'),
			],
		]));
	}
}