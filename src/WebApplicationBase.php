<?php

namespace Concept;

use \Neuron;

abstract class WebApplicationBase
	extends Neuron\ApplicationBase
{
	private $_Dispatcher;

	public function __construct()
	{
		parent::__construct();

		// @todo: overwrite standard logging with file.
	}

	/**
	 * @return mixed
	 */

	protected function loadRoutes()
	{
		$this->addRoute(
			[
				'type'		 	=> Core\RequestMethod::GET,
				'route' 			=> '/404/:missing',
				'controller' 	=> '\Concept\Controller\System',
				'method' 		=> '_404'
			]
		);

	}

	/**
	 * @param array $aRoute
	 */

	public function addRoute( array $aRoute )
	{
		$this->_Dispatcher->addRoute( $aRoute );
	}

	/**
	 * Called on application starting.
	 * @return bool
	 */

	protected function onStart()
	{
		$this->_Dispatcher = new Core\Dispatcher( $this );
		$this->loadRoutes();
		return true;
	}

	/**
	 * Called on application completion.
	 */

	protected function onFinish()
	{}

	/**
	 * Called to execute the application.
	 */

	protected function onRun()
	{
		$sRoute = $this->getParameter( 'route' );

		if( !$this->_Dispatcher->dispatch( $sRoute, Core\RequestMethod::GET ) )
		{
			// 404

			// call 404 route..
			//
			// Where does the default view live?

			$this->_Dispatcher->dispatch( "404/{$this->getParameter($sRoute)}", Core\RequestMethod::GET );
		}
	}

}
