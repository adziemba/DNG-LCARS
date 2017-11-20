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
use core\classes\loader\ModuleClassLoader;

$modules = [];

$modules = scandir(MODULEDIR);

foreach ($modules as $module) {
	if ($module === '.' or $module === '..' or $module === 'bootstrap.php') continue;

	if (is_dir(MODULEDIR . '/' . $module)) {
		$modulepath = MODULEDIR.$module.DIRECTORY_SEPARATOR;
		//echo "MODULE LOADED: ".$modulepath.'<br>';
		$autoloader->addLoader(new ModuleClassLoader('module'.DIRECTORY_SEPARATOR.$module, $modulepath));
	}
}

// function loadModule(string $module) {
// 	if (is_dir(MODULEDIR . '/' . $module)) {
// 		$modulepath = MODULEDIR.$module.DIRECTORY_SEPARATOR;
// 		//echo "MODULE LOADED: ".$modulepath.'<br>';
// 		$autoloader->addLoader(new ModuleClassLoader('module'.DIRECTORY_SEPARATOR.$module, $modulepath));
// 	}
// }