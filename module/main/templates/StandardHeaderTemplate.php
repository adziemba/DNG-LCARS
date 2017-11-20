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
use core\classes\session\Session;
use core\classes\templateengine\Template;

class StandardHeaderTemplate extends Template {
	
	public function __construct() {
		$this->setTemplate('main', 'templates', 'StandardHeaderTpl');
	}
	
	protected function compileTemplate() {
		$user = Session::instance()->getUser(); 
		$username = $user->getUsername();
		
		$this->setTag('user', $username);
		
		$imgsrc = LinkCreator::createImageLink('DNG_HeaderBanner', 'jpg');
		
		$img = '<img src="'.$imgsrc.'" alt="Banner">';
		$this->setTag('img', $img);
	}
}