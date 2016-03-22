<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class UserLoginEvent extends Event
{
	const USER_LOGIN = 'app.user_login';

	protected $token;
	protected $request;

	public function __construct($token, $request)
	{
		$this->token   = $token;
		$this->request = $request;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function getRequest()
	{
		return $this->request;
	}
}