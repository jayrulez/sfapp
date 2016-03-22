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
}