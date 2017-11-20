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

namespace core\interfaces\loader;

interface ClassLoaderInterface {
	
	/**
	 * Decision on what to do with none-module classes can be done by the ClassLoader itself!
	 *
	 * @param string $class The class to load.
	 *
	 * @throws InvalidArgumentException In case the class cannot be loaded.
	 */
	public function load($class);
	
	/**
	 * Returns the module name the class loader represents.
	 *
	 * @return string The name of the modul the class loader is attending to.
	 */
	public function getModuleName();
	
	/**
	 * Returns the root path this class loader instance uses to load PHP classes.
	 * <p/>
	 * Further, the root path is used to load templates files. This is because LCARS
	 * uses one addressing scheme for all elements. Please note, that template files
	 * naturally do not have namespaces but LCARS introduces them with this mechanism
	 * for convenience and consistency reasons.
	 *
	 * @return string The root path of the class loader.
	 */
	public function getRootPath();
	
	//TODO Find out if i need Configuration Files!!!!
	/**
	 * Returns the root path this class loader instance advices the ConfigurationProvider
	 * to load the config files from.
	 * <p/>
	 * Please note that LCARS uses one addressing scheme for all elements since configuration
	 * files naturally do not have namespaces. Namespaces have been introduced for convenience
	 * and consistency reasons.
	 *
	 * @return string The configuration root path of the class loader.
	 */
	//public function getConfigurationRootPath();
}