<?php

namespace Concept\Controller;

use Neuron\Application;

class Factory
{
	public static function create( $sController, Application\IApplication $Application )
	{
		return new $sController( $Application );
	}
}
