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
namespace module\forum\templates;

use core\classes\templateengine\Template;
use core\classes\session\Session;
use core\enums\URights;
use core\classes\environment\System;
use core\classes\environment\Request;

class TestTemplate extends Template {
	public function __construct() {
		$this->setTemplate('forum', 'templates', 'TestTpl');
	}
	
	protected function compileTemplate() {
		$user = Session::instance()->getUser(); 
		
		$this->setTag('test', System::esc_url(Request::instance()->getUri()));
		
		$this->setTag('username', $user->getUsername());
		
		if( $this->hasRight(URights::ADMIN)) {
			$this->setTag('usergender', $user->getGenderLong());
			$this->setTag('useremail', $user->getUserEMail());
		}
	}
}