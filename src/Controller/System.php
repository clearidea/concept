<?php

namespace Concept\Controller;

class System extends Controller
{
	public function index()
	{
		$this->render();
	}

	public function _404()
	{
		$this->setViewData(
			[
				'missing' 	=> $this->getParam( 'missing' ),
			] );

		$this->render();
	}
}
