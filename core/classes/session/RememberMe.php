<?php
namespace core\classes\session;

use core\classes\datastorage\LoginDatabase;
use core\classes\config\Configuration;
use core\classes\environment\Request;

class RememberMe {
	private $key = null;
	private $db;
	private $config;
	private $cookiename;
	private $request;

	function __construct($privatekey) {
		$this->key 		= $privatekey;
		$this->db 		= LoginDatabase::instance();
		$this->config	= Configuration::instance();
		$this->cookiename = (string) $this->config->get('COOKIE_NAME');
		$this->request 	= Request::instance();
	}

	public function auth() {
		$servcookie = $this->request->getCookie();

		// Check if remeber me cookie is present
		if (! isset($servcookie[$this->cookiename]) || empty($servcookie[$this->cookiename])) {
			return false;
		}

		// Decode cookie value
		if (! $cookie = @json_decode($servcookie[$this->cookiename], true)) {
			return false;
		}

		// Check all parameters
		if (! (isset($cookie['user']) || isset($cookie['token']) || isset($cookie['signature']))) {
			return false;
		}

		$var = $cookie['user'] . $cookie['token'];

		// Check Signature
		if (! $this->verify($var, $cookie['signature'])) {
			throw new \Exception("Cookies has been tampared with");
		}

		// Check Database 
		$info = $this->db->checkUseridExists($cookie['user']);
		if (! $info) {
			return false; // User must have deleted accout
		}

		// Check User Data
		if (! $info = json_decode($info, true)) {
			throw new \Exception("User Data corrupted");
		}

		// Verify Token //TODO �berlegen wie man damit umgeht?
 		if ($info['token'] !== $cookie['token']) {
 			throw new \Exception("System Hijacked or User use another browser");
 		}

		/**
		 * Important
		 * To make sure the cookie is always change
		 * reset the Token information
		 */

		$this->remember($info['user']);
		return $info;
	}

	public function remember($userid) {
		$cookie = [
				"user" => $userid,
				"token" => $this->getRand(64),
				"signature" => null
		];
		$cookie['signature'] = $this->hash($cookie['user'] . $cookie['token']);
		$encoded = json_encode($cookie);

		// Add User to database //TODO add real db insert
		$this->db->setAutoLogin($userid, $encoded);
		
		/**
		 * Set Cookies
		 * In production enviroment Use
		 * setcookie($this->cookiename, $encoded, time() + $expiration, "/~root/",
		 * "example.com", 1, 1);
		 */
		$expiration = 86499*30; //TODO set in config
		$path	= "/";
		$domain = $this->request->getHeaderLine("host");
		if(setcookie($this->cookiename, $encoded, time() + $expiration, $path, $domain, 1, 1)){
		}
	}

	public function verify($data, $hash) {
		$rand = substr($hash, 0, 4);
		return $this->hash($data, $rand) === $hash;
	}

	private function hash($value, $rand = null) {
		$rand = $rand === null ? $this->getRand(4) : $rand;
		return $rand . bin2hex(hash_hmac('sha256', $value . $rand, $this->key, true));
	}

	private function getRand($length) {
		switch (true) {
			case function_exists("mcrypt_create_iv") :
				$r = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
				break;
			case function_exists("openssl_random_pseudo_bytes") :
				$r = openssl_random_pseudo_bytes($length);
				break;
			case is_readable('/dev/urandom') : // deceze
				$r = file_get_contents('/dev/urandom', false, null, 0, $length);
				break;
			default :
				$i = 0;
				$r = "";
				while($i ++ < $length) {
					$r .= chr(mt_rand(0, 255));
				}
				break;
		}
		return substr(bin2hex($r), 0, $length);
	}
}