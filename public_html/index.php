<?php
error_reporting( E_ALL | E_STRICT );
date_default_timezone_set( 'US/Eastern' );

require '../vendor/autoload.php';

$Config = new Neuron\Setting\Source\Ini( '../config.ini' );

if( !$Config )
	die( 'Error: missing configuration file.' );

$AppClass = $Config->get( 'Concept', 'AppClass' );

$App = new $AppClass();

$Filter = new \Neuron\Data\Filter\Get();

$sRoute = $Filter->filterScalar( 'route' );

if( !$sRoute )
	$sRoute = '/';

$App->setConfig( $Config )
	->run( [ 'route' => $sRoute ] );
