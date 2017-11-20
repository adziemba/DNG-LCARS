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
namespace module\main\templates;

use core\classes\datastorage\RPGUserDatabase;
use core\classes\templateengine\Template;
use core\dto\User;
use core\classes\environment\Request;

class UserInfoTemplate extends Template {
	
	private $user;
	
	public function __construct() {
		$this->setTemplate('main', 'templates', 'UserInfoTpl');
		$uid = Request::instance()->get('uid', 0);
		$this->setUserByID($uid);
	}
	
	public function setUser(User $user) {
		$this->user = $user;
	}
	
	public function setUserByID(int $userid) {
		$this->user = RPGUserDatabase::instance()->getUser($userid);
	}
	
	protected function compileTemplate() {
		$this->setTag('username', $this->user->getUsername());
	}
}