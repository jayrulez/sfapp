<?php

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Event\UserLoginEvent;

class UserLoginListener
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function onComplete(UserLoginEvent $event)
	{
		$logger = $this->container->get('logger');

		try
		{
			$token   = $event->getToken();
			$request = $event->getRequest();

		}catch(\Exception $e)
		{
			$logger->error($e->getMessage());
		}
	}
}