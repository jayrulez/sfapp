<?php

namespace AppBundle\Helper;

use AppBundle\Entity\SystemSetting;

class SystemSettingHelper
{
	protected $em;

	public function __construct($em)
	{
		$this->em = $em;
	}

	public function set($key, $value)
	{
		$systemSetting = $this->em->getRepository('AppBundle:SystemSetting')->findOne($key);

		if($systemSetting == null)
		{
			$systemSetting = new SystemSetting();
		}

		$systemSetting->setValue($value);

		$this->em->persist($systemSetting);

		$this->em->flush();
	}

	public function get($key, $default = null)
	{
		$systemSetting = $this->em->getRepository('AppBundle:SystemSetting')->findOne($key);

		return $systemSetting != null ? $systemSetting->getValue() : $default;
	}
}