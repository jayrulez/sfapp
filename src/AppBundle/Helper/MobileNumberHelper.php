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
		return preg_replace(['/\s+/', '/\+/'], '', $number);
	}

	public function normalizeCountryCode($countryCode)
	{
		return preg_replace(['/\s+/', '/\+/', '/-/'], '', $countryCode);
	}

	public function findByNumber($number)
	{
		$number = $this->normalizeNumber($number);

		return $this->em
			->createQuery('SELECT m FROM AppBundle:MobileNumber m WHERE m.number=:number OR CONCAT(m.countryCode, m.number)=:number')
			->setParameter(':number', $number)
			->getOneOrNullResult();
	}

	public function serialize($mobileNumber)
	{
		return $mobileNumber;
	}
}