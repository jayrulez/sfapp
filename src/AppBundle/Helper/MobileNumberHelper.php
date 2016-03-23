<?php

namespace AppBundle\Helper;

use AppBundle\Entity\MobileNumber;

class MobileNumberHelper
{
	protected $em;

	public function __construct($em)
	{
		$this->em = $em;
	}

	public function createMobileNumber()
	{
		return new MobileNumber();
	}

	public function normalizeNumber($number)
	{
		return trim($number);
	}

	public function normalizeCountryCode($countryCode)
	{
		return trim($countryCode);
	}

	public function findByNumber($number)
	{
		$number = $this->normalizeNumber($number);

		return $this->em->getRepository('AppBundle:MobileNumber')->findOneBy([
			'number' => $number
		]);
	}
}