<?php

namespace AppBundle\Helper;

use AppBundle\Entity\User;
use AppBundle\Entity\UserSetting;

class UserHelper
{
	protected $em;
	protected $tokenStorage;
	protected $mobileNumberHelper;
	protected $emailAddressHelper;
	protected $userSettingHelper;

	public function __construct($em, $tokenStorage, $mobileNumberHelper, $emailAddressHelper, $userSettingHelper)
	{
		$this->em           = $em;
		$this->tokenStorage = $tokenStorage;
		$this->mobileNumberHelper = $mobileNumberHelper;
		$this->emailAddressHelper= $emailAddressHelper;
		$this->userSettingHelper= $userSettingHelper;
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
		return $this->tokenStorage->getToken()->getUser();
	}

	public function createUser()
	{
		return new User();
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

    public function getDisplayName($user)
    {
        $displayName = $user->getFullName();

        $displayNameOption = $this->userSettingHelper->get($user, UserSetting::KEY_DISPLAY_NAME_OPTION);

        switch($displayNameOption)
        {
            case UserSetting::DISPLAY_NAME_USERNAME:
                $displayName = $user->getUsername();
            break;

            case UserSetting::DISPLAY_NAME_FIRST_INITIAL_LAST_NAME:
                $displayName = substr($user->getFirstName(), 0, 1) . ' ' . $user->getLastName();
            break;

            case UserSetting::DISPLAY_NAME_FIRST_NAME_LAST_INITIAL:
                $displayName = $user->getFirstName() . ' ' . substr($user->getLastName(), 0, 1);
            break;

            case UserSetting::DISPLAY_NAME_FULL_NAME:
            default:
                $displayName = $user->getFullName();
        }

        return $displayName;
    }

    public function setDisplayNameOption($user, $option)
    {
    	$this->userSettingHelper->set($user, UserSetting::KEY_DISPLAY_NAME_OPTION, $option);
    }

	public function serialize($user)
	{
		if($user == null)
		{
			return [];
		}

		return [
			'id' => $user->getId(),
			'username' => $user->getUsername()
		];
	}
}