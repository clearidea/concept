<?php

namespace Concept\Controller;

use Neuron;
use Neuron\Application;

abstract class Base
{
	private $_aParams = array();
	private $_sRenderAction;
	private $_aViewData = array();
	private $_iDataType;
	private $_Application;
	private $_sViewPath;

	public function __construct( Application\IApplication $Application )
	{
		$this->_Application = $Application;

		$this->_sViewPath = $Application->getSetting( 'view_path', 'paths' );
	}

	protected function getApplication()
	{
		return $this->_Application;
	}

	//region ViewData
	protected function setViewData( array $data )
	{
		$this->_aViewData = $data;
	}

	protected function setRenderAction( $sAction )
	{
		$this->_sRenderAction = $sAction;
	}
	//endregion

	//region DataType
	protected function setDataType( $iDataType )
	{
		$this->_iDataType = $iDataType;
	}

	protected function getDataType()
	{
		return $this->_iDataType;
	}
	//endregion

	//region Encoding
	protected function jsonEncode( $aViewData )
	{
		return json_encode( $aViewData );
	}

	protected function xmlEncode( $aViewData )
	{
		echo xml_encode( $aViewData );
	}
	//endregion

	//region Parameters

	/**
	 * @param $sParam
	 * @return bool|mixed
	 */
	protected function getParam( $sParam )
	{
		if( !isset( $this->_aParams[ $sParam ] ) )
		{
			return false;
		}

		return $this->_aParams[ $sParam ];
	}

	/**
	 * @param array $aParams
	 */
	public function setParams( array $aParams )
	{
		$this->_aParams = $aParams;
	}
	//endregion

	//region Rendering

	/**
	 * @return mixed
	 */
	protected function getRenderAction()
	{
		return $this->_sRenderAction;
	}

	/**
	 * @param null $sAction
	 * @param int $iType
	 */
	protected function render( $sAction = null, $iType = 0 )
	{
		if( !$sAction)
		{
			$sAction = $this->getRenderAction();
		}

		if( !$iType )
		{
			$iType = $this->getDataType();
		}

		if( $iType == self::HTML )
		{
			extract( $this->_aViewData );

			$Path = explode( '\\', $this->getClass() );

			$sViewPath = $this->_sViewPath . '/' . $Path[ count( $Path ) - 1 ] . "/$sAction.php";

			$this->getApplication()->debug( "render view: $sViewPath" );
			require_once( $this->_sViewPath . '/template.php' );
		}
		else if( $iType == self::JSON )
		{
			echo $this->jsonEncode( $this->_aViewData );
		}
		else if( $iType == self::XML )
		{
			echo $this->xmlEncode( $this->_aViewData );
		}
	}
	//endregion

	//region Methods
	public function callMethod( $sMethod )
	{
		if( !method_exists( $this, $sMethod ) )
		{
			throw new ControllerException( "Method '$sMethod'' not found." );
		}

		return $this->$sMethod();
	}

	/**
	 * Checks if a controller method exists.
	 * @param $sMethod
	 * @return bool
	 */
	public function doesMethodExist( $sMethod )
	{
		return method_exists( $this, $sMethod );
	}

	/**
	 * Executes a controller action.
	 * @param $sAction
	 * @param array $aParams
	 * @param $iDataType
	 */
	public function action( $sAction, array $aParams, $iDataType )
	{
		$this->setDataType( $iDataType );
		$this->setRenderAction( $sAction );
		$this->_aParams = $aParams;

		$this->callMethod( $sAction );
	}
	//endregion

	/**
	 * @return string
	 */
	public function getClass()
	{
		return get_class( $this );
	}
}

function xml_encode($mixed, $domElement=null, $DOMDocument=null)
{
	if (is_null($DOMDocument))
	{
		$DOMDocument =new \DOMDocument;
		$DOMDocument->formatOutput = true;
		xml_encode($mixed, $DOMDocument, $DOMDocument);
		return $DOMDocument->saveXML();
	}
	else
	{
		if (is_array($mixed))
		{
			foreach ($mixed as $index => $mixedElement)
			{
				if (is_int($index))
				{
					if ($index === 0)
					{
						$node = $domElement;
					}
					else
					{
						$node = $DOMDocument->createElement($domElement->tagName);
						$domElement->parentNode->appendChild($node);
					}
				}
				else
				{
					$plural = $DOMDocument->createElement($index);
					$domElement->appendChild($plural);
					$node = $plural;
					if (!(rtrim($index, 's') === $index))
					{
						$singular = $DOMDocument->createElement(rtrim($index, 's'));
						$plural->appendChild($singular);
						$node = $singular;
					}
				}

				xml_encode($mixedElement, $node, $DOMDocument);
			}
		}
		else
		{
			$mixed = is_bool($mixed) ? ($mixed ? 'true' : 'false') : $mixed;
			$domElement->appendChild($DOMDocument->createTextNode($mixed));
		}
	}
}
