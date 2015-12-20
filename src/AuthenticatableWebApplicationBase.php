<?php

namespace Concept;

use Concept\Controller\IAuthenticator;
use \Neuron;

abstract class AuthenticatableWebApplicationBase
	extends WebApplicationBase
	implements Core\IAuthenticator
{

}
