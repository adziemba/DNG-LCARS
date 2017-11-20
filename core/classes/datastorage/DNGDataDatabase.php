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

class DNGDataDatabase {
		
	protected  $pdo;
	
	protected $dsn 			= 'mysql:host=localhost;dbname=dngdata';
	protected $username 	= 'dng_data';
	protected $password 	= 'dng';
	
	protected $tables 	= array(
			'RACE'=>'race',
			'PLANET'=>'planets',
			'PLANETCLASS'=>'planet_class',
			'ALLIANCE'=>'alliance'
	);
	
	protected function __construct() {
		$pdoconn = new PDOConnection();
		$this->pdo = $pdoconn->getConn($this->dsn, $this->username, $this->password); //TODO Daten Auslagen in zentrale Datei
	}
	
	public function __destruct() {
		$this->pdo == null;
	}
}