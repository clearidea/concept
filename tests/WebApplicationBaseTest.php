<?php

class WebApplicationBaseTest extends PHPUnit_Framework_TestCase
{
	private $_App;

	public function setup()
	{
		$this->_App = $this->getMockForAbstractClass( '\Concept\WebApplicationBase' );

		$this->_App->expects( $this->any() )
			->method( 'onRun' )
			->will( $this->returnValue( true ) );

		$Settings = new Neuron\Setting\Source\Memory();
		$this->_App->setConfig( $Settings );

		$this->_App->setSetting( 'view_path', 'tests', 'paths' );
	}

	public function testRun()
	{
		$this->assertTrue( $this->_App->run() );
	}

}

