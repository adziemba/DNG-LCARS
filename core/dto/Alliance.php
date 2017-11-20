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

class Alliance {
	private $alliance_id;
	private $alliance_name;
	private $alliance_tag;
	private $alliance_description;
	
	public function getAllianceId() {
		return $this->alliance_id;
	}
	
	public function setAllianceID(int $allianceid) {
		$this->alliance_id = $allianceid;
	}
	
	
	public function getAllianceName() {
		return $this->alliance_name;
	}
	
	//Limit 128 Chars
	public function setAllianceName(string $allianceName) {
		$this->alliance_name = $allianceName;
	}
	
	public function getAllianceTag() {
		return $this->alliance_tag;
	}
	
	//Limit 16 Chars
	public function setAllianceTag(string $allianceTag) {
		$this->alliance_tag = $allianceTag;
	}
	
	public function getAllianceDescription() : string {
		return $this->alliance_description;
	}
	
	public function setAllianceDescription(string $description) {
		if($description==null) {
			$this->alliance_description = "";
		}else {
			$this->alliance_description = $description;
		}
	}
}