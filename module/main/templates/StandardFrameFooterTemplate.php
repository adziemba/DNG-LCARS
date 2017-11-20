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

// Tag: loadtime

class StandardFrameFooterTemplate extends Template {
	
	public function __construct() {
	 	$this->setTemplate('main', 'templates', 'StandardFooterTpl');
	}
	
	protected function compileTemplate() {
		
	}
}