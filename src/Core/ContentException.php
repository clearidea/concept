<?php

namespace Concept\Core;


class ContentException
	extends \Exception
{
	public function __construct( $sName )
	{
		parent::__construct( "Error loading content for '$sName'" );
	}
}
