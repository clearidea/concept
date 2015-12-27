<?php

namespace Concept;

use Neuron;
use Neuron\Setting;
use Neuron\Data;

class Bootstrap
{
	static public function run( $configFile, Neuron\IApplication $App = null )
	{
		error_reporting( E_ALL | E_STRICT );

		$Config = new Setting\Source\Ini( $configFile );

		if( !$Config )
			die( 'Error: missing configuration file.' );

		date_default_timezone_set( $Config->get( 'concept', 'timezone' ) );

		$AppClass = $Config->get( 'concept', 'app_class' );

		if( !$App )
		{
			$App = new $AppClass();
		}

		$Filter = new Data\Filter\Get();

		$sRoute = $Filter->filterScalar( 'route' );

		if( !$sRoute )
			$sRoute = '/';

		return $App->setConfig( $Config )
			->run( [ 'route' => $sRoute ] );
	}
}
