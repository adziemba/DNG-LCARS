<?php
namespace module\main\controller;

use core\classes\environment\Request;
use core\classes\logger\Logger;
use core\interfaces\app\ControllerInterface;
use module\main\templates\RegistryTemplate;
use module\main\templates\HomeTemplate;
use module\main\templates\UserInfoTemplate;

class AjaxController implements ControllerInterface {

	private $body;
	
	public function getOrderedTemplate(){
		$link = Request::instance()->getUri();
		Logger::getLogger()->debug("URI: ".$link);
		$segments = explode('/', $link);
		array_shift($segments);
		array_shift($segments);
		Logger::getLogger()->debug("Index[0] : ".$segments[0]);
		
		$order = new HomeTemplate();
		switch($segments[0]) {
			case 'user':
				$order = new UserInfoTemplate();
				$uid = Request::instance()->get('uid', 0);
				$order->setUserByID($uid);
				break;
			case 's':
				$order = new RegistryTemplate();
				break;
			default:
				$order = new HomeTemplate();
				
				break;
		}	
		$this->body = $order->parse(true);
	}
	
	public function publish() {
		$this->getOrderedTemplate();
		
		echo $this->body;
	}
}