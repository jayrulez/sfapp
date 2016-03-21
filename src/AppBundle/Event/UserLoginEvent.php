<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class UserLoginEvent extends Event
{
	const USER_LOGIN = 'app.user_login';

	protected $request;

	public function __construct($request)
	{
		$this->request = $request;
	}

	public function getRequest()
	{
		return $this->request;
	}
}