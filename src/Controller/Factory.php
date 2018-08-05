<?php

namespace Concept\Controller;

use Neuron\Application;

/**
 * Class Factory
 * @package Concept\Controller
 */
class Factory
{
	/**
	 * @param $sController
	 * @param Application\IApplication $Application
	 * @return mixed
	 */
	public static function create( $sController, Application\IApplication $Application )
	{
		return new $sController( $Application );
	}
}
