<?php 
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('utf-8');
setlocale(constant('LC_ALL'), 'fr_FR.UTF-8');

ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
error_reporting(-1);

include(__DIR__ . '/Xbox/Live/Gamecard.php');

/**
 * @var $xboxLive Xbox_Live
 */
$xboxLive = new Xbox_Live_Gamercard;

$xboxLive->setGamerTag('xunnay')->setlanguage('fr-FR')->callApi();

echo $xboxLive->getProfilUrl();
echo "<br />";
echo $xboxLive->getAvatarUrl();
echo "<br />";
echo $xboxLive->getReputation();
echo "<br />";
echo $xboxLive->getGamerScore();
echo "<br />";
var_dump($xboxLive->getPlayedGames(5));
echo "<br />";