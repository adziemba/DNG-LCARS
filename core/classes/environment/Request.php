<?php
namespace core\classes\environment;

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
class Request implements RequestInterface{
	private $GET;
	private $POST;
	private $COOKIE;
	private $REQUEST;
	private $FILES;
	private $SERVER;
	//private $SESSION;
	
	private $headers = array();
	private $uri;
	private $fragment;
	
	private  static $_instance = null;
	
	public static function instance() {
	
		if ( !isset( self::$_instance ) ) {
	
			self::$_instance = new Request();
	
		}
	
		return self::$_instance;
	}
	
	private function __construct() {
		$this->GET		= $_GET;
		$this->POST		= $_POST;
		$this->COOKIE	= $_COOKIE;
		$this->REQUEST	= array_merge($this->GET,$this->POST,$this->COOKIE);
		$this->FILES	= $_FILES;
		
		$this->SERVER 	= $_SERVER;
		//TODO Validate and Check Globald (e.g. Strip Slashes? or something like that
		
		$this->parseUri();
		$this->marshalHeaders();
	}
	
	public function getUri() : string {
		return $this->uri;
	}
	
	public function getQuery() {
		return $this->SERVER['QUERY_STRING'];
	}
	
// 	public function getFragment() {
// 		return $this->fragment;
// 	}
	
	public function getMethod() {
		return $this->SERVER['REQUEST_METHOD'];
	}
	
	public function get(string $key, string $default='') {

		if(array_key_exists($key, $this->REQUEST)) {
			return $this->REQUEST[$key];
		}
		
		return $default;
	}
	
	public function getHeader(string $header) : string {
		$header = strtolower($header);
		if (array_key_exists($header, $this->headers)) {
			$value = is_array($this->headers[$header]) ? implode(', ', $this->headers[$header]) : $this->headers[$header];
			return $value;
		}
		return '';
	}
	
	public function getCookie() {
		return $this->COOKIE;
	}
	
	public function getTestInfo() {
		echo "<br>SERVER:<br>";
		foreach ($_SERVER as $k => $v) {
			echo "- ".$k." = ".$v."<br>";
		}
		echo "<br>GET:<br>";
		foreach ($this->GET as $k => $v) {
			echo "- ".$k." = ".$v."<br>";
		}
		echo "<br>POST:<br>";
		foreach ($this->POST as $k => $v) {
			echo "- ".$k." = ".$v."<br>";
		}
		echo "<br>COOKIE:<br>";
		foreach ($this->COOKIE as $k => $v) {
			echo "- ".$k." = ".$v."<br>";
		}
		echo "<br>Headers:<br>";
		foreach ($this->headers as $k => $v) {
			echo "- ".$k." = ".$v."<br>";
		}
		
		echo "<br>URI<br>";
		print_r(parse_url($_SERVER['REQUEST_URI']));
	}
	
	private function parseUri() {
		$uri = parse_url($_SERVER['REQUEST_URI']);
		$this->uri = $uri['path'];
		if(array_key_exists('fragment', $uri)) {
			$this->fragment = $uri['fragment'];
		}
	}
	
	private function marshalHeaders()
	{
		foreach ($this->SERVER as $key => $value) {
			if (strpos($key, 'HTTP_COOKIE') === 0) {
				// Cookies are handled using the $_COOKIE superglobal
				continue;
			}
	
			if ($value && strpos($key, 'HTTP_') === 0) {
				$name = strtr(substr($key, 5), '_', ' ');
				$name = strtr(ucwords(strtolower($name)), ' ', '-');
				$name = strtolower($name);
	
				$this->headers[$name] = $value;
				continue;
			}
	
			if ($value && strpos($key, 'CONTENT_') === 0) {
				$name = substr($key, 8); // Content-
				$name = 'Content-' . (($name == 'MD5') ? $name : ucfirst(strtolower($name)));
				$name = strtolower($name);
				$this->headers[$name] = $value;
				continue;
			}
		}
	}
}