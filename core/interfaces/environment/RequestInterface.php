<?php
namespace core\interfaces\environment;

interface RequestInterface {

	public function getMethod();
	public function getUri();
	public function getHeader(string $header);
}
