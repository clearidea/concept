<?php

class DispatchTest
	extends PHPUnit_Framework_TestCase
{
	private $_Dispatcher;

	public function setUp()
	{
		$App = $this->getMockForAbstractClass( '\Concept\WebApplicationBase' );

		$Config = new \Neuron\Setting\Source\Ini( 'example/config.ini' );

		$App->setConfig( $Config );

		$this->_Dispatcher = new \Concept\Core\Dispatcher( $App );

		$this->_Dispatcher->addResourcesForController( new \Concept\Controller\User( $App ) );

		try
		{
			$this->_Dispatcher->dispatch( '/user/1', \Concept\Core\RequestMethod::DELETE );
		}
		catch( \Exception $ex )
		{
			echo "No access for delete method.";
		}

	}

	public function testFail()
	{
		// @todo: finish..

		$this->assertTrue(
			$this->_Dispatcher->dispatch( '/user/1', 			\Concept\Core\RequestMethod::GET )
		);

		$this->assertTrue(
			$this->_Dispatcher->dispatch( '/user/3.json',	\Concept\Core\RequestMethod::GET )
		);

		$this->assertTrue(
			$this->_Dispatcher->dispatch( '/user/1.xml', 	\Concept\Core\RequestMethod::GET )
		);

		$this->assertTrue(
			$this->_Dispatcher->dispatch( '/user/1/edit', 	\Concept\Core\RequestMethod::GET )
		);

		$this->assertTrue(
			$this->_Dispatcher->dispatch( '/user/add', 		\Concept\Core\RequestMethod::GET )
		);

		$this->assertTrue(
			$this->_Dispatcher->dispatch( '/user', 			\Concept\Core\RequestMethod::GET )
		);

		$this->assertTrue(
			$this->_Dispatcher->dispatch( '/user/1', 			\Concept\Core\RequestMethod::POST )
		);
	}

}
