<?php

class Voicebox
{
	public $owner;

	public function __construct(Dude $owner)
	{
		$this->owner = $owner;
	}

	function greet (Dude $target)
	{
		$speech[] =
		[
			[
				'Hello there.',
				'Hi!',
				'Pleased to meet you.',
				"Hello, my name is {$this->owner->name}. What's yours?",
				'Hello.'
			]
		];

		if (isset($this->owner->NPCIs['npci_sell'])) // We're talking to a shop keeper
		{
			global $currencies;

			$vendorCUR = $this->owner->inventory->CUR;
			$acceptedCurrencyName = $currencies[$vendorCUR]->name;

			$speech[] =
			[
				[
					'Hello there. ',
					'Hi! ',
					'Pleased to meet you. ',
					'Hello. '
				],
				[
					"{$this->owner->name}'s the name! ",
					"My name's {$this->owner->name}. ",
					''
				],

				count($this->owner->inventory->contents) > 0
				?
					[
						"If you're looking to buy some of my wares, remember I only take {$acceptedCurrencyName}.",
						"I make my way buying and selling in {$acceptedCurrencyName}.",
						"If you're looking to spend some of you {$acceptedCurrencyName}, why not take a look over my wares?"
					]
				:
					[
						"I don't have any wares left to sell but I'll happily buy yours for {$acceptedCurrencyName}.",
						"I seem to be out of stock but when I get more and you find yourself with {$acceptedCurrencyName} to spare, pay me a visit!",
					]
			];

		}
	}

	function chat ()
	{
		$speech[] =
		[
			[
				'Have you explored this area much? ',
				'Are you from around here? ',
				'Have you spent much time looking around this place? '
			],

			'I have a theory that this is some weird grid-based test area. ',

			[
				'What the test is for, I couldn\'t say, but the more time I spend here the more I\'m sure! ',
				'People say I\'m crazy but the more I think about thi, the more it makes sense! ',
				'Maybe it\'s some kind of experiment or a crazy beta test or something. Does that make us the first of mankind to walk this realm? '
			],
			[
				'Haven\'t you wondered why it is that we can only walk in four directions? What if there were...other...directions? ',
				'Don\'t you think it\'s strange that everything lines up so well and we can\'t walk behind things that appear to be up in the air? ',
			],
			[
				'You probably just think I\'m crazy, too...but I\'m not! ',
				'Surely you\'ve noticed...surely...',
			]
		];

		$speech[] =
		[
			'I wish this place had sound effects.'
		];

		$speech[] =
		[
			[
				'I wonder if the sun will ever go down. ',
				'It\'s been day-time for a very long time now; I\'m beginning to think the night isn\'t coming at all! ',
				'I\'m tired of all this sunlight. '
			],
			[
				'Come to think of it, I don\'t know what it would be like without the sun. I\'ve never been in the dark. ',
				'That said, I\'ve never seen a "night" as such. '
			],
			'I think it would be cool, though!'
		];

		$speech[] =
		[
			[
				'There\'s been a lot of trouble around here recently. ',
				'Have you noticed how much more trouble has been going on recently? ',
				'The world seems so much more dangerous than it did before. '
			],
			[
				'You look like you can handle it, though. ',
				'I hope you\'re here to help. ',
				'Better keep an eye out for danger coming your way. '
			],
			'I don\'t mean anything by that...just saying.'
		];
	}

	function bye ()
	{
		$speech[] =
		[
			[
				'Goodbye',
				'Farewell',
				'Safe travels',
				'Good luck with your endeavours',
			],
			[
				'!',
				'.'
			]
		];
	}


	function onSell ()
	{
		$speech[] =
			[
				[
					'Thanks',
					'Pleasure doing business with you',
					'Deal',
					'Thanks for your business'
				],
				[
					'.',
					'!'
				]
			];

		if (isset($topic) && $topic instanceof Item)
		{
			$topicPrice = $topic->getPrice($topic->lastUsedCUR, true);

			$speech[] =
			[
				[
					"Enjoy that {$topic->name}",
				],
				[
					'. ',
					'! '
				],
			];

			$speech[] =
			[
				"That'll be {$topicPrice} thanks!"
			];

			if ($topic instanceof Equipment)
			{
				$speech[] =
				[
					[
						"I'm glad I get to sell this {$topic->name} to someone who looks like the know how to use it",
					],
					[
						'. ',
						'! '
					],

				];
			}
		}
	}

	function onBuy ()
	{
		$speech[] =
		[
			[
				'Great! ',
				'Excellent! ',
				'Thanks! '
			],
			[
				'I think I know someone who\'ll love this! ',
				'I\'m sure this will be put to good use. ',
				'Should be able to sell this on. ',
				'Let me know if you have anything else you\'re looking to sell. '
			]
		];

		if (isset($topic) && $topic instanceof Item)
		{
			$topicPrice = $topic->getPrice($topic->lastUsedCUR, true);

			$speech[] =
			[
				[
					"{$topicPrice} is a fair price for "
				],
				[
					'something like that, I think.',
					"a {$topic->name}."
				]
			];

			$speech[] =
			[
				"{$topic->name}, huh? ",
				[
					"I'll give you {$topicPrice} for it.",
					"{$topicPrice} seems fair."
				]
			];

			if ($topicPrice === 0.01)
			{
				$speech[] =
				[
					[
						'Wow, what a piece of shit THIS is! ',
						'Oh God, what? '
					],
					[
						'Where did you even find this? ',
						'Why did you even pick this up? ',
						'What do you take me for? A garbage collector? ',
					],
					[
						'I\'ll give you almost nothing for it for novelty reasons only.',
						'Take this for it, I\'m probably over-paying even at that price.'
					]
				];
			}


		}
	}

	function onSell_ne ()
	{
		$speech[] =
		[
			[
				'I\'m going to need a bit more for that'
			],
			[
				', I\'m afraid.',
				'.',
				'!'
			]
		];

		if (isset($topic) && $topic instanceof Item)
		{
			$topicPrice = $topic->getPrice($topic->lastUsedCUR, true);
			$topicPlayerMoney = $player->wallet->getAmount($topic->lastUsedCUR, true);

			$speech[] =
			[
				[
					"Sorry, I'm going to need a bit more to part with this {$topic->name}.",
					"I think we both know that a {$topic->name} is worth more than {$topicPlayerMoney}."
				]
			];
		}
	}


	function onGive ()
	{
		$speech[] =
		[
			[
				'You look like you like stuff, ',
				'I\'m trying to get rid of some stuff; ',
				'Want something for nothing? If you do, '
			],
			[
				'here! Take this',
				'have this thing',
				'I have no use for this, you take it'
			],
			[
				'!',
				'.'
			]
		];

		if (isset($topic) && $topic instanceof Item)
		{
			$speech[] =
			[
				[
					'Here, I found this ',
					'I\'ve had this ',
					'I\'ve been holding one this '
				],

				"{$topic->name} but I don't ",

				[
					'really need it. ',
					'want it anymore. '
				],
				[
					'Do you want it? ',
					'You take it!',
					'Take it with you.',
					'I\'m sure you\'ll get more out of it than I ever could.'
				]
			];
		}
	}

	function onTake ()
	{
		$speech[] =
		[
			[
				'Thanks for that! ',
				'Thanks so much! ',
				'Wonderful! Thankyou! '
			],
			[
				'Such generosity is so rare these days',
				'I won\'t forget this kindness you have done me today',
			],
			[
				'!',
				'.'
			]
		];

		$speech[] =
		[
			'Thanks!'
		];

		if (isset($topic) && $topic instanceof Item)
		{
			$speech[] =
			[
				[
					'Thanks for that! ',
					'Thanks so much! ',
					'Wonderful! Thankyou! '
				],
				[
					"I'll make sure this {$topic->name} doesn't go to waste",
				],
				[
					'!',
					'.'
				]
			];
		}
	}


	function onExchange ()
	{

	}


	function onRepair ()
	{
		$speech[] =
		[
			[
				'Well it\'s not what it was ',
				'It\'s not a perfect job ',
				'It\'s not exactly "like new" ',
			],
			[
				'but it should hold together a bit longer. ',
				'but it is usable. ',
				'but after a beating like that, it was never going to be. '
			]
		];

		if (isset($topic) && $topic instanceof Item)
		{
			$itemName = $topic->name;
			$isBroken = $topic->isBroken;
			$durabilityPercentage = ($topic->durability / $topic->durabilityMax) * 100;
			$price = getFormattedAmount($this->owner->NPCIs['npci_repair']->getRepairPrice($topic), $this->owner->CUR, true);


			$speech[] =
			[
				[
					"{$price} seems fair for repairs like that.",
					"I'll fix up that {$itemName} for {$price}.",
					"You really should take better care of this {$itemName}.",
				]
			];
		}
	}

	function onRepair_ne ()
	{
		if (isset($topic) && $topic instanceof Item)
		{
			$itemName = $topic->name;
			$isBroken = $topic->isBroken;
			$durabilityPercentage = ($topic->durability / $topic->durabilityMax) * 100;
			$price = getFormattedAmount($this->owner->NPCIs['npci_repair']->getRepairPrice($topic), $this->owner->CUR, true);
			$playerMoney = $player->wallet->getAmount($this->owner->CUR, true);


			$speech[] =
			[
				[
					'Repairs are hard work. ',
					'This isn\'t going to be an easy job. ',
				],
				[
					"I'm going to need at least {$price} to fix that {$itemName}.",
					"I can't fix your {$itemName} for less than {$price}",
				]
			];

			if ($isBroken)
			{
				$speech[] =
				[
					[
						"This {$itemName} is completely ruined! ",
						"How did your {$itemName} get into such a state of disrepair? ",
					],
					[
						'I\'m sure I can fix it but ',
						'It can be fixed but ',
					],
					"I'm going to need more than {$playerMoney}."
				];
			}
		}
	}


	function onFollow ()
	{
		$speech[] =
		[
			[
				'Ok great! You lead the way! ',
				'Yeah, I\'ll follow you. ',
				'Right behind you! '
			],
			[
				'Let me know if you want to ',
				'Just tell me if you need me to ',
			],
			[
				'wait behind.',
				'hold my position.',
				'guard the area.',
			]
		];
	}

	function onWait ()
	{
		$speech[] =
		[
			[
				'Alright, I\'ll wait here. ',
				'I\'ll stay here. ',
				'I\'ll watch this area. '
			],
			[
				'Let me know if you want to ',
				'Just tell me if you need me to ',
			],
			[
				'follow again.',
				'come with you.',
				'watch your back.',
			]
		];
	}


	function attacking ()
	{
		$speech[] =
		[
			[
				'Get back!',
				'Yaaahh!'
			]
		];
	}

	function onMiss ()
	{
		$speech[] =
		[
			[
				'Damn, ',
				'Steady! ',
				'Ahh, '
			],
			[
				'missed!',
				'missed you!',
			]
		];
	}

	function onStrike ()
	{
		$speech[] =
		[
			[
				'Take that!',
				'Hope you felt that one!',
				'Stay back!',
				'There\'s more where that canme from!'
			]
		];
	}

	function onKill ()
	{
		$speech[] =
		[
			[
				'You made me do this.',
				'Shouldn\'t have fucked with me.',
				'Anyone else?'
			]
		];
	}


	function onDefend ()
	{
		$speech[] =
		[
			[
				'Ahh!',
				'Leave me be!',
				'Stop!'
			]
		];
	}

	function onDeflect ()
	{
		$speech[] =
		[
			[
				'That could have gone badly.',
				'That would have hurt!',
				'Missed me!'
			]
		];
	}

	function onTakeHit ()
	{
		$speech[] =
		[
			[
				'AHH! ',
				'Ugh. ',
				'Ah shit! '
			],
			[
				'You got me!',
				'Hit me!',
			]
		];

		$speech[] =
		[
			[
				'Ahh! ',
				'Ouch! '
			],
			'Got me right in the ',
			[
				'arm',
				'leg',
				'face',
				'shoulder',
				'waiste',
				'junk',
				'belly',
				'throat',
				'ear'
			],
			'!'
		];
	}

	function dying ()
	{
		$speech[] =
		[
			[
				'This is...it...',
				'I\'m done...',
				'It\'s all over... '
			]
		];
	}


	function error ()
	{
		console_echo("Speech error. Don't think it's possible to hit this code.", '#faa');

		$speech[] =
		[
			[
				'Herpa derpa!',
				'Derp derp derp!',
				'Hurrr durrrrr!',
				'Derp!',
				'Herp derp!'
			]
		];
	}
}