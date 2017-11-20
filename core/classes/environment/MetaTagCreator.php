<?php
/**
 * LCARS is a framework for the RPG - ST - DNG
 * 
 *
 * @package    LCARS
 * @version    0.1
 * @author     Andreas Dziemba
 * @license    DNG-Bluegreen License
 * @copyright  2016 - 2017 Star Trek - Die neue Grenze
 * @link       http://www.die-neue-grenze.de
 */
namespace core\classes\environment;



class MetaTagCreator {

	private $meta;
	
	public function __construct() {
		$this->meta = '<meta charset="utf-8">'.PHP_EOL; 
	}
	
	public function addCSS(string $link, string $module ='main') {
// 		$src = LinkCreator::createDesignLink($link, $module);
// 		$this->meta .= '<link rel="stylesheet" href="'.$src.'">'.PHP_EOL;
		$this->meta .= $this->createCSS($link, $module);
	}
	
	public function addJS(string $link, string $logicType='js', string $module = 'main') {
// 		$src = LinkCreator::createLogicLink($link, $logicType, $module);
// 		$this->meta .= '<script type="text/javascript" src="'.$src.'"></script>'.PHP_EOL;
		$this->meta .= $this->createJS($link, $logicType, $module);
	}
	
	public function addMetaTag(string $metatag ) {
		$this->meta .= $metatag;
	}
	
	public function getMetaHead() {
		$this->meta .= '[$additionalMetaTags$]'.PHP_EOL;
		return $this->meta;
	}
	
	public static function createCSS(string $link, string $module ='main') {
		$src = LinkCreator::createDesignLink($link, $module);
		return '<link rel="stylesheet" href="'.$src.'">'.PHP_EOL;
	}
	
	public static function createJS(string $link, string $logicType='js', string $module = 'main') {
		$src = LinkCreator::createLogicLink($link, $logicType, $module);
		return '<script type="text/javascript" src="'.$src.'"></script>'.PHP_EOL;
	}
}