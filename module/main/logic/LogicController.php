<?php
/**
 * LCARS is a framework for the RPG - ST - DNG
 * 
 * Erstellt eine JS, JSON, Jquery Datei je nach URL übergabe
 *
 * @package    LCARS
 * @version    0.1
 * @author     Andreas Dziemba
 * @license    DNG-Bluegreen License
 * @copyright  2016 - 2017 Star Trek - Die neue Grenze
 * @link       http://www.die-neue-grenze.de
 */

namespace module\main\logic;
use core\interfaces\app\ControllerInterface;
use core\classes\environment\Request;
use core\classes\logger\Logger;

class LogicController implements ControllerInterface {
	public function publish() {
	
		$fullFilePathName = $this->parseLink();
		if(file_exists($fullFilePathName)) {
			echo file_get_contents($fullFilePathName);
		}else {
			Logger::getLogger()->warning("Could not find LogicFile : ".$fullFilePathName);
		}
	}
	
	/**
	 *    0 /   1  /    2   /     3     /  4
	 *  host/logic/{module}/{logictype}/{file}
	 * @return string
	 */
	private function parseLink() : string {
		$link = Request::instance()->getUri();
		$segments = explode('/', $link, 5);
	
		$fullFilePathName = MODULEDIR.$segments[2].DIRECTORY_SEPARATOR.'logic'.DIRECTORY_SEPARATOR.$segments[4].'.'.$segments[3];
	
		if(file_exists($fullFilePathName)) {
			$this->setMimeType($segments[3]);
			$length = filesize($fullFilePathName);
			header('Content-Length: '.$length);
			return $fullFilePathName;
		}else {
			return "";
		}
	}
	
	private function setMimeType(string $mime) {
		switch($mime) {
			case "js":
				header('Content-type: application/javascript');
				break;
		}
	}
}