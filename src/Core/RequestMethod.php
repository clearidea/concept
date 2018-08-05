<?php

namespace Concept\Core;

class RequestMethod
{
	const PUT      = 1;
	const POST     = 2;
	const GET      = 3;
	const HEAD     = 4;
	const DELETE   = 5;
	const OPTIONS  = 6;
	const UNKNOWN  = 256;

	/**
	 * Gets the text string for a type.
	 * @return int
	 */
	static public function getType()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		switch ($method)
		{
			case 'PUT':
				return self::PUT;

			case 'POST':
				return self::POST;

			case 'GET':
				return self::GET;

			case 'HEAD':
				return self::HEAD;

			case 'DELETE':
				return self::DELETE;

			case 'OPTIONS':
				return self::OPTIONS;

			default:
				return self::UNKNOWN;
		}
	}
}
