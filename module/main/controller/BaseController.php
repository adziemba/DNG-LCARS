<?php
/**
 * LCARS is a framework for the RPG - ST - DNG
 * 
 * Basic Controller von dem ale Controller erben. Stellt die Basisfunktionen zur verfügung um eine Seite darzustellen.
 * Seitenspezifische Logic muss von den einzelnen Controllern implementiert werden.
 *
 * @package    LCARS
 * @version    0.1
 * @author     Andreas Dziemba
 * @license    DNG-Bluegreen License
 * @copyright  2016 - 2017 Star Trek - Die neue Grenze
 * @link       http://www.die-neue-grenze.de
 */

namespace module\main\controller;

use core\interfaces\app\ControllerInterface;
use core\classes\statistics\LoadTimer;
use core\classes\environment\MetaTagCreator;
use core\classes\session\Session;
use module\main\templates\StandardHeaderTemplate;
use module\main\templates\StandardFooterTemplate;
use module\main\templates\LoginFormTemplate;

abstract class BaseController implements ControllerInterface {
	
	protected $session;
	
	//Das Seitentemplate mit der HTMLStrukture
	protected $page;
	
	protected $metacreator;
	
	//The controller template
	protected $controllerTpl;
	
	private $useRedirect=false;
	private $redirectController;
	/**
	 * Wird aufgerufen wenn der Controller aus LCARS (Route) aufgerufen wird.
	 * Dann sollte diese Methode die Logic enthalten, welche die Page darstellt.
	 * 
	 * {@inheritDoc}
	 * @see \core\interfaces\app\ControllerInterface::publish()
	 */
	public function publish(bool $withclear = true) {
		$this->session = Session::instance();
		
		$this->metacreator = new MetaTagCreator();
		$this->setHTMLPageTemplate();
		$this->setTitle();
		$this->setBodyTemplate();
		
		if(!$this->useRedirect) {
			$this->setHeaderTemplate();
		}
		if(!$this->useRedirect) {
			$this->setFooterTemplate();
		}
		if(!$this->useRedirect) {
			$this->setMetaHead();
		}
		if(!$this->useRedirect) {
			$this->finalize();
		}
		
		if($this->useRedirect) {
			return $this->redirectController->publish($withclear);
		}
		
		$this->page->setTag('meta', $this->metacreator->getMetaHead());
		$this->page->setTag('loadtime', LoadTimer::instance()->getTime());
		return $this->page->parse($withclear);
	}
	
	public function redirect(BaseController $redirectController) {
		$this->redirectController = $redirectController;
		$this->useRedirect = true;
	}
	
	public function setTitle() {
		$this->page->setTag('title', 'LIRA service GmbH');
	}
	
	public abstract function setHTMLPageTemplate();
	
	public abstract function setMetaHead();
	public abstract function setHeaderTemplate();
	public abstract function setBodyTemplate();
	public abstract function setFooterTemplate();
	
	public function finalize() {
		
	}
	
	public function useStandardHeader() {
		$header = new StandardHeaderTemplate();
		if($this->session->isLoggedIn()) {
			$login = '<a href="/logout">logout</a>';
		}else {
			$this->metacreator->addJS('sha512');
			$this->metacreator->addJS('loggin');
			$logTmp = new LoginFormTemplate();
			$login = $logTmp->parse();
		}
		
		$header->setTag('login', $login);
		$this->page->setTag('header', $header->parse());
	}
	
	public function useStandardFooter() {
		$footer = new StandardFooterTemplate();
		$this->page->setTag('footer', $footer->parse());
	}
}