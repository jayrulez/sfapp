<?php

namespace AppBundle\Helper;

use AppBundle\Entity\User;

class UserHelper
{
	protected $em;

	public function __construct($em)
	{
		$this->em = $em;
	}
}