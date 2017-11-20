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

use core\interfaces\loader\ClassLoaderInterface;

class ModuleClassLoader implements ClassLoaderInterface {

   /**
    * The module name this class loader is registered for.
    *
    * @var string $moduleName
    */
   private $moduleName;

   /**
    * The root path to load classes/templates from.
    *
    * @var string $rootPath
    */
   private $rootPath; //TODO needed?

   /**
    * The root path to load configurations from.
    *
    * @var string $configRootPath
    */
   private $configRootPath; //TODO needed?

   /**
    * Constructs the module class loader.
    *
    * @param string $moduleName The vendor name this class loader is registered for.
    * @param string $rootPath The root path to load classes/templates from.
    * @param string $configRootPath The configuration root path to load configurations from.
    */
   public function __construct($moduleName, $rootPath, $configRootPath = null) {
      $this->moduleName = $moduleName;
      $this->rootPath = $rootPath;
      
      // By default the configuration files are located under the classes root path.
      // If desired, you can re-map the path to whatever you intend to structure your
      // project folder structure.
      if ($configRootPath === null) {
         $configRootPath = $rootPath;
      }

      $this->configRootPath = $configRootPath;
   }

   public function load($class) {
   	  $isInModul = strpos($class, $this->moduleName . '\\')!==false ? true : false;
      if (strpos($class, $this->moduleName . '\\') !== false) {
         // create the complete and absolute file name
         $strippedClass = str_replace($this->moduleName . '\\', '', $class);
         $file = $this->rootPath . str_replace('\\', '/', $strippedClass) . '.php';
         if (file_exists($file)) {
            include_once ($file);
            return true;
         }
      }
   }

   public function getModuleName() {
      return $this->moduleName;
   }

   public function getRootPath() { //TODO needed?
      return $this->rootPath;
   }

   public function getConfigurationRootPath() { //TODO needed?
      return $this->configRootPath;
   }

   /**
    * Let's you define the module name this class loader is registered for.
    *
    * @param string $name The module name.
    *
    * @return ModuleClassLoader This instance for further usage.
    */
   public function setModuleName($name) {
      $this->moduleName = $name;

      return $this;
   }

   /**
    * Let's you define the root path for load classes/configurations/templates.
    *
    * @param string $rootPath The root path to load classes/configurations/templates from.
    *
    * @return ModuleClassLoader This instance for further usage.
    */
   public function setRootPath($rootPath) { //TODO Needed?
      $this->rootPath = $rootPath;

      return $this;
   }

}
