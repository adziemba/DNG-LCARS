<?php
namespace core\errorpages;
use core\interfaces\app\ControllerInterface;

class Error404 implements ControllerInterface{
	public function publish(){
		echo "<br><h1>ERROR 404</h1><br>";
	}
}