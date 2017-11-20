<?php
/**
 * LCARS is a framework for the RPG - ST - DNG
 * 
 * Erstellt eine CSS JS Datei je nach URL übergabe
 *
 * @package    LCARS
 * @version    0.1
 * @author     Andreas Dziemba
 * @license    DNG-Bluegreen License
 * @copyright  2016 - 2017 Star Trek - Die neue Grenze
 * @link       http://www.die-neue-grenze.de
 */

namespace module\main\design;
use core\interfaces\app\ControllerInterface;
use core\classes\environment\Request;
use core\classes\logger\Logger;

class DesignerController implements ControllerInterface {
	public function publish() {		
		
		$fullFilePathName = $this->parseLink();
		if(file_exists($fullFilePathName)) {
			header('Content-type: text/css');
			echo file_get_contents($fullFilePathName);
		}else {
			Logger::getLogger()->warning("Could not find DesignFile : ".$fullFilePathName);
		}
		
	}
	
	/**
	 *    0 /   1  /    2   /  3
	 *  host/design/{module}/{file}
	 * @return string
	 */
	private function parseLink() : string {
		$link = Request::instance()->getUri();
		$segments = explode('/', $link, 4);
		
		$fullFilePathName = MODULEDIR.$segments[2].DIRECTORY_SEPARATOR.'design'.DIRECTORY_SEPARATOR.$segments[3].".css";
		
		return $fullFilePathName;
	}
	
}