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


//use core\classes\logger\Logger;

class Parser {
	
	// Normal Variable Tags
	private $leftDelimiter 	= '[$';
	private $rightDelimiter	= '$]';
	private $leftDelimiterClean 	= '\[\$';
	private $rightDelimiterClean	= '\$\]';
	
	//Standard Controller Tags
	private $stdLeftDelimiter 	= '\{\{';
	private $stdRightDelimiter	= '\}\}';
	
	public function parseTpl(string $tpl, $tagArray) {
		
		if(is_array($tagArray)) {
			//Logger::getLogger()->debug("Begin Parse Tags with Array");
			foreach ($tagArray as $tag => $value) {
				$tpl = str_replace( $this->leftDelimiter .$tag.$this->rightDelimiter,
						$value, $tpl );
				//Logger::getLogger()->debug("Parse TAGS : ". $tag." -> ".$value);
			}
			//Logger::getLogger()->debug("END Parse Tags with Array");
		}else {
			//Logger::getLogger()->debug("Begin Parse Tags without Array");
		}
		return $tpl;
	}
	
	public function cleanTpl(string $tpl) {
		return  preg_replace( "/" .$this->leftDelimiterClean ."(.*)" .$this->rightDelimiterClean ."/",
                                        "", $tpl );
	}
	
// 	public function parseControllerTags(string  $tpl) {		
// 		return preg_replace_callback(("/".$this->stdLeftDelimiter."(\w+)".$this->stdRightDelimiter."/"), array($this, 'replaceControllerMatch'), $tpl);
// 	}
	
// 	public function replaceControllerMatch($match) {
// 		if($code = TemplateControllerTags::instance()->getParsedController($match[1])) {
// 			return $code;
// 		}else {
// 			return "{{".$match[1]."}}";
// 		}
		
// 	}
}