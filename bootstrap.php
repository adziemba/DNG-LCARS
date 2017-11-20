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
use core\classes\loader\Autoloader;
use core\classes\loader\ModuleClassLoader;
use core\classes\LCARS;
use core\classes\statistics\LoadTimer;

require_once(COREDIR.'classes'.DIRECTORY_SEPARATOR.'statistics'.DIRECTORY_SEPARATOR.'LoadTimer.php');
LoadTimer::instance();

//Import needed Classes for Autoloader pattern
require_once(COREDIR.'classes'.DIRECTORY_SEPARATOR.'loader'.DIRECTORY_SEPARATOR.'Autoloader.php');
require_once(COREDIR.'interfaces'.DIRECTORY_SEPARATOR.'loader'.DIRECTORY_SEPARATOR.'ClassLoaderInterface.php');
require_once(COREDIR.'classes'.DIRECTORY_SEPARATOR.'loader'.DIRECTORY_SEPARATOR.'ModuleClassLoader.php');

/**
 * Set error reporting and display errors settings.  You will want to change these when in production.
 */
error_reporting(-1);
ini_set('display_errors', 1);


//### Initialize Autoloader and register core Loader
$autoloader  = new Autoloader();
$autoloader->addLoader(new ModuleClassLoader('core', COREDIR));

// load ModuleClassLoader from the modules
require_once MODULEDIR.'bootstrap.php';

$loaders = $autoloader->getAllLoaders();

$autoloader->register();
//####### END AUTOLOADER

//### Load LCARS BASE APP
$lcars = LCARS::instance();
$lcars->init();
//####### END LCARS
