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

class User {
	private $uid;
	private $username;
	private $gender;
	private $useremail;
	private $registered;
	private $lastlogin;
	private $user_status;
	
	private $chars 		= array();
	
	private $activeCharID;
	private $activeChar = null;
	
	public function __construct(bool $gast = false) {
		if($gast) {
			$this->setUserID(0);
			$this->setUsername("Gast");
		}
	}
	
	public function getUserID() : int {
		return $this->uid;
	}
	
	public function setUserID(int $uid) {
		$this->uid = $uid;
	}
		
	public function getUsername() : string {
		return $this->username;
	}
	
	public function setUsername(string $value) {
		$this->username = $value;
	}
	
	public function getGenderShort() {
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
			default:
				return 'undefined';
				break;
		}
	}
	
	public function setGender(string $gender) {
		$this->gender = $gender;
	}
	
	public function getUserEMail() : string {
		return $this->useremail;
	}
	
	public function setUserEMail(string $email) {
		$this->useremail = $email;
	}
	
	public function getRegisterDate() : int {
		return $this->registered;
	}
	
	public function setRegisterDate($registerDate) {
		$this->registered = $registerDate;
	}
	
	public function getLastLoginTime() : int {
		return $this->lastlogin;
	}
	
	public function setLastLoginTime($lastlogin) {
		$this->lastlogin = $lastlogin;
	}
	
	public function getStatus() {
		return $this->user_status;
	}
	
	public function setStatus($userstatus) {
		$this->user_status = $userstatus;
	}
	
	public function getChars() : array {
		return $this->chars;
	}
	
	public function setChars(array $chars) {
		$this->chars = $chars;
	}
	
	public function getActiveCharID() : int {
		return $this->activeCharID;
	}
	
	public function setActiveCharID(int $id) {
		$this->activeCharID = $id;
	}
	
	public function getActiveChar() {
		return $this->activeChar;
	}
	
	public function setActiveChar($char) {
		$this->activeChar = $char;
	}
}