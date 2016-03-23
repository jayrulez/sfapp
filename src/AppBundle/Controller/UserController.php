<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\Result;
use AppBundle\Common\ErrorCode;

/**
 * @Route("/api/resource/users")
 */
class UserController extends Controller
{
    /**
     * @Route("/me", name="me")
     * @Method({"GET"})
     */
	public function meAction()
	{
        $result = new Result();

        $user = $this->get('user_helper')->getUser();

        $result->setData($user, ['password', 'salt']);

        return new ApiResponse($result);
	}

    /**
     * @Route("/{identity}", name="user")
     * @Method({"GET"})
     */
	public function userAction($identity)
	{
        $result = new Result();

        try
        {
	        $user = $this->get('user_helper')->findByIdOrUsername($identity);

	        if($user == null)
	        {
	        	$result->setError("No user with id '$identity' was found.", ErrorCode::RESOURCE_NOT_FOUND);

	        	return new ApiResponse($result, ApiResponse::HTTP_NOT_FOUND);
	        }

	        $result->setData($user, ['password', 'salt']);

	        return new ApiResponse($result);
	    }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
	}
}
