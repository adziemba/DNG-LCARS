<?php
namespace core\classes\datastorage;

use core\dto\Alliance;
use core\dto\Planet;
use core\dto\PlanetClass;
use core\dto\Race;

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

class RPGRacePlanetsDatabase extends DNGDataDatabase{	
	
	public static $_instance = null;
	
	public static function instance() {
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new RPGRacePlanetsDatabase();
	
		}
		return self::$_instance;
	}
	
	// Alliances
	public function getAllAlliances() {		
		$stmt 	= $this->pdo->prepare("SELECT alliance_id, alliance_name, alliance_tag, alliance_description 
				FROM ".$this->tables['ALLIANCE']);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetchAll();
		
		$alliances = array();
		foreach($data as $all) {
			$ally = new Alliance();
			$ally->setAllianceID($all['alliance_id']);
			$ally->setAllianceName($all['alliance_name']);
			$ally->setAllianceTag($all['alliance_tag']);
			$ally->setAllianceDescription($all['alliance_description']);

			array_push($alliances, $ally);
		}

		return $alliances;
	}
	
	public function getAlliance(int $allianceID) {
		$stmt = $this->pdo->prepare("SELECT alliance_id, alliance_name, alliance_tag, alliance_description 
				FROM ".$this->tables['ALLIANCE'] ."
				WHERE alliance_id=:aid");
		$stmt->bindParam("aid", $allianceID, \PDO::PARAM_INT);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetch(\PDO::FETCH_ASSOC);
		
		if($count==1) {
			$ally = new Alliance();
			$ally->setAllianceID($data['alliance_id']);
			$ally->setAllianceName($data['alliance_name']);
			$ally->setAllianceTag($data['alliance_tag']);
			$ally->setAllianceDescription($data['alliance_description']);

			return $ally;
		}
		return null;
	}
	
	public function saveNewAlliance(Alliance $ally) {
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->tables['ALLIANCE']." (alliance_name, alliance_tag, alliance_description) VALUES (:aname, :atag, :adesc)");
		$stmt->bindParam("aname", $ally->getAllianceName(), \PDO::PARAM_STR);
		$stmt->bindParam("atag", $ally->getAllianceTag(), \PDO::PARAM_STR);
		$stmt->bindParam("adesc", $ally->getAllianceDescription(), \PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	public function updateAllianace(Alliance $ally) {
		$stmt = $this->pdo->prepare("UPDATE  ".$this->tables['ALLIANCE']." 
				SET alliance_name=:aname, alliance_tag=:atag, alliance_description=:adesc
				WHERE alliance_id=:aid");
		$stmt->bindParam("aid", $ally->getAllianceId(), \PDO::PARAM_INT);
		$stmt->bindParam("aname", $ally->getAllianceName(), \PDO::PARAM_STR);
		$stmt->bindParam("atag", $ally->getAllianceTag(), \PDO::PARAM_STR);
		$stmt->bindParam("adesc", $ally->getAllianceDescription(), \PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	public function removeAllianace(int $allyid) {
		$stmt = $this->pdo->prepare("DELETE FROM ".$this->tables['ALLIANCE']." WHERE alliance_id=:aid");
		$stmt->bindParam("aid", $allyid, \PDO::PARAM_INT);
		return $stmt->execute();
	}
	
	// Planets
	public function getAllPlanets() {
		$stmt 	= $this->pdo->prepare("SELECT planet_id, planet_name, planet_class, planet_position, planet_description 
				FROM ".$this->tables['PLANET']);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetchAll();
		
		$planets = array();
		foreach($data as $plan) {
			$planet = new Planet();
			$planet->setPlanetID($plan['planet_id']);
			$planet->setPlanetName($plan['planet_name']);
			$planet->setPlanetClass($plan['planet_class']);
			$planet->setPlanetposition($plan['planet_position']);
			$planet->setPlanetDescription($plan['planet_description']);
		
			array_push($planets, $planet);
		}
		
		return $planets;
	}
	
	public function getPlanet(int $planetID) {
		$stmt = $this->pdo->prepare("SELECT planet_id, planet_name, planet_class, planet_position, planet_description 
				FROM ".$this->tables['PLANET'] ."
				WHERE planet_id=:pid");
		$stmt->bindParam("pid", $planetID, \PDO::PARAM_INT);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetch(\PDO::FETCH_ASSOC);
		
		if($count==1) {
			$planet = new Planet();
			$planet->setPlanetID($data['planet_id']);
			$planet->setPlanetName($data['planet_name']);
			$planet->setPlanetClass($data['planet_class']);
			$planet->setPlanetposition($data['planet_position']);
			$planet->setPlanetDescription($data['planet_description']);
			return $planet;
		}
		return null;
	}
	
	public function saveNewPlanet(Planet $planet) {
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->tables['PLANET']." (planet_name, planet_class, planet_position, planet_description) 
																		VALUES (:pname, :pclass, :ppos, :pdesc)");
		$stmt->bindParam("pname", $planet->getPlanetName(), \PDO::PARAM_STR);
		$stmt->bindParam("pclass", $planet->getPlanetClass(), \PDO::PARAM_INT);
		$stmt->bindParam("ppos", $planet->getPlanetPosition(), \PDO::PARAM_STR);
		$stmt->bindParam("pdesc", $planet->getPlanetDescription(), \PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	public function updatePlanet(Planet $planet) {
		$stmt = $this->pdo->prepare("UPDATE  ".$this->tables['PLANET']."
				SET planet_name=:pname, planet_class=:pclass, planet_position=:ppos, planet_description=:pdesc
				WHERE planet_id=:pid");
		$stmt->bindParam("pid", $planet->getPlanetId(), \PDO::PARAM_INT);
		$stmt->bindParam("pname", $planet->getPlanetName(), \PDO::PARAM_STR);
		$stmt->bindParam("pclass", $planet->getPlanetClass(), \PDO::PARAM_INT);
		$stmt->bindParam("ppos", $planet->getPlanetPosition(), \PDO::PARAM_STR);
		$stmt->bindParam("pdesc", $planet->getPlanetDescription(), \PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	public function removePlanet(int $planetID) {
		$stmt = $this->pdo->prepare("DELETE FROM ".$this->tables['PLANET']." WHERE planet_id=:pid");
		$stmt->bindParam("pid", $planetID, \PDO::PARAM_INT);
		return $stmt->execute();
	}
	
	//Planet Classes
	public function getAllPlanetClasses() {
		$stmt = $this->pdo->prepare("SELECT planet_class, class_description
				FROM ".$this->tables['PLANETCLASS']);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetchAll();
		
		$planetclasse = array();
		foreach($data as $plan) {
			$planetClass = new PlanetClass();
			$planetClass->setPlanetClass($plan['planet_class']);
			$planetClass->setPlanetClassDescription($plan['class_description']);
		
			array_push($planetclasse, $planetClass);
		}
		return $planetclasse;
	}
	
	public function getPlanetClass(string $planetClass) {
		$stmt = $this->pdo->prepare("SELECT planet_class, class_description
				FROM ".$this->tables['PLANETCLASS'] ."
				WHERE planet_class=:pid");
		$stmt->bindParam("pid", $planetClass, \PDO::PARAM_STR);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetch(\PDO::FETCH_ASSOC);
		
		if($count==1) {
			$planetClass = new PlanetClass();
			$planetClass->setPlanetClass($data['planet_class']);
			$planetClass->setPlanetClassDescription($data['class_description']);
			return $planetClass;
		}
		return null;
	}
	
	public function saveNewPlanetClass(PlanetClass $planetClass) {
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->tables['PLANETCLASS']." (planet_class, class_description)
																		VALUES (:pclass, :pdesc)");
		$stmt->bindParam("pclass", $planetClass->getPlanetClass(), \PDO::PARAM_STR);
		$stmt->bindParam("pdesc", $planetClass->getPlanetClassDescription(), \PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	public function updatePlanetClass(PlanetClass $planetClass) {
		$stmt = $this->pdo->prepare("UPDATE ".$this->tables['PLANETCLASS']."
				SET planet_class=:pclass, class_description=:pdesc
				WHERE planet_class=:pclass");
		$stmt->bindParam("pclass", $planetClass->getPlanetClass(), \PDO::PARAM_STR);
		$stmt->bindParam("pdesc", $planetClass->getPlanetClassDescription(), \PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	public function removePlanet(string $planetClass) {
		$stmt = $this->pdo->prepare("DELETE FROM ".$this->tables['PLANETCLASS']." WHERE planet_class=:pid");
		$stmt->bindParam("pid", $planetClass, \PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	// Races
	public function getAllRaces() {
		$stmt = $this->pdo->prepare("SELECT race, racestate, homeplanet, alliance, racedescription, playable
				FROM ".$this->tables['RACE']);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetchAll();
		
		$races = array();
		foreach($data as $plan) {
			$race = new Race();
			$race->setRace($plan['race']);
			$race->setRaceState($plan['racestate']);
			$race->setHomeplanet($plan['homeplanet']);
			$race->setAlliance($plan['alliance']);
			$race->setRaceDescription($plan['racedescription']);
			$race->setPlayable($plan['playable']);
		
			array_push($races, $race);
		}
		return $races;
	}
	
	public function getRace(string $race) {
		$stmt = $this->pdo->prepare("race, racestate, homeplanet, alliance, racedescription, playable
				FROM ".$this->tables['RACE'] ."
				WHERE race=:race");
		$stmt->bindParam("race", $race, \PDO::PARAM_STR);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetch(\PDO::FETCH_ASSOC);
		
		if($count==1) {
			$race = new Race();
			$race->setRace($data['race']);
			$race->setRaceState($data['racestate']);
			$race->setHomeplanet($data['homeplanet']);
			$race->setAlliance($data['alliance']);
			$race->setRaceDescription($data['racedescription']);
			$race->setPlayable($data['playable']);
			return $race;
		}
		return null;
	}
	
	public function saveNewRace(Race $race) {
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->tables['RACE']." (race, racestate, homeplanet, alliance, racedescription, playable)
																		VALUES (:race, :racestate, :homeplanet, :alliance, :racedescription, :playable)");
		$stmt->bindParam("race", $race->getRace(), \PDO::PARAM_STR);
		$stmt->bindParam("racestate", $race->getRaceState(), \PDO::PARAM_STR);
		$stmt->bindParam("homeplanet", $race->getHomeplanet(), \PDO::PARAM_INT);
		$stmt->bindParam("alliance", $race->getAlliance(), \PDO::PARAM_INT);
		$stmt->bindParam("racedescription", $race->getRaceDescription(), \PDO::PARAM_STR);
		$stmt->bindParam("playable", $race->isPlayable(), \PDO::PARAM_BOOL);
		return $stmt->execute();
	}
	
	public function updateRace(Race $race) {
		$stmt = $this->pdo->prepare("UPDATE ".$this->tables['RACE']."
				SET race=:race, racestate=:racestate, homeplanet=:homeplanet, alliance=:alliance, racedescription=:racedescription, playable=:playable
				WHERE race=:race");
		$stmt->bindParam("race", $race->getRace(), \PDO::PARAM_STR);
		$stmt->bindParam("racestate", $race->getRaceState(), \PDO::PARAM_STR);
		$stmt->bindParam("homeplanet", $race->getHomeplanet(), \PDO::PARAM_INT);
		$stmt->bindParam("alliance", $race->getAlliance(), \PDO::PARAM_INT);
		$stmt->bindParam("racedescription", $race->getRaceDescription(), \PDO::PARAM_STR);
		$stmt->bindParam("playable", $race->isPlayable(), \PDO::PARAM_BOOL);
		return $stmt->execute();
	}
	
	public function removeRace(string $race) {
		$stmt = $this->pdo->prepare("DELETE FROM ".$this->tables['RACE']." WHERE race=:race");
		$stmt->bindParam("race", $race, \PDO::PARAM_STR);
		return $stmt->execute();
	}
}