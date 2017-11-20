<?php
namespace core\classes\logger;
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

class Logger {
	
	private $lvl;
	
	private $endline = "\n";
	
	private  static $_instance = null;
	public static function getLogger() {
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new Logger();
		}
		return self::$_instance;
	}
	private function __construct() {
		$this->lvl = LogLevel::WARNING;
	}
	
	public function setLogLevel($lvl){
		$this->lvl = $lvl;
	}
	
	public function error(string $msg) {
		if($this->lvl <= LogLevel::ERROR){
			$this->writeLog($this->logStart()."ERROR ".$msg.$this->endline);
		}
	}
	
	public function warning(string $msg) {
		if($this->lvl <= LogLevel::WARNING){
			$this->writeLog($this->logStart()."WARN ".$msg.$this->endline);
		}
	}
	
	public function info(string $msg) {
		if($this->lvl <= LogLevel::INFO){
			$trace = debug_backtrace();
			$this->writeLog($this->logStart()."INFO ".$msg.$this->endline);
		}
	}
	
	public function debug(string $msg) {
		if($this->lvl <= LogLevel::DEBUG){
			$this->writeLog($this->logStart()."DEBUG ".$msg.$this->endline);
		}
	}
	
	private function logStart() : string {
		$trace = debug_backtrace();
		return "[".date("d.m.Y H:i:s", time())." ".$trace[2]['class']."] ";
	}
	
	private function writeLog(string $log) {
		$dir = ROOTDIR.'logs'.DIRECTORY_SEPARATOR;
		$file = $dir.'server.log';
		
		if(!is_dir($dir)) {
			mkdir($dir);
		}
		
		file_put_contents($file, $log, FILE_APPEND | LOCK_EX);
	}

}
