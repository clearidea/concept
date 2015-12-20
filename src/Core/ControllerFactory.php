<?php

namespace Concept\Core;

use Neuron;

class ControllerFactory
{
	public static function create( $sController, Neuron\IApplication $Application )
	{
		return new $sController( $Application );
	}
}
