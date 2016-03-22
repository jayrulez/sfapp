<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Common\ApiResponse;

/**
 * @Route("/api/resource/users")
 */
class UserController extends Controller
{
    /**
     * @Route("/me", name="me")
     */
	public function meAction()
	{
        $result    = new Result();

        $result->setData($this->getUser(), ['password']);

        return new ApiResponse($result);
	}
}
