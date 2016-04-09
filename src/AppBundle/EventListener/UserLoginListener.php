<?php

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Event\UserLoginEvent;
use AppBundle\Entity\LoginAttempt;

class UserLoginListener
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function onSuccess(UserLoginEvent $event)
	{
		$logger = $this->container->get('logger');

		try
		{
			$token   = $event->getToken();
			$request = $event->getRequest();

			$username = $request->request->get('username', null);

			if($username != null)
			{
				$userHelper = $this->container->get('user_helper');

				$user = $userHelper->findByUsername($username);

				if($user != null)
				{
					$em = $this->container->get('doctrine.orm.entity_manager');

					$loginAttempt = new LoginAttempt();

					$loginAttempt->setUserId($user->getId())
						->setUser($user)
						->setStatus(LoginAttempt::STATUS_SUCCESS)
						->setIpAddress($this->container->get('request_stack')->getMasterRequest()->getClientIp())
						->setCreatedAt(new \DateTime('now'));

					$em->persist($loginAttempt);

					$em->flush();
				}
			}
		}catch(\Exception $e)
		{
			$logger->error($e->getMessage());
		}
	}

	public function onFailure(UserLoginEvent $event)
	{
		$logger = $this->container->get('logger');

		try
		{
			$request = $event->getRequest();

			$username = $request->request->get('username', null);

			if($username != null)
			{
				$userHelper = $this->container->get('user_helper');

				$user = $userHelper->findByUsername($username);

				if($user != null)
				{
					$em = $this->container->get('doctrine.orm.entity_manager');

					$loginAttempt = new LoginAttempt();

					$loginAttempt->setUserId($user->getId())
						->setUser($user)
						->setStatus(LoginAttempt::STATUS_FAILURE)
						->setIpAddress($this->container->get('request_stack')->getMasterRequest()->getClientIp())
						->setCreatedAt(new \DateTime('now'));

					$em->persist($loginAttempt);

					$em->flush();
				}
			}

		}catch(\Exception $e)
		{
			$logger->error($e->getMessage());
		}
	}
}