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

class Userright {
	private $uright_id;
	private $uright;
	private $description;
	
	public function getUserRightId() {
		return $this->uright_id;
	}
	
	public function setuserRightID(int $userrightid) {
		$this->uright_id = $userrightid;
	}
	
	
	public function getUserRightName() {
		return $this->uright;
	}
	
	//Limit 32 Chars
	public function setUserRightName(string $uRightName) {
		$this->uright = $uRightName;
	}
	
	public function getDescription() : string {
		return $this->description;
	}
	
	//Limit 512 Chars
	public function setDescription(string $description) {
		if($description==null) {
			$this->description = "";
		}else {
			$this->description = $description;
		}
	}
	
	public function isRight(string $checkRight) {
		if(strtoupper($checkRight)==$this->uright) {
			return true;
		}
		return false;
	}
}