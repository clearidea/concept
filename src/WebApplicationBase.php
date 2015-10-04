<?php

namespace Concept;

use \Neuron;

abstract class WebApplicationBase
	extends Neuron\ApplicationBase
{
	private $_Settings;
	private $_Dispatcher;

	/**
	 * @return mixed
	 */

	abstract protected function loadRoutes();

	/**
	 * @param array $aRoute
	 */

	public function addRoute( array $aRoute )
	{
		$this->_Dispatcher->addRoute( $aRoute );
	}

	/**
	 * @param Neuron\Setting\Source\ISettingSource $Source
	 * @return $this
	 */

	public function setConfig( Neuron\Setting\Source\ISettingSource $Source )
	{
		$this->_Settings = new Neuron\Setting\SettingManager( $Source );
		return $this;
	}

	/**
	 * Called on application starting.
	 * @return bool
	 */

	protected function onStart()
	{
		$this->_Dispatcher = new Core\Dispatcher( $this->_Settings );
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

		$this->_Dispatcher->dispatch( $sRoute, Core\RequestMethod::GET );
	}
}
