<?php

// IN refers to INTO THE NPC'S POSSESION
// OUT refers to OUT OF THE NPC'S POSSESION

class npci_exchange extends NPCInteraction
{
	const COMMISSION = 0.15;

	public $CURs_in = [];
	public $CURs_out = [];

	public function __construct($CURs_in, $CURs_out)
	{
		$this->CURs_in = $CURs_in;
		$this->CURs_out = $CURs_out;

		parent::__construct('Exchange', 'Exchange currencies.');
	}

	public function onClick($UIN)
	{
		global $currencies;
		global $player;

		$update = new stdClass();
		$update->type = $this->key;
		$update->currencies = [];

		foreach ($this->CURs_in as $CUR)
		{
			if (!isset($player->wallet->contents[$CUR])) continue;

			$update_currency = new stdClass();

			$update_currency->name	= $currencies[$CUR]->name;
			$update_currency->sym	= $currencies[$CUR]->symbol;
			$update_currency->CUR	= $CUR;

			$update->currencies[] = $update_currency;
		}

		update(UPD_INTERACTIONS, $update);
	}

	public function onItemClick($UIN, $content)
	{
		global $currencies;
		global $player;

		$content = explode('#', "{$content}");

		$CUR_in = (int)$content[0];
		$CUR_out = isset($content[1]) ? (int)$content[1] : null;
		$amount_in = isset($content[2]) ? (int)str_replace(',', '', $content[2]) : null;


		if (isset($CUR_out))
		{
			if (!in_array($CUR_in, $this->CURs_in)
				|| !in_array($CUR_out, $this->CURs_out)
				|| $amount_in > $player->wallet->contents[$CUR_in])
			{
				update_thoughts('Naughty, naughty!');
				//packet hacking has transpired
				$this->onClick($UIN);
				return;
			}

			$amount_out = convertCurrency($amount_in, $CUR_in, $CUR_out) * (1 - self::COMMISSION);

			console_echo("In:  {$currencies[$CUR_in]->symbol} {$amount_in}");
			console_echo("Out: {$currencies[$CUR_out]->symbol} {$amount_out}");

			if ($player->wallet->remove($amount_in, $CUR_in))
			{

				$player->wallet->add($amount_out, $CUR_out);

				$exchangeDetails = new stdClass();

				$exchangeDetails->CUR_in = $CUR_in;
				$exchangeDetails->CUR_out = $CUR_out;
				$exchangeDetails->amount_in = $amount_in;
				$exchangeDetails->amount_out = $amount_out;

				$this->owner->speak(SPSI_EXCHANGING, $exchangeDetails);
			}
			else
			{
				update_thoughts('Looks like I don\'t have the money for that. Weird.');
			}

			$this->onClick($UIN);
		}
		else
		{
			$update = new stdClass();
			$update->type = $this->key . '_CUR';
			$update->currencies = [];

			$update->CUR = $CUR_in;
			$update->name = $currencies[$CUR_in]->name;
			$update->sym = $currencies[$CUR_in]->symbol;
//			$update->amount = number_format($player->wallet->contents[$CUR_in]);

			foreach ($this->CURs_out as $CUR_out)
			{
				if (!isset($player->wallet->contents[$CUR_out]) || !$player->wallet->contents[$CUR_out] || $CUR_in === $CUR_out) continue;

				$enough = true;
				$amount_in = 1;

				do
				{
					$amount_out = floor(convertCurrency($amount_in, $CUR_in, $CUR_out) * (1 - self::COMMISSION));

					if ($amount_out >= 1)
					{
						$update_currency = new stdClass();

						$update_currency->CUR = $CUR_out;
						$update_currency->name = $currencies[$CUR_out]->name;
						$update_currency->sym = $currencies[$CUR_out]->symbol;
						$update_currency->amount_out = number_format($amount_out);
						$update_currency->amount_in = number_format($amount_in);

						$update->currencies[] = $update_currency;
					}

					$amount_in *= 10;
					if ($player->wallet->contents[$CUR_in] < $amount_in) $enough = false;

				} while ($enough === true);

				$update_currency = new stdClass();

				$update_currency->CUR = $CUR_out;
				$update_currency->name = $currencies[$CUR_out]->name;
				$update_currency->sym = $currencies[$CUR_out]->symbol;
				$update_currency->amount_out = number_format(floor(convertCurrency($player->wallet->contents[$CUR_in], $CUR_in, $CUR_out) * (1 - self::COMMISSION)));
				$update_currency->amount_in = number_format($player->wallet->contents[$CUR_in]);

				$update->currencies[] = $update_currency;
			}

			update(UPD_INTERACTIONS, $update);
		}

//		update_thoughts("Gotta rid me of these shitty {$currencies[(int)$content]->name}!");
	}
}