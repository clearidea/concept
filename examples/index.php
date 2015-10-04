<?php
error_reporting( E_ALL | E_STRICT );
date_default_timezone_set( 'US/Eastern' );

require '../vendor/autoload.php';

$App = new MyApplication();

$sRoute = filter_input( INPUT_GET, 'route' );

if( !$sRoute )
	$sRoute = '/';

$App->setConfig( new Neuron\Setting\Source\Ini( '../config.ini')  )
	->run( [ 'route' => $sRoute ] );
