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

namespace core\classes\templateengine;

use core\classes\logger\Logger;
use core\classes\session\Session;

abstract class Template {
	
	private $tplFile;
	private $tplDirname;
	private $tplModule;
	
	protected $tplExtention = "htm";
	
	protected $parser;
	
	protected $tags = array();
	
	protected $templateCode;
	
		
	public function setTemplate(string $modul, string $templatDirname, string $templateFile, string $tplExtention='htm') {
		$this->tplModule 	= $modul;
		$this->tplDirname	= $templatDirname;
		$this->tplFile		= $templateFile.".".$tplExtention;
		
		$this->tplExtention	= $tplExtention;
		
		$this->loadTemplateCode();
	}
	
	/**
	 * Läde den HTML Code aus dem Template
	 */
	private function loadTemplateCode() {
		$templatePath =  MODULEDIR.$this->tplModule.DIRECTORY_SEPARATOR.$this->tplDirname.DIRECTORY_SEPARATOR;
		if(file_exists($templatePath.$this->tplFile)) {
			$this->templateCode = file_get_contents($templatePath.$this->tplFile);
		}else {
			Logger::getLogger()->warning("Could not find File : ".$templatePath.$this->tplFile);
		}
	}
	
	/**
	 * Speichert ein Tag mit Daten im TagArray
	 * @param string $key Tag
	 * @param string $value replacesment
	 */
	public function setTag(string $key, string $value) {
		$element = array($key=>$value);
		$this->tags = array_replace($this->tags, $element);
	}
	
	/**
	 * Entfernt eine gesetzten Tag aus dem TagArray
	 * @param string $key
	 */
	public function unsetTag(string $key) {
		if(array_key_exists($key, $this->tags)) {
			unset($this->tags[$key]);
		}
	}
	
	public function parse(bool $withclear=false) {
		//Im Child implementiert. Setzt Tags und fügt deren Ersatz ein.
		$this->compileTemplate();
		
		if($this->parser==null) {
			$this->parser = new Parser();
		}
		
		$parsed = $this->parser->parseTpl($this->templateCode, $this->tags);
		if($withclear) {
			return $this->parser->cleanTpl($parsed);
		}
		
		return $parsed;
	}
	
	protected function hasRight(string $right) {
		return Session::instance()->hasURight($right);
	}
	
	protected abstract function compileTemplate();

}