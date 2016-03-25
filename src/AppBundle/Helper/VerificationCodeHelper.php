<?php

namespace AppBundle\Helper;

use AppBundle\Entity\VerificationCode;

class VerificationCodeHelper
{
	protected $em;

	public function __construct($em)
	{
		$this->em = $em;
	}

	public function find($type, $subject)
	{
		$verificationCode = $this->em->getRepository('AppBundle:VerificationCode')->findOneBy([
			'type'    => $type,
			'subject' => $subject
		]);

		return $verificationCode;
	}

	public function findOrCreate($type, $subject)
	{
		$verificationCode = $this->find($type, $subject);

		$now = new \DateTime('now');

		if($verificationCode == null)
		{
			$verificationCode = new VerificationCode();

			$verificationCode->setType($type)
				->setSubject($subject)
				->setCode(VerificationCode::generateCode($type))
				->setCreatedAt($now);

			$this->em->persist($verificationCode);
			$this->em->flush();
		}else if($verificationCode->isExpired())
		{
			$verificationCode->setCode(VerificationCode::generateCode($type))
				->setExpiresAt($now->add(new \DateInterval('P1D')))
				->setUpdatedAt($now);

			$this->em->persist($verificationCode);
			$this->em->flush();
		}

		return $verificationCode;
	}
}