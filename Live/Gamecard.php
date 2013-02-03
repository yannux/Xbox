<?php

/**
 * @package Xbox
 * @subpackage Live
 * @author Yannux
 * @version 0.1
 * @license GPL
 *  
 */
class Xbox_Live_Gamercard
{
	/**
	 * @var $_language String 
	 */
	private $_apiUrl	= 'http://gamercard.xbox.com/';

	/**
	 * @var $_language String Iso Code 
	 */
	private $_language 	= 'fr-FR';

	/**
	 * @var $_language String 
	 */
	private $_gamerTag	= '';

	/**
	 * $var $_gamerData DOMDocument
	 */
	private $_gamerData;


	/**
	 * @param $gamertag String Gamer Tag
	 * @param $language String Language IsoCode like en-US, fr-FR, etc..
	 * @return Xbox_Live_Gamercard
	 */
	public function __construct($gamertag = '', $language = '')
	{
		if (!empty($gamertag)) {
			$this->setGamerTag($gamertag);
		}

		if (!empty($language)) {
			$this->setLanguage($language);
		}

		return $this;
	}

	/**
	 * @param $value String Language IsoCode like en-US, fr-FR, etc..
	 * @return Xbox_Live_Gamercard
	 */
	public function setLanguage($value)
	{
		$this->_language = $value;

		return $this;
	}

	/**
	 * @param $value String Gamer Tag
	 * @return Xbox_Live_Gamercard
	 */
	public function setGamerTag($value)
	{
		$this->_gamerTag = $value;

		return $this;
	}

	public function callApi()
	{
		if (empty($this->_gamerTag)) {
			throw new Exception('You need to set the gamertag with setGamerTag');
		}


		$this->_gamerData = new DOMDocument();
		$this->_gamerData->preserveWhiteSpace = false;

		// Add xml encoding to load HTML as UTF-8 http://www.php.net/manual/fr/domdocument.loadhtml.php#95251
		$this->_gamerData->loadHTML('<?xml encoding="UTF-8">' 
			. file_get_contents($this->_apiUrl . $this->_language . '/' . $this->_gamerTag . '.card'));

		return $this;
	}

	/**
	 * @todo finish it, retrieve all data for gamercard
	 * @return array;
	 */
	public function getAll()
	{
	}

	/**
	 * @return string
	 */
	public function getProfilUrl()
	{
		return $this->_gamerData->getElementById('Gamertag')->getAttribute('href');
	}

	/**
	 * @return string
	 */
	public function getAvatarUrl()
	{
		return $this->_gamerData->getElementById('Gamerpic')->getAttribute('src');	
	}

	/**
	 * @return int
	 */
	public function getReputation()
	{
		$xpath 	= new DOMXPath($this->_gamerData);
		$length = (int)$xpath->evaluate("//div[@class='RepContainer']/div[@class='Star Full']")->length;
		
		unset($xpath);

		return $length;
	}

	/**
	 * @return int
	 */
	public function getGamerScore()
	{
		return (int)$this->_gamerData->getElementById('Gamerscore')->nodeValue;
	}

	/**
	 * @param $max int Between 0 and 5.
	 * @return array
	 */
	public function getPlayedGames($max = 5)
	{
		if ((int)$max > 5 || (int)$max === 0) {
			$max = 5;
		}
		
		$playedGames = array();

		$xpath 	= new DOMXPath($this->_gamerData);
		$node 	= $xpath->query("//*[@id='PlayedGames']/*");

		unset($xpath);

		foreach ($node as $key => $gameNode) {
			
			if ($key >= $max) break;

			$playedGames[$key] = array();

			$gameInfos = $gameNode->getElementsByTagName('span');
			foreach ($gameInfos as $info) {
				$playedGames[$key][$info->getAttribute('class')] = $info->nodeValue;
			}
			
			unset($gameInfos);
			
			$playedGames[$key]['activityUrl'] 	= $gameNode->getElementsByTagName('a')->item(0)->getAttribute('href');
			$playedGames[$key]['gameImgUrl'] 	= $gameNode->getElementsByTagName('img')->item(0)->getAttribute('src');
		}

		unset($node);

		return $playedGames;
	}
}