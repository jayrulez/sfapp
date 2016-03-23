<?php

namespace AppBundle\Helper;

use AppBundle\Entity\User;

class UserHelper
{
	protected $em;
	protected $tokenStorage;
	protected $mobileNumberHelper;
	protected $emailAddressHelper;

	public function __construct($em, $tokenStorage, $mobileNumberHelper, $emailAddressHelper)
	{
		$this->em           = $em;
		$this->tokenStorage = $tokenStorage;
	}

	public function findById($id)
	{
		return $this->em->getRepository('AppBundle:User')->find($id);
	}

	public function findByUsername($username)
	{
		$username = $this->normalizeUsername($username);

		return $this->em->getRepository('AppBundle:User')->findOneBy(['username' => $username]);
	}

	public function findByIdOrUsername($identity)
	{
		if(is_string($identity))
		{
			return $this->findByUsername($identity);
		}

		$query = $this->em->createQuery('SELECT u FROM AppBundle:User u WHERE u.id=:id OR u.username=:username');

		$query->setParameters([
			'id'       => $identity,
			'username' => $this->normalizeUsername($identity)
		]);

		return $query->getOneOrNullResult();
	}

	public function findByEmailAddress($address)
	{
		$emailAddress = $this->emailAddressHelper->findByAddress($address);

		return $emailAddress != null ? $emailAddress->getUser() : null;
	}

	public function findByMobileNumber($number)
	{
		$mobileNumber = $this->mobileNumberHelper->findByNumber($number);

		return $mobileNumber != null ? $mobileNumber->getUser() : null;
	}

	public function getUser()
	{
		return $this->findByUsername($this->tokenStorage->getToken()->getUser());
	}

	public function createUser()
	{
		return $this->em->getRepository('AppBundle:User')->createUser();
	}

	public function generateUsername($firstName, $lastName)
	{
		return $this->normalizeUsername(substr(strtolower($firstName), 0, 3) . substr(strtolower($lastName), 0, 3) . uniqid());
	}

	public function normalizeUsername($username)
	{
		return trim(strtolower($username));
	}

	public function normalizeFirstName($firstName)
	{
		return trim(ucwords(strtolower($firstName)));
	}

	public function normalizeLastName($lastName)
	{
		return trim(ucwords(strtolower($lastName)));
	}
}