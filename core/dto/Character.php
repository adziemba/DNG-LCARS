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

class Character {
	private $charID;
	private $name;
	private $surname;
	private $gender;
	private $race;
	
	private $sfnumber;
	
	private $rank;
	
	private $position;
	
	private $charrights;
	
	public function getCharID() : int {
		return $this->charID;
	}
	
	public function setCharID(int $id) {
		$this->charID = $id;
	}
	
	public function getFullName() : string {
		return $this->name." ".$this->surname;
	}
	
	public function getName() : string {
		return $this->name;
	}
	
	public function setName(string $name) {
		$this->name = $name;
	}
	
	public function getSurname() : string {
		return $this->surname;
	}
	
	public function setSurname(string $surname) {
		$this->surname = $surname;
	}
	
	public function getGender() {
		return $this->gender;
	}
	
	public function getGenderLong() {
		switch($this->gender) {
			case 'm':
				return 'männlich';
				break;
			case 'w':
				return 'weiblich';
				break;
			case 'z':
				return 'zwitter';
				break;
			case 'u':
			default:
				return 'undefined';
				break;
		}
	}
	
	public function setGender(string $gender) {
		$this->gender = $gender;
	}
	
	public function getRace() {
		return $this->race;
	}
	
	public function setRace(string $race) {
		$this->race = $race;
	}
	
	public function getSFNumber() : string {
		return $this->sfnumber;
	}
	
	public function setSFNumber(string $sfnumber) {
		$this->sfnumber = $sfnumber;
	}
	
	public function getYearOfBirth() {
		return '2359'; // TODO fill with data!!!
	}
	
	public function getMonthOfBirth() {
		return '05'; // TODO fill with data!!!
	}
	
	public function getDayOfBirth() {
		return '14'; // TODO fill with data!!!
	}
	
	// A lot more
}