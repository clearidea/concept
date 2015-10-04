<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/9/15
 * Time: 3:01 PM
 */

namespace Concept\Controller;


class User
	extends Controller
{
	public function index()
	{
		$this->render();
	}

	public function show()
	{
		global $User;
		$UM = new \Concept\Model\User();

		$User = $UM->getById( $this->getParam( 'id' ) );
		if( !$User )
			die( "Cannot locate user." );

		$this->setViewData( $User );

		$this->render();
	}

	public function edit()
	{
		$this->render();
	}

	public function add()
	{
		$this->render();
	}

	public function delete()
	{
		$this->roleRequired( 'role_admin' );

		$this->render();
	}

	public function update()
	{
		$this->render( 'show' );
	}
}
