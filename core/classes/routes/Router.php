<?php
namespace core\classes\routes;

use core\errorpages\Error404;
use core\interfaces\environment\RequestInterface;

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

class Router {
	
	private $routes = array();
	
	//Singleton
	private  static $_instance = null;
	public static function instance() {
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new Router();
		}
		return self::$_instance;
	}
	private function __construct() {}
		
	public function addRoute(string $name, string $path, string $controller_class, string $controller_path, bool $case_sensitive) {
		$route = new Route($name, $path, $controller_class, $controller_path, $case_sensitive);
		array_push($this->routes, $route);
	}
	
	public function getRouteByName(string $rname) {
		$match = null;
		foreach($this->routes as $route) {
			if ($match = $route->compareName($rname))
			{
				break;
			}
		}
		return $match;
	}
	
	public function parseRoute(RequestInterface $request) : Route {
		$match = null;
		
		foreach($this->routes as $route) {
			if ($match = $route->parse($request))
			{
				break;
			}
		}
		if($match == null) {
			$match = $this->getRouteByName("standard");
			if($match == null) {
				$match = new Route("", "", "Error404", "core\\errorpages\\");
			}
		}
		
		return $match;		
	}
	
// 	//TODO
// 	public function parseMatch(Route $match) {
		
// 	}
}