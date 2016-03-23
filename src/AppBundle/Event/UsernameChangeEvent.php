<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class UsernameChangeEvent extends Event
{
	const USERNAME_CHANGE = 'app.username_change';

	protected $user;
	protected $oldUsername;

	public function __construct($user, $oldUsername)
	{
		$this->user   = $user;
		$this->oldUsername = $oldUsername;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function getOldUsername()
	{
		return $this->oldUsername;
	}
}