<?php
namespace core\classes\datastorage;

use core\dto\User;

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

class LoginDatabase extends RPGDatabase {
	
	public static $_instance = null;

	// Singleton Pattern
	public static function instance() {
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new LoginDatabase();
	
		}
		return self::$_instance;
	}
	
	public function getLoginData(string $usernameEmail) {
		$stmt = $this->pdo->prepare("SELECT user_id, password, lastlogin FROM ".$this->tables['USER']." WHERE (username=:usernameEmail OR email=:usernameEmail)");
		$stmt->bindParam("usernameEmail", $usernameEmail, \PDO::PARAM_STR);
		$stmt->execute();
		
		$count = $stmt->rowCount();
		$data = $stmt->fetch(\PDO::FETCH_OBJ);

		if($count) {
			return $data;
		}
		return false;
	}
	
	public function getPassword(string $uid) {
		$stmt = $this->pdo->prepare("SELECT password FROM ".$this->tables['USER']." WHERE user_id=:userid");
		$stmt->bindParam("userid", $uid, \PDO::PARAM_INT);
		$stmt->execute();
		$count = $stmt->rowCount();
		$data=$stmt->fetch(\PDO::FETCH_OBJ);
		
		if($count==1) {
			return $data;
		}
		return false;
	}
	
	public function checkUserEmailExists(string $username, string $email) : bool {
		$stmt 	= $this->pdo->prepare("SELECT user_id FROM ".$this->tables['USER']." WHERE (username=:username OR email=:email)");
		$stmt->bindParam("username", $username, \PDO::PARAM_STR);
		$stmt->bindParam("email", $email, \PDO::PARAM_STR);
		$stmt->execute();
		$count 	= (int)$stmt->rowCount();
	
		if($count == 1) {
			return true;
		}else {
			return false;
		}
	}
	
	public function checkUseridExists(int $userid) {
		$stmt = $this->pdo->prepare("SELECT cookieencoding FROM ".$this->tables['USER']." WHERE user_id=:userid");
		$stmt->bindParam("userid", $userid, \PDO::PARAM_INT);
		$stmt->execute();
		$count = (int)$stmt->rowCount();
		$data 	= $stmt->fetch(\PDO::FETCH_ASSOC);
		
		if($count == 1) {
			return $data['cookieencoding'];
		}else {
			return false;
		}
	}
	
	public function insertNewUser(string $username, string $db_password, string $email, string $gender){
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->tables['USER']." (username, gender, email, password) VALUES (:username, :gender, :email, :password)");
		$stmt->bindParam("username", $username, \PDO::PARAM_STR);
		$stmt->bindParam("email", $email, \PDO::PARAM_STR);
		$stmt->bindParam("password", $db_password, \PDO::PARAM_STR);
		$stmt->bindParam("gender", $gender, \PDO::PARAM_STR);
		$stmt->execute();
		
		$uid = $this->pdo->lastInsertId();
		
		return $uid;
	}
	
	public function setLoginTime(string $uid) {
		$stmt = $this->pdo->prepare("UPDATE ".$this->tables['USER']." SET lastlogin=DEFAULT WHERE user_id=:uid");
		$stmt->bindParam("uid", $uid, \PDO::PARAM_INT);
		return $stmt->execute();
	}
		
	public function setAutoLogin(int $userid, string $cookieencoding) {
		$stmt = $this->pdo->prepare("UPDATE ".$this->tables['USER']." SET cookieencoding=:cookieencoding WHERE user_id=:uid");
		$stmt->bindParam("cookieencoding", $cookieencoding, \PDO::PARAM_STR);
		$stmt->bindParam("uid", $userid, \PDO::PARAM_INT);
		return $stmt->execute();
	}
	
	public function getUserLoginDetail(int $userid) {
		$stmt = $this->pdo->prepare("SELECT username, password FROM ".$this->tables['USER']." WHERE user_id=:uid");
		$stmt->bindParam("uid", $userid, \PDO::PARAM_STR);
		$stmt->execute();
		$count = $stmt->rowCount();
		$data 	= $stmt->fetch(\PDO::FETCH_ASSOC);
		
		if($count==1) {
			return $data;
		}
		return false;
	}
	
	public function setLoginAttemp(int $userid) {
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->tables['TRIES']." SET user_id=:uid");
		$stmt->bindParam("uid", $userid, \PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function getLoginAttemps(int $userid) {
		$valid_attemps = time() - (60*60); // attempts in the last hour
		
		$stmt = $this->pdo->prepare("SELECT time FROM ".$this->tables['TRIES']." WHERE user_id =:uid AND UNIX_TIMESTAMP(time) > :valid_attempts");
		$stmt->bindParam("uid", $userid, \PDO::PARAM_INT);
		$stmt->bindParam("valid_attempts", $valid_attemps, \PDO::PARAM_INT);
		$stmt->execute();
		$count = (int)$stmt->rowCount();
		
				
		if($count > 5) {
			$this->clearOldAttempts();
			//echo "true $count<br>";
			return true;
		}else {
			//echo "false $count<br>";
			return false;
		}
	}
	
	public function clearOldAttempts() {
		$valid_attemps = time() - (2*60*60);
		$stmt = $this->pdo->prepare("DELETE FROM ".$this->tables['TRIES']." WHERE UNIX_TIMESTAMP(time) < :valid_attempts");
		$stmt->bindParam("valid_attempts", $valid_attemps, \PDO::PARAM_INT);
		$stmt->execute();
	}
}