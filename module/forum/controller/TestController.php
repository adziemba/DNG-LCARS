<?php
namespace module\forum\controller;

use module\main\controller\BaseController;
use module\main\templates\StandardBaseTemplate;

use module\forum\templates\TestTemplate;


class TestController extends BaseController {

	public function setHTMLPageTemplate(){
		$this->page = new StandardBaseTemplate();
	}

	public function setMetaHead(){
		$this->metacreator->addCSS('StandardBasic');
		$this->metacreator->addJS('prototype');
	}

	public function setHeaderTemplate() {
		$this->useStandardHeader();
	}

	public function setBodyTemplate(){
		$body = new TestTemplate();
		$this->page->setTag('body', $body->parse());
	}

	public function setFooterTemplate(){
		$this->useStandardFooter();
	}
}