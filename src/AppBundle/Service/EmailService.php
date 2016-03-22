<?php

namespace AppBundle\Service;

class EmailService
{
	private $mailer;
	private $message;

	public function __construct($mailer, $emailAddress, $name)
	{
		$this->message = new \Swift_Message();

		$this->message->setFrom([
			$emailAddress => $name
		]);

		$this->mailer = $mailer;
	}

	public function sendMessage($emailAddress, $subject, $body, $isHtml = true)
	{
		$this->message->setTo($emailAddress)
			->setSubject($subject)
			->setBody($body)
			->setContentType($isHtml ? "text/html": "text/plain");

		$this->mailer->send($this->message);
	}
}