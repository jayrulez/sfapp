<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class UserRegistrationEvent extends Event
{
	const USER_REGISTRATION = 'app.user_registration';

	protected $user;

	protected $request;

	protected $emailAddress;

	protected $mobileNumber;

	public function __construct($user, $emailAddress, $mobileNumber, $request)
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

	public function getEmailAddress()
	{
		return $this->emailAddress;
	}

	public function getMobileNumber()
	{
		return $this->mobileNumber;
	}
}