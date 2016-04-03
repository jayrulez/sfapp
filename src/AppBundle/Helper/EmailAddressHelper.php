<?php

namespace AppBundle\Helper;

use AppBundle\Entity\EmailAddress;

class EmailAddressHelper
{
	protected $em;

	public function __construct($em)
	{
		$this->em = $em;
	}

	public function createEmailAddress()
	{
		return new EmailAddress();
	}

	public function normalizeAddress($address)
	{
		return trim(strtolower($address));
	}

	public function findByAddress($address)
	{
		$address = $this->normalizeAddress($address);

		return $this->em->getRepository('AppBundle:EmailAddress')->find($address);
	}

	public function serialize($emailAddress)
	{
		return $emailAddress;
	}
}