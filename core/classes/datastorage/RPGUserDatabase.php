<?php
namespace core\classes\datastorage;

use core\dto\User;
use core\dto\Character;
use core\classes\logger\Logger;
use core\dto\Userright;
use core\classes\config\GlobalRights;

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

class RPGUserDatabase extends RPGDatabase{	
	
	public static $_instance = null;
	
	public static function instance() {
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new RPGUserDatabase();
	
		}
		return self::$_instance;
	}
	
	public function getUser(int $userid) {
		$starttime = microtime(true);
		$stmt = $this->pdo->prepare("SELECT username, gender, email, registered, lastlogin, user_status, activeCharacter FROM ".$this->tables['USER']." WHERE user_id=:uid");
		$stmt->bindParam("uid", $userid, \PDO::PARAM_INT);
		$stmt->execute();
		$count = $stmt->rowCount();
		$data 	= $stmt->fetch(\PDO::FETCH_ASSOC);
		
		$user = new User(true);
		
		if($count==1) {
			$user = new User();
			$user->setUserID($userid);
			$user->setUsername($data['username']);
			$user->setGender($data['gender']);
			$user->setUserEMail($data['email']);
			$user->setRegisterDate($data['registered']);
			$user->setLastLoginTime($data['lastlogin']);
			$user->setStatus($data['user_status']);
			$user->setActiveCharID($data['activeCharacter']);
			if($data['activeCharacter'] > 0) {
				$user->setActiveChar($this->getCharakterDetails($data['activeCharacter'], $userid));
			}
		}
		$duration = round(microtime(true)-$starttime, 3);
		Logger::getLogger()->info("getUser took: ".$duration);
		return $user;
	}
	
	public function updateUser(User $user) : bool {
		$starttime = microtime(true);
		$stmt = $this->pdo->prepare("UPDATE ".$this->tables['USER']." SET email=:email, gender=:gender WHERE user_id=:uid");
		
		$stmt->bindParam("uid", $user->getUserID(), \PDO::PARAM_INT);
		$stmt->bindParam("email", $user->getUserID(), \PDO::PARAM_STR);
		$stmt->bindParam("gender", $user->getUserID(), \PDO::PARAM_STR);
		$res = $stmt->execute();
		
		$duration = round(microtime(true)-$starttime, 3);
		Logger::getLogger()->info("updateUser took: ".$duration);
		return $res;
	}
	
	public function deleteUser(int $uid) : bool {
		$starttime = microtime(true);
		
		$stmt 	= $this->pdo->prepare("DELETE FROM ".$this->tables['USER']." WHERE user_id=:uid");
		
		$stmt->bindParam("uid", $user->getUserID(), \PDO::PARAM_INT);
		$res 	= $stmt->execute();
		
		$duration = round(microtime(true)-$starttime, 3);
		Logger::getLogger()->info("deleteUser took: ".$duration);
		return $res;
	}
	
	public function getUserRightsDetail(int $uid) {
		$starttime = microtime(true);
		
		$stmt 	= $this->pdo->prepare("SELECT uright_id, uright, description 
				FROM ".$this->tables['USERASSIGNEDRIGHTS']." as ass 
				JOIN $this->tables['URIGHT'] as ur ON ass.uright_id = ur.uright_id 
				WHERE user_id=:uid");
		$stmt->bindParam("uid", $uid, \PDO::PARAM_INT);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		$userrights = array();
		foreach($data as $right) {
			$uright = new Userright();
			$uright->setuserRightID($right['uright_id']);
			$uright->setUserRightName($right['uright']);
			$uright->setDescription($right['description']);
			array_push($userrights, $uright);
		}
		
		$duration = round(microtime(true)-$starttime, 3);
		Logger::getLogger()->info("getUserRightsDetail took: ".$duration);
		return $userrights;
	}
	
	public function getUserRights(int $uid) {
		$starttime = microtime(true);
		
		$stmt = $this->pdo->prepare("SELECT uright 
				FROM ".$this->tables['USERASSIGNEDRIGHTS']." as ass 
				JOIN ".$this->tables['URIGHT']." as ur ON ass.uright_id = ur.uright_id
				WHERE user_id=:uid");
		$stmt->bindParam("uid", $uid, \PDO::PARAM_INT);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$userrights 	= $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
		
		$duration = round(microtime(true)-$starttime, 3);
		Logger::getLogger()->info("getUserRights took: ".$duration);
		return $userrights;
	}
	
	public function setUserRight(int $uid, int $urid) {
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->tables['USERASSIGNEDRIGHTS']." (user_id, uright_id) VALUES (:uid, :urid)");
		$stmt->bindParam("uid", $uid, \PDO::PARAM_INT);
		$stmt->bindParam("urid", $urid, \PDO::PARAM_INT);
		return $stmt->execute();
	}
	
	public function removeUserRight(int $uid, int $urid) {
		$stmt = $this->pdo->prepare("DELETE FROM ".$this->tables['USERASSIGNEDRIGHTS']." WHERE user_id=:uid AND uright_id=:urid");
		$stmt->bindParam("uid", $uid, \PDO::PARAM_INT);
		$stmt->bindParam("urid", $urid, \PDO::PARAM_INT);
		return $stmt->execute();
	}
	
	public function loadAllURights() {
		$starttime = microtime(true);
		
		$stmt 	= $this->pdo->prepare("SELECT uright_id, uright, description FROM ".$this->tables['URIGHT']);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetchAll();
		
		$userrights = GlobalRights::instance();
		foreach($data as $right) {
			$uright = new Userright();
			$uright->setuserRightID($right['uright_id']);
			$uright->setUserRightName($right['uright']);
			$uright->setDescription($right['description']);
			
			$userrights->addURight($right['uright'], $uright);
			//array_push($userrights, $uright);
		}
		
		//$duration = round(microtime(true)-$starttime, 3);
		//Logger::getLogger()->info("getAllURights took: ".$duration);
		return $userrights;
	}
	
	public function setNewURight(Userright $newURight) {
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->tables['URIGHT']." (uright, description) VALUES (:uright, :desc)");
		$stmt->bindParam("uright", $newURight->getUserRightName(), \PDO::PARAM_STR);
		$stmt->bindParam("desc", $newURight->getDescription(), \PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	public function getAllCharactersFromUser(int $userid) {
		$stmt = $this->pdo->prepare("SELECT character_id, char_sfnumber, char_name, char_surname FROM ".$this->tables['CHAR']." WHERE owner_id=:uid");
		$stmt->bindParam("uid", $ownerid, \PDO::PARAM_STR);
		$stmt->bindParam("cid", $charid, \PDO::PARAM_STR);
		$stmt->execute();
		$count 	= (int)$stmt->rowCount();
		$data 	= $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		$chars	= array();
		foreach($data as $char) {
			array_push($chars, $this->createCharacterFromAssocData($char));
		}
		
		return $chars;
	}
	
	public function getCharakterDetails(int $charid, int $ownerid) {
		$stmt 	= $this->pdo->prepare("SELECT character_id, char_sfnumber, char_name, char_surname, gender FROM ".$this->tables['CHAR']." WHERE character_id=:cid AND owner_id=:uid");
		$stmt->bindParam("uid", $ownerid, \PDO::PARAM_STR);
		$stmt->bindParam("cid", $charid, \PDO::PARAM_STR);
		$stmt->execute();
		$count 	= $stmt->rowCount();
		$data 	= $stmt->fetch(\PDO::FETCH_ASSOC);
		
		$character = null;
		
		if($count==1) {
			$character = $this->createCharacterFromAssocData($data);
		}
		
		return $character;
	}
	
	protected function createCharacterFromAssocData($data) {
		$character = new Character();
		$character->setCharID($data['character_id']);
		$character->setName($data['char_name']);
		$character->setSurname($data['char_surname']);
		$character->setSFNumber($data['char_sfnumber']);
		$character->setGender($data['gender']);
		
		return $character;
	}
}