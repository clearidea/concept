<?php

namespace Concept\Core;

use Concept\Controller;
use Neuron;
use Neuron\Application;

/*
 * The question here is, should routes just be generated automatically?
 *
 * /controller/action/parameters..
 *
 * This means that addRoute would only be needed to map controller actions to
 * non default routes.
 *
 */

class Dispatcher
{
	private $_aRoutes;
	private $_aParams = array();
	private $_iDataType;
	private $_Settings;
	private $_Application;

	public function getApplication()
	{
		return $this->_Application;
	}

	public function __construct( Application\IApplication $Application )
	{
		$this->_Application = $Application;
	}

	/**
	 * @param $sRoute
	 * @param $iMethod
	 * @return bool
	 */

	public function dispatch( $sRoute, $iMethod )
	{
		/**
		 * controller/action
		 *
		 */

		$this->getApplication()->debug( "Dispatch route: $sRoute, Method: $iMethod" );

		$Route = $this->getRoute( $sRoute, $iMethod );

		if( !$Route )
		{
			$this->getApplication()->warning( "No route found for: $sRoute, Method: $iMethod" );
			return false;
		}

		$sController	= "$Route[controller]";

		$Controller = Controller\Factory::create( $sController, $this->getApplication() );

		$Controller->action( $Route[ 'method' ], $this->_aParams, $this->_iDataType );
		return true;
	}

	protected function processRoute( $Route, $sUri )
	{
		$aDetails	= array();
		$aParams		= array();

		$iPos = strripos( $sUri, '.' );
		if( $iPos )
		{
			$iPos++;
			$sType = substr( $sUri, $iPos );

			if( $sType == 'json' )
				$this->_iDataType = Controller\Base::JSON;
			else if( $sType == 'xml' )
				$this->_iDataType = Controller\Base::XML;

			$sUri = substr( $sUri, 0, strlen( $sUri ) - strlen( $sType ) - 1);
		}
		else
			$this->_iDataType = Controller\Base::HTML;

		// Does route have parameters?

		if( strpos( $Route[ 'route' ], ':' ) )
		{
			$aParts = explode( '/', $Route[ 'route' ] );
			array_shift( $aParts );

			foreach( $aParts as $sPart )
			{
				if( substr( $sPart, 0, 1 ) == ':' )
				{
					$aDetails[] = array(
						'param' 	=> substr( $sPart, 1 ),
						'action'	=> false
					);
				}
				else
				{
					$aDetails[] = array(
						'param' 	=> false,
						'action'	=> $sPart
					);
				}
			}

			$aUri = explode( '/', $sUri );
			//array_shift( $aUri );
			$iOffset = 0;

//			print_r( $aUri );

			foreach( $aUri as $sPart )
			{
				if( $iOffset >= count( $aDetails ) )
					return false;

				if( $aDetails[ $iOffset ][ 'action' ] )
				{
					if( $aDetails[ $iOffset ][ 'action' ] != $sPart )
						return false;
				}
				else
				{
					$aParams[ $aDetails[ $iOffset ][ 'param' ] ]	= $sPart;
				}
				$iOffset++;
			}
			return $aParams;
		}
		else
		{
			if( $sUri[ 0 ] != '/' )
				$sUri = '/'.$sUri;

			if( $Route[ 'route' ] == $sUri )
				return true;
		}

		return false;
	}

	/**
	 * @param $sUri
	 * @param $iMethod
	 * @return null
	 */

	public function getRoute( $sUri, $iMethod )
	{
		foreach( $this->_aRoutes as $Route )
		{
			if( $Route[ 'type' ] == $iMethod )
			{
				$aParams = $this->processRoute( $Route, $sUri );

				if( $aParams )
				{
					if( is_array( $aParams ) )
						$this->_aParams = $aParams;
					return $Route;
				}
			}
		}

		return null;
	}

	/**
	 * @param array $aInfo
	 *
	 */

	public function addRoute( array $aInfo )
	{
		$this->_aRoutes[] = $aInfo;
	}

	/**
	 * @param Controller $Controller
	 */

	public function addResourcesForController( Controller\Controller $Controller )
	{
		$sController = strtolower( $Controller->getClass() );

		// index

		if( $Controller->doesMethodExist( 'index' ) )
		{
			$this->addRoute(
				array(
					'type'		 	=> \Concept\Core\RequestMethod::GET,
					'route' 			=> "/$sController",
					'controller' 	=> $sController,
					'method' 		=> 'index'
				)
			);
		}

		// add

		if( $Controller->doesMethodExist( 'add' ) )
		{
			$this->addRoute(
				array(
					'type'			=> \Concept\Core\RequestMethod::GET,
					'route'			=> "/$sController/add",
					'controller'	=> $sController,
					'method'			=> 'add'
				)
			);
		}

		if( $Controller->doesMethodExist( 'create' ) )
		{
			$this->addRoute(
				array(
					'type'			=> \Concept\Core\RequestMethod::POST,
					'route'			=> "/$sController/create",
					'controller'	=> $sController,
					'method'			=> 'create'
				)
			);
		}

		// show

		if( $Controller->doesMethodExist( 'show' ) )
		{
			$this->addRoute(
				array(
					'type'			=> \Concept\Core\RequestMethod::GET,
					'route'			=> "/$sController/:id",
					'controller'	=> $sController,
					'method'			=> 'show'
				)
			);
		}


		// edit

		if( $Controller->doesMethodExist( 'edit' ) )
		{
			$this->addRoute(
				array(
					'type' 			=> \Concept\Core\RequestMethod::GET,
					'route'			=> "/$sController/:id/edit",
					'controller'	=> $sController,
					'method'			=> 'edit'
				)
			);
		}

		// update

		if( $Controller->doesMethodExist( 'update' ) )
		{
			$this->addRoute(
				array(
					'type'			=> \Concept\Core\RequestMethod::POST,
					'route'			=> "/$sController/:id",
					'controller'	=> $sController,
					'method'			=> 'update'
				)
			);
		}

		// destroy

		if( $Controller->doesMethodExist( 'delete' ) )
		{
			$this->addRoute(
				array(
					'type'			=> \Concept\Core\RequestMethod::DELETE,
					'route'			=> "/$sController/:id",
					'controller'	=> $sController,
					'method'			=> 'delete'
				)
			);
		}
	}
}
