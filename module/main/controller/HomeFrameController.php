<?php
namespace module\main\controller;

use module\main\templates\HomeTemplate;
use module\main\templates\StandardFrameBaseTemplate;
use module\main\templates\StandardFrameHeaderTemplate;

class HomeFrameController extends BaseController {

	//Order of compiling
	//
	// - setHTMLPageTemplate
	// - setTitle (Optional) 
	// - setBodyTemplate
	// - setHeaderTemplate
	// - 
	
	public function setHTMLPageTemplate(){
		$this->page = new StandardFrameBaseTemplate();
	}
	
	public function setMetaHead(){
		//$this->metacreator->addCSS('reset');
		$this->metacreator->addCSS('AjaxFrame');
		$this->metacreator->addJS("prototype");
		$this->metacreator->addJS('effects');
		$this->metacreator->addJS("mytest");
	}
	
	public function setHeaderTemplate() {
		$header = new StandardFrameHeaderTemplate();
		$this->page->setTag('header', $header->parse());
	}
	
	public function setBodyTemplate(){
		$body = new HomeTemplate();
		$this->page->setTag('body', $body->parse());
	}
	
	public function setFooterTemplate(){
		$this->useStandardFooter();
	}
}