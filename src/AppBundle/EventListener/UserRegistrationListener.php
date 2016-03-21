<?php

namespace AppBundle\EventListener;

use AppBundle\Event\UserRegistrationEvent;

class UserRegistrationListener
{
	protected $logger;

	public function __construct($logger)
	{
		$this->logger = $logger;
	}

	public function onComplete(UserRegistrationEvent $event)
	{
		try
		{
			$user    = $event->getUser();
			$request = $event->getRequest();

		}catch(\Exception $e)
		{
			$this->logger->error($e->getMessage());
		}
	}
}