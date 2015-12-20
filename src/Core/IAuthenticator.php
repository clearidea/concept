<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 10/6/15
 * Time: 7:17 AM
 */

namespace Concept\Core;


interface IAuthenticator
{
	const CREATE	= 1;
	const READ		= 2;
	const UPDATE	= 4;
	const DELETE	= 8;

	public function authenticateWithUserNameAndPassword( $sUserName, $sPassword );
	public function validateToken( $sToken );
	public function canAccessResource( $sResource, $iMask );
}
