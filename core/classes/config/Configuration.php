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

class Configuration {
	
	private $config = array();
	
	private  static $_instance = null;
	
	public static function instance() {
	
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new Configuration();
	
		}
	
		return self::$_instance;
	}
	
	private function __construct() {}
	
	public function addItem($key, $value) {
		$new = array($key=>$value);
		$this->config = array_merge($this->config, $new);
	}
	
	public function addArray($configArray) {
		$this->config = array_merge($this->config, $configArray);
	}
	
	/**
	 * 
	 * @param unknown $key The config key
	 * @param unknown $default a default return value, if no key was found
	 * @return mixed|string
	 */
	public function get($key, $default = null) {
		$key = strtoupper($key);
		if(array_key_exists($key, $this->config)){
			return $this->config[$key];
		}
		return $default;
	}
	
	public function configExists($key) : bool {
		if(array_key_exists($key, $this->config)){
			return true;
		}
		return false;
	}
	
	public function removeItem($key) {
		if(array_key_exists($key, $this->config)){
			unset($this->config[$key]);
		}
	}
}