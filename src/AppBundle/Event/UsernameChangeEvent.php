<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class UsernameChangeEvent extends Event
{
	const USERNAME_CHANGE = 'app.username_change';

	protected $user;

	public function __construct($user)
	{
		$this->user   = $user;
	}

	public function getUser()
	{
		return $this->user;
	}
}