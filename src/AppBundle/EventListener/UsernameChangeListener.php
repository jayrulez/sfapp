<?php

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Event\UsernameChangeEvent;

class UsernameChangeListener
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function onComplete(UsernameChangeEvent $event)
	{
		$logger = $this->container->get('logger');

		try
		{
			$user = $event->getUser();

		}catch(\Exception $e)
		{
			$logger->error($e->getMessage());
		}
	}
}