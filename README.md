Xbox
====

PHP to retrieve Xbox Live informations for gamer


include(__DIR__ . '/Live/Gamecard.php');

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
