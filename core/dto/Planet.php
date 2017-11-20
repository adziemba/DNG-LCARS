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

class Planet {
	private $planet_id;
	private $planet_name;
	private $planet_class;
	private $planet_position;
	private $planet_description;
	
	public function getPlanetId() {
		return $this->planet_id;
	}
	
	public function setPlanetID(int $planetid) {
		$this->planet_id = $planetid;
	}
		
	public function getPlanetName() {
		return $this->planet_name;
	}
	
	//Limit 64 Chars
	public function setPlanetName(string $planetName) {
		$this->planet_name = $planetName;
	}
	
	// ForeignKey PlanetClass
	public function getPlanetClass() {
		return $this->planet_class;
	}
	
	public function setPlanetClass(int $planetClass) {
		$this->planet_class = $planetClass;
	}
	
	public function getPlanetPosition() {
		return $this->planet_position;
	}
	
	//Limit 32 Chars
	public function setPlanetposition(string $planetPosition) {
		$this->planet_position = $planetPosition;
	}
	
	
	public function getPlanetDescription() : string {
		return $this->planet_description;
	}
	
	public function setPlanetDescription(string $description) {
		if($description==null) {
			$this->planet_description = "";
		}else {
			$this->planet_description = $description;
		}
	}
}