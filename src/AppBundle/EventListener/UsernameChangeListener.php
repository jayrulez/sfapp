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
			$user        = $event->getUser();
			$oldUsername = $event->getOldUsername();
			$em          = $this->container->get('doctrine.orm.entity_manager');

			$accessTokens = $em->getRepository('AppBundle:AccessToken')->findBy(['username' => $oldUsername]);

			if($accessTokens != null)
			{
				foreach($accessTokens as $accessToken)
				{
					$accessToken->setUsername($user->getUsername());

					$em->persist($accessToken);
				}
			}

			$refreshTokens = $em->getRepository('AppBundle:RefreshToken')->findBy(['username' => $oldUsername]);

			if($refreshTokens != null)
			{
				foreach($refreshTokens as $refreshToken)
				{
					$refreshToken->setUsername($user->getUsername());

					$em->persist($refreshToken);
				}
			}

			$authorizations = $em->getRepository('AppBundle:Authorize')->findBy(['username' => $oldUsername]);

			if($authorizations != null)
			{
				foreach($authorizations as $authorization)
				{
					$authorization->setUsername($user->getUsername());

					$em->persist($authorization);
				}
			}

			$authCodes = $em->getRepository('AppBundle:Code')->findBy(['username' => $oldUsername]);

			if($authCodes != null)
			{
				foreach($authCodes as $authCode)
				{
					$authCode->setUsername($user->getUsername());

					$em->persist($authCode);
				}
			}

			$em->flush();

		}catch(\Exception $e)
		{
			$logger->error($e->getMessage());
		}
	}
}