<?php
namespace core\classes\statistics;

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

class LoadTimer {
	
	private $starttime;
	
	
	private  static $_instance = null;
	
	public static function instance() {
	
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new LoadTimer();
	
		}
	
		return self::$_instance;
	}
	
	private function __construct() {
		$this->starttime = microtime(true);
	}
	
	public function getTime(){
		return round(microtime(true)-$this->starttime, 3);
	}
}