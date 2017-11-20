<?php
namespace module\main\controller;

use module\main\templates\HomeTemplate;
use module\main\templates\StandardBaseTemplate;

class HomeController extends BaseController {

	//Order of compiling
	//
	// - setHTMLPageTemplate
	// - setTitle (Optional) 
	// - setBodyTemplate
	// - setHeaderTemplate
	// - 
	
	
	public function setHTMLPageTemplate(){
		$this->page = new StandardBaseTemplate();
	}
	
	public function setMetaHead(){
		$this->metacreator->addCSS('StandardBasic');
		$this->metacreator->addJS("prototype");
		$this->metacreator->addJS("mytest");
	}
	
	public function setHeaderTemplate() {
		$this->useStandardHeader();
	}
	
	public function setBodyTemplate(){
		$body = new HomeTemplate();
		$this->page->setTag('body', $body->parse());
	}
	
	public function setFooterTemplate(){
		$this->useStandardFooter();
	}
}