<?php
namespace module\main\controller;

use core\classes\environment\Request;
use core\classes\environment\System;
use core\classes\logger\Logger;
use core\classes\session\Session;
use module\main\templates\HomeTemplate;
use module\main\templates\RegistryTemplate;
use module\main\templates\StandardBaseTemplate;

class LogInOutController extends BaseController {
	

	public function setHTMLPageTemplate(){
		$this->page = new StandardBaseTemplate();
	}
	
	public function setMetaHead(){
		$this->metacreator->addCSS('StandardBasic');
	}
	
	/**
	 * Override BaseTitle
	 * {@inheritDoc}
	 * @see \module\main\controller\BaseController::setTitle()
	 */
	public function setTitle() {
		$this->page->setTag('title', 'Log LIRA service');
	}
	
	public function setHeaderTemplate() {
		$this->useStandardHeader();
	}
	
	public function setBodyTemplate(){
		$this->parseLink();
	}
	
	public function setFooterTemplate(){
		$this->useStandardFooter();
	}
		
	private function parseLink() { 
		$link = Request::instance()->getUri();
		$segments = explode('/', $link, 2);
		Logger::getLogger()->debug($segments[1]);
		switch($segments[1]) {
			case 'login':
				$req = Request::instance();
				$username 	= $req->get('username');
				$pass		= $req->get('ph');
				$loginresult = $this->session->login($username, $pass, false);
				if(!$loginresult) {
					$this->page->setTag('notice', 'Login Failed');
				}
				$body = new HomeTemplate();
				$this->page->setTag('body', $body->parse());
				
				break;
			case 'logout':
				$this->session->logout();
				$this->redirect(new HomeController());
				break;
			case 'logreg':				
				// Override TemplateTitleTag
				$this->page->setTag('title', 'Reg LIRA service');
				
				$body = new RegistryTemplate();
				$this->page->setTag('body', $body->parse());
				break;
			case 'logregsave':
				$req = Request::instance();
				$ref = $req->getHeader('referer');
				$ends = System::endsWith($ref, '/logreg');
				if($ends==1) {
					$username 	= $req->get('username');
					$email		= $req->get('email');
					$pass		= $req->get('ph');
					$gender		= $req->get('gender');
					$error = $this->session->register($username, $pass, $email, $gender);
					
					if($error != null || $error != '') {
						Logger::getLogger()->error("RegisterError: ".$error);
					}
					$this->redirect(new HomeController());
				}
				break;
			default:
				$body = new HomeTemplate();
				$this->page->setTag('body', $body->parse());
				break;
		}
	}
}