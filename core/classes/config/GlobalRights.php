<?php
namespace core\classes\config;

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

class GlobalRights {
	
	private $userrights 		= array();
	private $characterrights 	= array();
	
	private  static $_instance 	= null;
	
	public static function instance() {
	
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new GlobalRights();
	
		}
	
		return self::$_instance;
	}
	
	private function __construct() {}
	
	public function addURight($key, $value) {
		$new = array($key=>$value);
		$this->userrights = array_merge($this->userrights, $new);
	}
	
	public function addCRight($key, $value) {
		$new = array($key=>$value);
		$this->characterrights = array_merge($this->characterrights, $new);
	}
	
	public function removeURight($key) {
		if(array_key_exists($key, $this->userrights)){
			unset($this->userrights[$key]);
		}
	}
	
	public function removeCRight($key) {
		if(array_key_exists($key, $this->characterrights)){
			unset($this->characterrights[$key]);
		}
	}
	
	public function get($key, $default = null) {
		$key = strtoupper($key);
		if(array_key_exists($key, strtoupper($this->userrights))){
			return $this->userrights[$key];
		}
		if(array_key_exists($key, strtoupper($this->characterrights))){
			return $this->characterrights[$key];
		}
		return $default;
	}
	
}