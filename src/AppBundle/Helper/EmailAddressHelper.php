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
}