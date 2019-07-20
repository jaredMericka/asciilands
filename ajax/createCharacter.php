<?php
$rootPath = '../';

require "{$rootPath}engine/core/include.php";
require "{$rootPath}engine/core/newCharConstants.php";


if (!isset($_POST['n'], $_POST['h'], $_POST['l'], $_POST['s'], $_POST['p'])) DIE('1');

$name	= $_POST['n'];
$gender	= $_POST['g'];
$head	= $gender ? $heads_f[$_POST['h']] : $heads_m[$_POST['h']];
$legs	= $gender ? $legs_f[$_POST['l']] : $legs_m[$_POST['l']];
$skin	= $skins[$_POST['s']];
$pants	= $pants[$_POST['p']];

$player = new Player($name, START_MAP, START_N, START_W, $skin, $pants, $head, $legs);
$player->gender = $gender ? GND_FEMALE : GND_MALE;

unset($_SESSION['map']);
unset($_SESSION['view']);

$_SESSION['player'] = $player;

EXIT('0');