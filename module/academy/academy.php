<?php
namespace module\forum;

use core\classes\psr\ServerRequestFactory;
use core\classes\psr\Server;
use core\interfaces\psr\ServerRequestInterface as IRequest;
use core\interfaces\psr\ResponseInterface as IResponse;

class Academy {
	public function run() {
		//$response = new Response();
		//echo $response;
	}
	
	public function testURI(){

		$request = ServerRequestFactory::fromGlobals();
		$server = Server::createServerFromRequest(function (IRequest $request, IResponse $response, $done) {
			    $response->getBody()->write("Hello ");
				},
				$request);
		
		$server->listen();
		$server = Server::createServerFromRequest(function (IRequest $request, IResponse $response, $done) {
			$response->getBody()->write("Kadett!<br>");
		},
		$request);
		
		$server->listen();
	}
}