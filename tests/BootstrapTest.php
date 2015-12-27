<?php

class BootstrapTest
	extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
	}

	public function testPass()
	{
		$App = $this->getMockForAbstractClass( '\Concept\WebApplicationBase' );
		$this->assertTrue( \Concept\Bootstrap::run( 'tests/config.ini', $App ) );
	}

	public function testFail()
	{
	}

}
