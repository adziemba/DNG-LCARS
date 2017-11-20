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

class PlanetClass {
	private $planet_class;
	private $class_description;
		
	public function getPlanetClass() {
		return $this->$planet_class;
	}
	
	//Limit 4 Chars
	public function setPlanetClass(string $planetClass) {
		$this->planet_class = $planetClass;
	}
	
	public function getPlanetClassDescription() : string {
		return $this->class_description;
	}
	
	public function setPlanetClassDescription(string $description) {
		if($description==null) {
			$this->class_description = "";
		}else {
			$this->class_description = $description;
		}
	}
}