<?php


namespace Concept\Core;

use Neuron;

class Controller
	extends ControllerBase
{
	public function __construct( Neuron\IApplication $Application )
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
