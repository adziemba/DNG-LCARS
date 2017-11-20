<?php
namespace core\dto;

/**
 * LCARS is a framework for the RPG - ST - DNG
 *
 * @package    LCARS
 * @version    0.1
 * @author     Andreas Dziemba
 * @license    DNG-Bluegreen License
 * @copyright  2016 - 2017 Star Trek - Die neue Grenze
 * @link       http://www.die-neue-grenze.de
 */

class Race {
	private $race;				//varchar(64)
	private $racestate;			//varchar(64)
	private $homeplanet; 		//int(11)
	private $alliance;			//int(11)
	private $race_description;	//text
	private $playable;			//tinyint(1) / boolean
	
	public function getRace() {
		return $this->race;
	}
	
	//Limit 64 Chars
	public function setRace(string $race) {
		$this->race = $race;
	}
		
	public function getRaceState() {
		return $this->racestate;
	}
	
	//Limit 64 Chars
	public function setRaceState(string $racestate) {
		$this->racestate = $racestate;
	}
	
	public function getHomeplanet() {
		return $this->homeplanet;
	}
	
	// ForeignKey Planet
	public function setHomeplanet(int $homeplanet) {
		$this->homeplanet = $homeplanet;
	}
	
	public function getAlliance() {
		return $this->alliance;
	}
	
	// ForeignKey Alliance
	public function setAlliance(string $alliance) {
		$this->alliance = $alliance;
	}
	
	
	public function getRaceDescription() : string {
		return $this->race_description;
	}
	
	public function setRaceDescription(string $description) {
		if($description==null) {
			$this->race_description = "";
		}else {
			$this->race_description = $description;
		}
	}
	
	public function isPlayable() {
		return $this->playable;
	}
	
	public function setPlayable(bool $playable) {
		$this->playable = $playable;
	}
}