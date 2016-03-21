<?php

namespace AppBundle\EventListener;

use AppBundle\Event\UserLoginEvent;

class UserLoginListener
{
	protected $logger;

	public function __construct($logger)
	{
		$this->logger = $logger;
	}

	public function onComplete(UserLoginEvent $event)
	{
		try
		{
			$request = $event->getRequest();

		}catch(\Exception $e)
		{
			$this->logger->error($e->getMessage());
		}
	}
}