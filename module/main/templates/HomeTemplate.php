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

use core\classes\templateengine\Template;
use core\classes\session\Session;

class HomeTemplate extends Template {
	
	public function __construct() {
		$this->setTemplate('main', 'templates', 'HomeTpl');
	}
	
	protected function compileTemplate() {
		// TAGS:
		// - uinfo
		
		$user = Session::instance()->getUser();
		
		if($user->getUserID()>0) {
			$this->setTag('uinfo', 'Willkommen '.$user->getUsername());
		}else {
			$this->setTag('uinfo', 'Hallo du Gast');
		}
	}
}