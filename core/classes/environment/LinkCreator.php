<?php
/**
 * LCARS is a framework for the RPG - ST - DNG
 * 
 * Basic Controller von dem ale Controller erben. Stellt die Basisfunktionen zur verfügung um eine Seite darzustellen.
 * Seitenspezifische Logic muss von den einzelnen Controllern implementiert werden.
 *
 * @package    LCARS
 * @version    0.1
 * @author     Andreas Dziemba
 * @license    DNG-Bluegreen License
 * @copyright  2016 - 2017 Star Trek - Die neue Grenze
 * @link       http://www.die-neue-grenze.de
 */
namespace core\classes\environment;

class LinkCreator {
	
	public static function createDesignLink(string $link, string $module ='main') {
// 		$host = Request::instance()->getHeader('host');
// 		$fullLink = 'http://'.$host.'/design/'.$link;
		$fullLink = '/design/'.$module.'/'.$link;
		return $fullLink;
	}
	
	public static function createImageLink(string $link, string $imageType, string $module = 'main') {
		$fullLink = '/images/'.$module.'/'.$imageType.'/'.$link;
		return $fullLink;
	}
	
	public static function createLogicLink(string $link, string $logicType = 'js', string $module = 'main') {
		$fullLink = '/lcarslogic/'.$module.'/'.$logicType.'/'.$link;
		return $fullLink;
	}
	
}