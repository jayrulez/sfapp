<?php

namespace AppBundle\Helper;

use AppBundle\Entity\UserSetting;

class UserSettingHelper
{
	protected $em;

	public function __construct($em)
	{
		$this->em = $em;
	}

	public function set($user, $key, $value)
	{
		$userSetting = $this->em->getRepository('AppBundle:UserSetting')->findOne($user->getId(), $key);

		if($userSetting == null)
		{
			$userSetting = new UserSetting();

			$userSetting->setUserId($user->getId())
				->setUser($user);
		}

		$userSetting->setValue($value);

		$this->em->persist($userSetting);

		$this->em->flush();
	}

	public function get($user, $key, $default = null)
	{
		$userSetting = $this->em->getRepository('AppBundle:UserSetting')->findOne($user->getId(), $key);

		return $userSetting != null ? $userSetting->getValue() : $default;
	}
}