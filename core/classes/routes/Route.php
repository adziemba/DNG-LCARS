<?php
namespace core\classes\routes;

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

class Route {
	
	private $segment = array();
	private $methods = array();
	private $path = '';
	
	private $name 				= null;
	private $namespace 			= null;
	private $controller_class 	= null;
	
	private $case_sensitive;
	
	private $named_params 		= array();
	private $regularPath 		= null;
	
	/*
	 * @params $name Name Identifier
	 * @params $path The URI on which the controller will be called
	 * @params $module The module in which the controller works
	 */
	public function __construct(string $name, string $path, string $controller_class, string $namespace, bool $case_sensitive = false) {
		$this->name 			= $name;
		$this->path 			= $path;
		$this->controller_class = $controller_class;
		$this->namespace		= $namespace;
		$this->case_sensitive 	= $case_sensitive;
		
		$this->regularPath		= $this->preparePath();
	}
	
	public function parse(RequestInterface $request) {
		$uri 	= $request->getUri();
		$method	= $request->getMethod();
		
		$result = $this->parseSearch($uri, null, $method);
		
		if ($result)
		{
			return $result;
		}
		
		return false;
	}
	
	public function compareName(string $rname) {
		if(strcasecmp($this->name, $rname)==0) {
			return $this;
		}else {
			return false;
		}
	}
	
	private function preparePath() {
		if($this->path === '_root_') {
			return '';
		}
		
		$regReplace = str_replace(array(
				':any',
				':everything',
				':alnum',
				':num',
				':alpha',
				':segment',
		), array(
				'.+',
				'.*',
				'[[:alnum:]]+',
				'[[:digit:]]+',
				'[[:alpha:]]+',
				'[^/]*',
		), $this->path);
		
		return preg_replace('#(?<!\[\[):([a-z\_]+)(?!:\]\])#uD', '(?P<$1>.+?)', $regReplace);
	}
	
	private function parseSearch($uri, $route = null, $method = null) {
		if($route===null) {
			$route = $this;
		}
		
		if ($this->case_sensitive) {
			$result = preg_match('#^'.$route->regularPath.'$#uD', $uri, $params);
		}
		else {
			$result = preg_match('#^'.$route->regularPath.'$#uiD', $uri, $params);
		}
		
		if($result === 1) {
			return $route->matched($uri, $params);
		}
		
		return false;
	}
	
	private function matched($uri = '', $named_params = array()) {
		foreach($named_params as $key => $val)
		{
			if (is_numeric($key))
			{
				unset($named_params[$key]);
			}
		}
		
		$this->named_params = $named_params;

		if($uri != '') {
			
			if ($this->case_sensitive)
			{
				$path = preg_replace('#^'.$this->regularPath.'$#uD', $this->path, $uri);
			}
			else
			{
				$path = preg_replace('#^'.$this->regularPath.'$#uiD', $this->path, $uri);
			}
		}
		$this->segment = explode('/', trim($path, '/'));
		
		return $this;
	}
	
	public function getClass() {
		return $this->namespace."".$this->controller_class;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getNamespace() {
		return $this->namespace;
	}
	
	public function getControllerClass() {
		return $this->controller_class;
	}
	
	public function getSegments() {
		return $this->segment;
	}
}