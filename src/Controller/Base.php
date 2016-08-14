<?php

namespace Concept\Controller;

use Neuron;
use Neuron\Application;

abstract class Base
{
	const HTML	= 1;
	const JSON	= 2;
	const XML	= 3;

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
	protected function setViewData( array $a )
	{
		$this->_aViewData = $a;
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
		echo xml_encode( $this->_aViewData );
	}
	//endregion

	//region Parameters
	protected function getParam( $sParam )
	{
		if( !isset( $this->_aParams[ $sParam ] ) )
			return false;

		return $this->_aParams[ $sParam ];
	}

	public function setParams( array $aParams )
	{
		$this->_aParams = $aParams;
	}
	//endregion

	//region Rendering
	protected function getRenderAction()
	{
		return $this->_sRenderAction;
	}

	protected function render( $sAction = null, $iType = 0 )
	{
		if( !$sAction)
			$sAction = $this->getRenderAction();

		if( !$iType )
			$iType = $this->getDataType();

		if( $iType == self::HTML )
		{
			extract( $this->_aViewData );

			$sViewPath = $this->_sViewPath . '/' . $this->getClass() . "/$sAction.php";
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
			throw new ControllerException( "Method '$sMethod'' not found." );

		return $this->$sMethod();
	}

	public function doesMethodExist( $sMethod )
	{
		return method_exists( $this, $sMethod );
	}

	public function action( $sAction, array $aParams, $iDataType )
	{
		$this->setDataType( $iDataType );
		$this->setRenderAction( $sAction );
		$this->_aParams = $aParams;

		$this->callMethod( $sAction );
	}
	//endregion

	public function getClass()
	{
		return get_class( $this );

		//return join( '', array_slice( explode( '\\', get_class( $this ) ), -1 ) );
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
