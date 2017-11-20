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
namespace module\main\templates;

use core\classes\environment\LinkCreator;
use core\classes\logger\Logger;
use core\classes\templateengine\Template;

class StandardFrameHeaderTemplate extends Template {
	
	public function __construct() {
		$this->setTemplate('main', 'templates', 'StandardFrameHeaderTpl');
	}
	
	protected function compileTemplate() {
// 		$user = Session::instance()->getUser(); 
// 		$username = $user->getUsername();
		
// 		$this->setTag('user', $username);
		
 		$imgsrcBG = LinkCreator::createImageLink('Head_BG', 'png');
 		Logger::getLogger()->debug($imgsrcBG);
 		$imgBG = '<img id="headerBG" src="'.$imgsrcBG.'" alt="HeaderBackground">';
 		$this->setTag('imgBG', $imgBG);
 		
 		$imgsrc = LinkCreator::createImageLink('ufp_logo', 'png');
 		Logger::getLogger()->debug($imgsrc);
 		$img = '<img id="headerLogo" src="'.$imgsrc.'" alt="UFP Logo">';
 		$this->setTag('imgLogo', $img);
	}
}