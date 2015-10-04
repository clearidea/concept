<?php

try
{
	$Dispatcher->dispatch( '/user/1', \Concept\Core\RequestMethod::DELETE );
}
catch( \Concept\Core\AuthException $ex )
{
	echo "No access for delete method.";
}
class DispatchTest
	extends PHPUnit_Framework_TestCase
{
	private $_Dispatcher;

	public function setUp()
	{

		$this->_Dispatcher = new \Concept\Core\Dispatcher();

		$this->_Dispatcher->addResourcesForController( new \Concept\Core\User() );

	}

	public function testFail()
	{
		$this->_Dispatcher->dispatch( '/user/1', 			\Concept\Core\RequestMethod::GET );
		$this->_Dispatcher->dispatch( '/user/3.json',	\Concept\Core\RequestMethod::GET );
		$this->_Dispatcher->dispatch( '/user/1.xml', 	\Concept\Core\RequestMethod::GET );

		$this->_Dispatcher->dispatch( '/user/1/edit', 	\Concept\Core\RequestMethod::GET );
		$this->_Dispatcher->dispatch( '/user/add', 		\Concept\Core\RequestMethod::GET );
		$this->_Dispatcher->dispatch( '/user', 			\Concept\Core\RequestMethod::GET );
		$this->_Dispatcher->dispatch( '/user/1', 			\Concept\Core\RequestMethod::POST );
	}

}
