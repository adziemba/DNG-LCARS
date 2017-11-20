<?php
namespace core\classes\session;

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

use core\classes\config\Configuration;
use core\classes\datastorage\LoginDatabase;
use core\classes\datastorage\RPGUserDatabase;
use core\classes\environment\Request;
use core\dto\User;
use core\classes\logger\Logger;

class Session {
	
	private $key = "8CxXotAUpfCAfhJyyb6uNSaAizyqYOsYsZChs9Y54vFrZpDa5hw4loZ4YtJD43eLwEmgYZcpHWmGIAXh";
	
	private $config;
	private $request;
	
	private $loggedUser;
	
	private $urights = array();
	
	private  static $_instance = null;
	
	public static function instance() {
	
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new Session();
	
		}
	
		return self::$_instance;
	}
	
	private function __construct() {
		$this->config = Configuration::instance();
		$this->key = pack("H", $this->key);
		$this->request = Request::instance();	
		$this->loggedUser = new User(true);
	}
	
	public function dng_session_start() {
		$session_name = $this->config->get('SESSION_NAME');
		//echo "Session Start <br>";
		$secure 	= true;
		$httponly 	= true;
		
		if(ini_set('session.use_only_cookies', 1) === false ) {
			echo "Need Cookies only poperty";
			exit();
		}
		
		$cookieParams = session_get_cookie_params();
		session_set_cookie_params(	$cookieParams["lifetime"],
									$cookieParams["path"],
									$cookieParams["domain"],
									$secure,
									$httponly);
		session_start();
		session_regenerate_id(true);
	
		
		if($this->login_check()){
			//Logger::getLogger()->debug("reLogincheck !");
			$this->setSessionUser();
		}else {
			if($this->checkAutoLogin()) {
				//Logger::getLogger()->debug("AutoLogincheck !");
				$this->setSessionUser();
			}else {
				//echo "Gast<br>";
			}
		}
	}
	
	private function setSessionUser() {
		$this->setUser($_SESSION['user_id']);
	}
	
	private function setUser(int $userid) {
		if($userid>0) {
			$userdb = RPGUserDatabase::instance();
			$this->loggedUser = $userdb->getUser($userid);
			Logger::getLogger()->debug("User ".$this->loggedUser->getUsername()." logged in");
			if($this->loggedUser->getActiveCharID() != null) {
				//$this->loggedUser->setActiveChar($logindb->getCharakterDetails($this->loggedUser->getActiveCharID(), $this->loggedUser->getUserID()));
				Logger::getLogger()->debug("With Char: ".$this->loggedUser->getActiveChar()->getFullName());
			}else {
				Logger::getLogger()->debug("Witout CHAR!!");
			}
			
			$this->urights = $userdb->getUserRights($userid);
		}
	}
	
	public function dng_session_close() {
		$_SESSION = array();
		$params = session_get_cookie_params();
		
		// Delete the actual cookie.
		setcookie(session_name(),
				'', time() - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]);
		
		// Destroy session
		session_destroy();
	}
	
	//TODO Fehlermeldungen richtig erzeugen bei falschen angaben....
	public function register(string $username, string $pass, string $email, string $gender){
		
		$username 		= filter_var($username, FILTER_SANITIZE_STRING);
		$email 			= filter_var($email, FILTER_SANITIZE_EMAIL);
		$email 			= filter_var($email, FILTER_VALIDATE_EMAIL);
		$gender 		= filter_var($gender, FILTER_SANITIZE_STRING);
		//$pass 			= filter_var($pass, FILTER_SANITIZE_STRING);
		
		$usernameCheck 	= preg_match('~^[A-Za-z0-9_]{6,30}$~i', $username);
		// Pass changed to 128Bit Verschlüsselung. Es wird Serverseitig kein reintext passwort mehr empfangen
		//$passCheck 	= preg_match('~^[A-Za-z0-9!@#$%^&*()_]{6,20}$~i', $pass);
		if(strlen($pass) != 128) {
			return "Bei der Passwordübertragung ist etwas schief gelaufen!";
		}
		$emailCheck		= filter_var($email, FILTER_VALIDATE_EMAIL);
		$genderCheck	= ($gender =="m" || $gender == "w")?true:false;
		Logger::getLogger()->debug("Gender : ".$gender." - ".$genderCheck);
		
		if($usernameCheck && $emailCheck && $genderCheck) {
			$logindb = LoginDatabase::instance();
			if($logindb->checkUserEmailExists($username, $email)) {
				return "User oder Email schon vorhanden!";
			}else {
				Logger::getLogger()->debug("Hashing Pasword");
				$password = password_hash($pass, PASSWORD_BCRYPT);
				Logger::getLogger()->debug("Sending Data to DB");
				$uid = $logindb->insertNewUser($username, $password, $email, $gender);
				Logger::getLogger()->info("New User registered: ".$username);
				$this->login($username, $pass);
				return '';
			}
		}
		return "Something happend during registration";
	}
	
	public function login(string $username, string $password, bool $remember=false ) {
		//echo "Session login Start <br>";
		$user_browser = $this->request->getHeader("user-agent");
		$logindb = LoginDatabase::instance();
		
		$password 			= filter_var($password, FILTER_SANITIZE_STRING);
		if($user = $logindb->getLoginData($username)) {
			if($logindb->getLoginAttemps($user->user_id)) { 
				$this->logout();
				Logger::getLogger()->info("Too many login attempts for user_id: ".$user->user_id);
				return false;
			}else {
				
				$userid 		= $user->user_id;
				$db_password 	= $user->password;
				$lastlogin 		= $user->lastlogin;
				if(password_verify($password, $db_password)){
					// XSS protection as we might print this value
					$userid = preg_replace("/[^0-9]+/", "", $userid);
					$_SESSION['user_id'] = $userid;
					
					$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
					$_SESSION['username'] = $username;
					
					$_SESSION['login_hash'] = hash('sha512', $db_password . $user_browser);
					
					//echo "Login check: $userid : $username <br>";
					
					if($remember) {
						$rememberme = new RememberMe($this->key);
						$rememberme->remember($userid);
					}
					
					$this->setUser($userid);
					
					$logindb->setLoginTime($userid);
					
					return true; //Logged IN
				}else {
					
					$this->logout();
					$logindb->setLoginAttemp($userid);
					return false;
				}
			}
		}else {
			// NO User found
			return false;
		}

	}
	
	public function login_check() {
		$logindb = LoginDatabase::instance();
		$user_browser = $this->request->getHeader("user-agent");
		if(isset($_SESSION['user_id'], $_SESSION['login_hash'])) {
			
			$user_id	= $_SESSION['user_id'];
			$login_hash = $_SESSION['login_hash'];
			
			if($dbpassword = $logindb->getPassword($user_id)) {
				$dbpassword = $dbpassword->password;
				$login_check = hash('sha512', $dbpassword . $user_browser);
				
				if (hash_equals($login_check, $login_hash) ){
					// Logged In!!!!
					return true;
				} else {
					// Not logged in
					return false;
				}
				// Not logged in
				return false;
			}
			// Not logged in
			return false;
		}
		// Not logged in
		return false;
	}
	
	public function checkAutoLogin() {
		$logindb = LoginDatabase::instance();
		$user_browser = $this->request->getHeader("user-agent");
		
		$rememberMe = new RememberMe($this->key);
		try {
			if($data = $rememberMe->auth()) {
				// automatic login
				$userdata = $logindb->getUserLoginDetail($data['user']);
				$_SESSION['user_id'] = $data['user'];
				$_SESSION['username'] = $userdata['username'];
				$_SESSION['login_hash'] = hash('sha512', $userdata['password'] . $user_browser);
			
				$rememberme = new RememberMe($this->key);
				$rememberme->remember($data['user']);
				
				
				return true;
			}
		} catch (\Exception $e) {
			Logger::getLogger()->error($e->getMessage());
			//echo $e->getMessage()."<br>";
			return false;
		}
		
		return false;
	}
	
	public function logout() {
		$this->dng_session_close();
		
		$cookiename = (string) $this->config->get('COOKIE_NAME');
		//$servcookie = $this->request->getCookieParams();
		
		setcookie($cookiename, "", time()-1000, '/');
		$this->loggedUser = new User(true);
	}
	
	public function getUser() : User {
		return $this->loggedUser;
	}
	
	public function hasURight(string $right) {
		foreach ($this->urights as $value) {
			if(strtoupper($value)===strtoupper($right)) {
				return true;
			}
		}
		
		return false;
	}
	
	public function isLoggedIn() : bool{
		if($this->getUser()->getUserID() > 0) {
			return true;
		}
		return false;
	}
}