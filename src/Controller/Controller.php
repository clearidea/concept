<?php


namespace Concept\Controller;

use Neuron\Application;

class Controller
	extends Base
{
	public function __construct( Application\IApplication $Application )
	{
		parent::__construct( $Application );
	}

	public function index()
	{
		throw new ControllerException( 'Method not implemented.' );
	}

	public function show()
	{
		throw new ControllerException( 'Method not implemented.' );
	}

	public function add()
	{
		throw new ControllerException( 'Method not implemented.' );
	}

	public function create()
	{
		throw new ControllerException( 'Method not implemented.' );
	}

	public function edit()
	{
		throw new ControllerException( 'Method not implemented.' );
	}

	public function update()
	{
		throw new ControllerException( 'Method not implemented.' );
	}

	public function destroy()
	{
		throw new ControllerException( 'Method not implemented.' );
	}
}

?>
