<?php
namespace core\classes\datastorage;

use core\classes\config\Configuration;
use core\classes\routes\Router;

class LcarsDatabase {
	
	protected static $_instance = null;
	
	private $pdo;
	
	private $dsn 		= 'mysql:host=localhost;dbname=dnglcars';
	private $username 	= 'dng';
	private $password 	= 'dng';
	
	private $tables 	= array(
			'CONFIG'=>'config',
			'ROUTE'=>'routes',
			'TAGTEMPALTE'=>'tagtemplates'
			
	);

	// Singleton Pattern
	public static function instance() {
		if ( !isset( self::$_instance ) ) {

			self::$_instance = new LcarsDatabase();

		}
		return self::$_instance;
	}
	
	
	private function __construct() {
		$pdoconn = new PDOConnection();
		$this->pdo = $pdoconn->getConn($this->dsn, $this->username, $this->password);
	}
	
	public function __destruct() {
		$this->pdo == null;
	}
	
	public function loadConfig() {
		$starttime = microtime(true);
		$stmt = $this->pdo->prepare("SELECT * FROM ".$this->tables['CONFIG']);
		$stmt->execute();
		
		$count = $stmt->rowCount();
		$data = $stmt->fetchAll();
		
		if($count) {
			$config = Configuration::instance();
			foreach ($data as $conf) {
				$config->addItem($conf['configkey'], $conf['configvalue']);
			}
		}
		$duration = round(microtime(true)-$starttime, 3);
		//Logger::getLogger()->debug("Load Config took: ".$duration);
	}
	
	public function loadRoutes() {
		$starttime = microtime(true);
		$stmt = $this->pdo->prepare("SELECT name, path, controller_class, namespace, case_sensitive FROM ".$this->tables['ROUTE']);
		$stmt->execute();
	
		$count = $stmt->rowCount();
		$data = $stmt->fetchAll();
	
		if($count) {
			$router = Router::instance();
			foreach ($data as $route) {
				$router->addRoute($route['name'], $route['path'], $route['controller_class'], $route['namespace'], $route['case_sensitive']);
			}
		}
		$duration = round(microtime(true)-$starttime, 3);
		//Logger::getLogger()->debug("Caching Routes took: ".$duration);
	}
	
// 	public function loadTagTemplates() {
// 		$starttime = microtime(true);
// 		$stmt = $this->pdo->prepare("SELECT tagname, templateController, namespace FROM ".$this->tables['TAGTEMPALTE']);
// 		$stmt->execute();
		
// 		$count = $stmt->rowCount();
// 		$data = $stmt->fetchAll();
		
// 		if($count) {
// 			$tagtemplates = TemplateControllerTags::instance();
// 			foreach ($data as $tagtemplate) {
// 				$tagtemplates->addTag(trim($tagtemplate['tagname']), trim($tagtemplate['templateController']), trim($tagtemplate['namespace']));
// 			}
// 		}
// 		$duration = round(microtime(true)-$starttime, 3);
// 		//Logger::getLogger()->debug("Caching TagTemplates took: ".$duration);
// 	}
}