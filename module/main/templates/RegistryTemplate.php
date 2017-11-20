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
use core\classes\environment\MetaTagCreator;

class RegistryTemplate extends Template {
	
	public function __construct() {
		$this->setTemplate('main', 'templates', 'RegistryTpl');
	}
	
	protected function compileTemplate(){
		
		$this->setTag('target', '/logregsave');
		
		$link = MetaTagCreator::createCSS('RegistryFrame');
		$this->setTag('regcss', $link);
		
	}
}