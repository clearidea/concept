<?php

namespace Concept\Core;

use Concept\Controller;
use Concept\Core\RequestMethod;

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

		$this->getApplication()->debug( "Matched route: $Route[route]" );
		$sController	= "$Route[controller]";

		$Controller = Controller\Factory::create( $sController, $this->getApplication() );

		$Controller->action( $Route[ 'method' ], $this->_aParams, $this->_iDataType );
		return true;
	}

	/**
	 *
	 */

	protected function processRouteExtension( $sUri )
	{
		$iPos = strripos( $sUri, '.' );
		if( $iPos )
		{
			$iPos++;
			$sType = substr( $sUri, $iPos );

			if( $sType == 'json' )
			{
				$this->_iDataType = Controller\Base::JSON;
			}
			else if( $sType == 'xml' )
			{
				$this->_iDataType = Controller\Base::XML;
			}

			$sUri = substr( $sUri, 0, strlen( $sUri ) - strlen( $sType ) - 1);
		}
		else
		{
			$this->_iDataType = Controller\Base::HTML;
		}
	}

	/**
	 * @param $Route
	 * @param $sUri
	 * @return array|bool
	 */
	protected function processRoute( $Route, $sUri )
	{
		$this->getApplication()->debug( "-Route: $Route[route], Uri: $sUri." );

		$aDetails	= array();
		$aParams		= array();

		$this->processRouteExtension( $sUri );

		$this->getApplication()->debug( "Uri: $sUri." );

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

			$iOffset = 0;

			foreach( $aUri as $sPart )
			{
				if( $iOffset >= count( $aDetails ) )
				{
					return false;
				}

				if( $aDetails[ $iOffset ][ 'action' ] )
				{
					if( $aDetails[ $iOffset ][ 'action' ] != $sPart )
					{
						return false;
					}
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
			{
				$sUri = '/' . $sUri;
			}

			$this->getApplication()->debug( "Route: $Route[route], Uri: $sUri." );


			if( $Route[ 'route' ] == $sUri )
			{
				return true;
			}
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
					{
						$this->_aParams = $aParams;
					}
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
	 * Builts the array parameters for the addRoute method.
	 * @param $Type
	 * @param $sController
	 * @param $sMethod
	 * @param $sAction
	 * @return array
	 */

	public function buildRouteParams( $Type, $sController, $sMethod, $sAction = '' )
	{
		if( $sAction )
		{
			$sRoute = "/$sController/$sAction";
		}
		else
		{
			$sRoute = "/$sController";
		}

		return [
			'type'		 	=> $Type,
			'route' 			=> $sRoute,
			'controller' 	=> $sController,
			'method' 		=> $sMethod
		];
	}

	/**
	 * @param Controller\Controller $Controller
	 */

	public function addResourcesForController( Controller\Controller $Controller )
	{
		$sController = strtolower( $Controller->getClass() );

		// index

		if( $Controller->doesMethodExist( 'index' ) )
		{
			$this->addRoute( $this->buildRouteParams( RequestMethod::GET, $sController, 'index' ) );
		}

		// add

		if( $Controller->doesMethodExist( 'add' ) )
		{
			$this->addRoute( $this->buildRouteParams( RequestMethod::GET, $sController, 'add', 'add' ) );
		}

		if( $Controller->doesMethodExist( 'create' ) )
		{
			$this->addRoute( $this->buildRouteParams( RequestMethod::POST, $sController, 'create', 'create' ) );
		}

		// show

		if( $Controller->doesMethodExist( 'show' ) )
		{
			$this->addRoute( $this->buildRouteParams( RequestMethod::GET, $sController, 'show', ':id' ) );
		}

		// edit

		if( $Controller->doesMethodExist( 'edit' ) )
		{
			$this->addRoute( $this->buildRouteParams( RequestMethod::GET, $sController, 'edit', ':id/edit' ) );
		}

		// update

		if( $Controller->doesMethodExist( 'update' ) )
		{
			$this->addRoute( $this->buildRouteParams( RequestMethod::POST, $sController, 'update', ':id' ) );
		}

		// destroy

		if( $Controller->doesMethodExist( 'delete' ) )
		{
			$this->addRoute( $this->buildRouteParams( RequestMethod::DELETE, $sController, 'delete', ':id' ) );
		}
	}
}
