<?php


namespace Concept\Core;

class Controller
	extends ControllerBase
{
	public function __construct( \Neuron\Setting\SettingManager $Settings )
	{
		parent::__construct( $Settings );
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
