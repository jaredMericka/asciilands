<?php

$itm_yirinKey = new Item('Yirin\'s key', 'The key Yirin\'s house and supplies chest.', spr_key('#cc6'));
$itm_yirinKey->cantLose = true;

$eqp_yirinSword = new eqp_sword(2);
$eqp_yirinSword->name = 'Yirin\'s Mighty Sword';
$eqp_yirinSword->description = 'This sword has caused many a wound but not for many a year.';
$eqp_yirinSword->DSs_req_mod = 0.6;
$eqp_yirinSword->DMGs_mod = 1.5;
$eqp_yirinSword->DMGs_mod = 1.5;
$eqp_yirinSword->goldValue = 2;
$eqp_yirinSword->DS_base = DS_AGILITY;

$eqp_yirinSword->finish();

$dude_giantSpider = new nme_giantSpider();