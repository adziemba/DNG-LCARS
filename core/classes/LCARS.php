<?php
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

namespace core\classes;

use core\classes\config\Configuration;
use core\classes\datastorage\LcarsDatabase;
use core\classes\datastorage\RPGUserDatabase;
use core\classes\environment\Request;
use core\classes\logger\Logger;
use core\classes\logger\LogLevel;
use core\classes\routes\Router;
use core\classes\session\Session;
use core\classes\templateengine\Template;
use core\interfaces\app\ControllerInterface;
use module\main\controller\BaseController;

class LCARS {
	
	const VERSION 					= '0.1';

	public $loglvl 					= LogLevel::DEBUG;
	
	public static $isinitialized = false;
	
	public static $locale 	= 'de-de';
	public static $timezone = 'UTC';
	public static $encoding = 'UTF-8';
		
	private $config 		= array();
	private $request		= null;
	
	private  static $_instance = null;
	public static function instance() {
	
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new LCARS();
	
		}
	
		return self::$_instance;
	}
	final private function __construct() {}
	
	private $initdone = false; 	// Boolean true after successfull init
	
	//private $request; 	// Server Object
	private $dngcookie; // cookie array
		
	public function init() {
		
		if(static::$isinitialized) {
			Logger::getLogger()->error("FATAL ERROR: Initialized twice!!!!");
			throw new \Exception("You can't initialize twice");
		}
	
		Logger::getLogger()->setLogLevel($this->loglvl);
		
		$this->request = Request::instance();
		//$this->request->getTestInfo();
	
		
		RPGUserDatabase::instance()->loadAllURights();
		
		
		$lcarsdb =LcarsDatabase::instance();
		
		//load Configuratione
		$lcarsdb->loadConfig();
		$config = Configuration::instance();
		
		//load Routes
		$lcarsdb->loadRoutes();
		
		try {
			static::$timezone = $config->get('default_timezone') ?: date_default_timezone_get();
			date_default_timezone_set(static::$timezone);
		}catch(\Exception $e) {
			date_default_timezone_set('UTC');
			throw new \PHPErrorException($e->getmessage());
		}
		
		static::$encoding = $config->get('encoding', static::$encoding);
		mb_internal_encoding(static::$encoding);
		
		static::$locale = $config->get('locale', static::$locale);
				
		static::$isinitialized = true;

		// TODO Remove TestRoute
		$this->testRoute();
		
		$this->execute();
	}
	
	private function execute() {
		Logger::getLogger()->debug("Starting Executing Request for: ".$this->request->getUri());
		$router = Router::instance();
		$match = $router->parseRoute($this->request);
		
 		$class = $match->getClass();
 		
 		if( ($match->getName()!="design") && ($match->getName()!="images") && ($match->getName()!="logic") ) {
 			$this->initsession();
  		}
// 		else {
//  			Logger::getLogger()->debug("Load Design/Images/Logic - Skip Session");
//  		}
 		
		if ( ! class_exists($class, true))
		{
			Logger::getLogger()->warning("Class not Found");
			$class = "core\\errorpages\\Error404";
		}
		
		$controller = new $class;
		
		$matchame = $match->getName();
		if( ($matchame==="design") || ($matchame==="images") || ($matchame==="logic") ) {
			echo $controller->publish();
		}else {
			
			if($controller instanceof BaseController) {
				Logger::getLogger()->debug("Publish BaseController ".$matchame);
				ob_start();
				echo $controller->publish();
				header('Content-Length: '.ob_get_length());
				ob_end_flush();
			}else if($controller instanceof Template){
				Logger::getLogger()->debug("Publish Template ".$matchame);
				ob_start();
				echo $controller->parse(true);
				ob_end_flush();
			}else if($controller instanceof ControllerInterface) {
				Logger::getLogger()->debug("Publish ControllerInterface ".$matchame);
				ob_start();
				echo $controller->publish();
				header('Content-Length: '.ob_get_length());
				ob_end_flush();
			}
		}
	}
	
	public function testRoute() {
		$router = Router::instance();
		$router->addRoute("Testroute", "/test/:alnum", "TestController", "module\\forum\\controller\\", false);
		$match = $router->parseRoute($this->request);

	}
	
	public function initsession() {
			
		// Start und Check Session
		// Wenn keine Session vorhanden, GastSession erstellen
		// Wenn Session vorhanden, aktualisieren
		$session = Session::instance();
		$session->dng_session_start();
	}
		
	private function isMobile() {
	    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $this->request->getHeaderLine("user-agent"));
	}
}