<?php

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Event\UserRegistrationEvent;
use AppBundle\Entity\VerificationCode;

class UserRegistrationListener
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function onComplete(UserRegistrationEvent $event)
	{
		$logger                 = $this->container->get('logger');
		$em                     = $this->container->get('doctrine')->getManager();
		$verificationCodeHelper = $this->container->get('verification_code_helper');

		try
		{
			$user    = $event->getUser();
			$request = $event->getRequest();

			foreach($user->getEmailAddresses() as $emailAddress)
			{
				$emailService = $this->container->get('email_service');
				$twig         = $this->container->get('twig');

				try
				{
					$subject      = 'Welcome';
					$body         = $twig->render('::email/welcome.html.twig', [
						'title' => $subject,
						'user'  => $user
					]);

					$emailService->sendMessage($emailAddress->getAddress(), $subject, $body);
				}catch(\Exception $e)
				{
					$logger->error(get_class($this) . ': ' . $e->getMessage());
				}

				if(!$emailAddress->getVerified())
				{
					try
					{
						// send email verification code
						$verificationCode = $verificationCodeHelper->findOrCreate(VerificationCode::TYPE_EMAIL_ADDRESS, $emailAddress->getAddress());

						$subject = 'Email Verification';
						$body    = $twig->render('::email/verifyEmail.html.twig', [
							'title'            => $subject,
							'verificationCode' => $verificationCode
						]);

						$emailService->sendMessage($emailAddress->getAddress(), $subject, $body);
					}catch(\Exception $e)
					{
						$logger->error(get_class($this) . ': ' . $e->getMessage());
					}
				}
			}

			foreach($user->getMobileNumbers() as $mobileNumber)
			{
				if(!$mobileNumber->getVerified())
				{
					//send mobile verification code
					$smsService = $this->container->get('sms_service');

					$verificationCode = $verificationCodeHelper->findOrCreate(VerificationCode::TYPE_MOBILE_NUMBER, $mobileNumber->getNumber());

					$message = 'Mobile verification code: ' . $verificationCode->getCode();

					$smsService->sendMessage($mobileNumber->getFullMobileNumber(), $message);
				}
			}

		}catch(\Exception $e)
		{
			$logger->error($e->getMessage());
		}
	}
}