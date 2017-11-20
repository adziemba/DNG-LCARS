<?php
namespace core\classes\datastorage;

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
class RPGDatabase {
	
	protected  $pdo;
	
	protected $dsn 			= 'mysql:host=localhost;dbname=dnguser';
	protected $username 	= 'dng_secure';
	protected $password 	= 'dng';
	
	protected $tables 	= array(
			'CHAR'=>'sfcharacter',
			'USER'=>'user', 
			'TRIES'=>'login_attempts',
			'URIGHT'=>'userrights',
			'USERASSIGNEDRIGHTS' => 'assigned_user_rights'
			
	);
	
	protected function __construct() {
		$pdoconn = new PDOConnection();
		$this->pdo = $pdoconn->getConn($this->dsn, $this->username, $this->password);
	}
	
	public function __destruct() {
		$this->pdo == null;
	}
}