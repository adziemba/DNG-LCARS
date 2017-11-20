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

namespace core\classes\loader;

use InvalidArgumentException;
use core\interfaces\loader\ClassLoaderInterface;

class Autoloader {
	private $loaders = [];
	
	/**
	 * Registers a given class loader.
	 *
	 * @param ClassLoader $loader A class loader to add to the list.
	 */
	public function addLoader(ClassLoaderInterface $loader) {
		$this->loaders[$loader->getModuleName()] = $loader;
	}
	
	/**
	 * Removes a given class loader from the list.
	 *
	 * @param ClassLoader $loader A class loader to remove from the list.
	 */
	public function removeLoader(ClassLoaderInterface $loader) {
		unset($this->loaders[$loader->getModuleName()]);
	}
	
	/**
	 * Main entry for all class loading activities. This method is registered with the
	 * <em>spl_autoload_register()</em> function.
	 *
	 * @param string $class The fully-qualified class name to load. (e.g. <em>core\classes\loader\ModuleClassLoader</em>)
	 */
	public function load($class) {
		foreach ($this->loaders as $loader) {
			if($loader->load($class)) {
				break;
			}
		}
	}
	
	/**
	 * Returns a class loader by the applied module name.
	 *
	 * @param string $moduleName The name of the desired class loader to get.
	 *
	 * @return ClassLoader The desired class loader.
	 * @throws InvalidArgumentException In case no class loader is found.
	 */
	public function getLoaderByModule($moduleName) {
		if (isset($this->loaders[$moduleName])) {
			return $this->loaders[$moduleName];
		}
		throw new InvalidArgumentException('No class loader with modul "' . $moduleName . '" registered!');
	}
	
	/**
	 * Returns a class loader by the applied namespace.
	 *
	 * @param string $namespace The namespace of the desired class loader to get.
	 *
	 * @return ClassLoader The desired class loader.
	 * @throws Exception In case no class loader is found.
	 */
	public function getLoaderByNamespace($namespace) {
		// we can use getModule() here, because only the first section is of our interest!
		return $this->getLoaderByModule(self::getModule($namespace));
	}
	
	/**
	 * Returns a class loader by the applied namespace.
	 *
	 * @param string $class The fully-qualified class of the desired class loader to get.
	 *
	 * @return ClassLoader The desired class loader.
	 * @throws Exception In case no class loader is found.
	 */
	public function getLoaderByClass($class) {
		$namespace = $this->getNamespace($class);
		if ($this->isVendorOnlyNamespace($namespace)) {
			return $this->getLoaderByVendor($namespace);
		} else {
			return $this->getLoaderByNamespace($namespace);
		}
	}
	
	/**
	 * Determines the class name of a fully-qualified class for you.
	 *
	 * @param string $class Fully-qualified class name (e.g. <em>core\classes\loader\ModuleClassLoader</em>).
	 *
	 * @return string The class name of the given class (e.g. <em>ModuleClassLoader</em>).
	 */
	public function getClassName($class) {
		return substr($class, strrpos($class, '\\') + 1);
	}
	
	/**
	 * Determines the namespace of a fully-qualified class for you.
	 *
	 * @param string $class Fully-qualified class name (e.g. <em>core\classes\loader\ModuleClassLoader</em>).
	 *
	 * @return string The class name of the given class (e.g. <em>core\classes\loader</em>).
	 */
	public function getNamespace($class) {
		return substr($class, 0, strrpos($class, '\\'));
	}
	
	/**
	 * Determines the namespace without the leading module of a fully-qualified class for you.
	 *
	 * @param string $class Fully-qualified class name (e.g. <em>core\classes\loader\ModuleClassLoader</em>).
	 *
	 * @return string The class name without module of the given class (e.g. <em>loader\classes</em>).
	 */
	public function getNamespaceWithoutModule($class) {
	
		$start = strpos($class, '\\');
		$end = strrpos($class, '\\');
	
		return substr($class, ($start + 1), ($end - $start - 1)); // plus/minus one to strip leading and trailing slashes
	}
	
	/**
	 * Determines the module of a fully-qualified class for you.
	 *
	 * @param string $class Fully-qualified class name (e.g. <em>core\loader\ModuleClassLoader</em>).
	 */
	public function getModule($class) {
		return substr($class, 0, strpos($class, '\\'));
	}
	
	/**
	 * Determines whether the given namespace only consists of a module.
	 *
	 * @param string $namespace A fully-qualified namespace (e.g. <em>core\classes\loader</em> or <em>core</em>).
	 *
	 * @return bool <em>True</em> in case the namespace contains only the vendor declaration, <em>false</em> otherwise.
	 */
	public function isModuleOnlyNamespace($namespace) {
		return strpos($namespace, '\\') === false;
	}
	
	/**
	 * Register the ClassLoader with <em>spl_autoload_register()</em> function.
	 * 
	 */
	public function register() {
		spl_autoload_register(array($this, 'load'));
	}
	
	public function getAllLoaders() {
		return $this->loaders;
	}
}