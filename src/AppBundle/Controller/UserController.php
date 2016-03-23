<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\Result;
use AppBundle\Common\ErrorCode;
use AppBundle\Entity\User;

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

    /**
     * @Route("/set_username", name="set_username")
     * @Method({"POST"})
     */
	public function setUsernameAction(Request $request)
	{
        $result     = new Result();
        $em         = $this->getDoctrine()->getManager();
	    $user       = $this->get('user_helper')->getUser();
        $userHelper = $this->get('user_helper');
        $username   = $request->request->get('username', null);
        $username   = $userHelper->normalizeUsername($username);

        $errors     = $this->get('validator')->validatePropertyValue($userHelper->createUser(), 'username', $username);

    	if(count($errors) > 0)
    	{
            $result->setError($errors[0]->getMessage(), ErrorCode::VALIDATION_ERROR);
                
            return new ApiResponse($result);
    	}

    	$owner = $userHelper->findByUsername($username);

    	if($owner != null && $owner->getId() != $user->getId())
    	{
            $result->setError('This username is already in use.', ErrorCode::VALIDATION_ERROR);
                
            return new ApiResponse($result);
    	}

        try
        {

	        $user->setUsername($username)
	        	->setUpdatedAt(new \DateTime('now'));

	        $em->persist($user);

	        $em->flush();

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
