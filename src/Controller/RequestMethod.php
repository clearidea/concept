<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/14/15
 * Time: 5:04 PM
 */

namespace Concept\Controller;

class RequestMethod
{
	const PUT		= 1;
	const POST		= 2;
	const GET		= 3;
	const HEAD		= 4;
	const DELETE	= 5;
	const OPTIONS	= 6;
	const UNKNOWN	= 256;

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
