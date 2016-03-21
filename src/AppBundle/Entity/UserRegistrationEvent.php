<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class UserRegistrationEvent extends Event
{
	const USER_REGISTRATION = 'app.user_registration';

	protected $user;

	protected $request;

	public function __construct($user, $request)
	{
		$this->user    = $user;
		$this->request = $request;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function getRequest()
	{
		return $this->request;
	}
}