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
use AppBundle\Event\UsernameChangeEvent;

/**
 * @Route("/users")
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

        $userHelper = $this->get('user_helper');

        $user = $userHelper->getUser();

        $result->setData($userHelper->serialize($user));

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
            $userHelper = $this->get('user_helper');
	        $user       = $userHelper->findByIdOrUsername($identity);

	        if($user == null)
	        {
	        	$result->setError("No user with id '$identity' was found.", ErrorCode::RESOURCE_NOT_FOUND);

	        	return new ApiResponse($result, ApiResponse::HTTP_NOT_FOUND);
	        }

	        $result->setData($userHelper->serialize($user));

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
        $userHelper = $this->get('user_helper');
        $user       = $userHelper->getUser();
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

	        $result->setData($userHelper->serialize($user));

            try
            {
                $event           = new UsernameChangeEvent($user);
                $eventDispatcher = $this->get('event_dispatcher');
                $eventDispatcher->dispatch(UsernameChangeEvent::USERNAME_CHANGE, $event);
            }catch(\Exception $e)
            {
                $this->get('logger')->error($e->getMessage());
            }

	        return new ApiResponse($result);
	    }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
	}

    /**
     * @Route("/set_first_name", name="set_first_name")
     * @Method({"POST"})
     */
    public function setFirstNameAction(Request $request)
    {
        $result     = new Result();
        $em         = $this->getDoctrine()->getManager();
        $userHelper = $this->get('user_helper');
        $user       = $userHelper->getUser();
        $firstName  = $request->request->get('first_name', null);
        $firstName  = $userHelper->normalizeFirstName($firstName);

        $errors     = $this->get('validator')->validatePropertyValue($userHelper->createUser(), 'firstName', $firstName);

        if(count($errors) > 0)
        {
            $result->setError($errors[0]->getMessage(), ErrorCode::VALIDATION_ERROR);
                
            return new ApiResponse($result);
        }

        try
        {
            $user->setFirstName($firstName)
                ->setUpdatedAt(new \DateTime('now'));

            $em->persist($user);

            $em->flush();

            $result->setData($userHelper->serialize($user));

            return new ApiResponse($result);
        }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/set_last_name", name="set_last_name")
     * @Method({"POST"})
     */
    public function setLastNameAction(Request $request)
    {
        $result     = new Result();
        $em         = $this->getDoctrine()->getManager();
        $userHelper = $this->get('user_helper');
        $user       = $userHelper->getUser();
        $lastName  = $request->request->get('last_name', null);
        $lastName  = $userHelper->normalizeLastName($lastName);

        $errors     = $this->get('validator')->validatePropertyValue($userHelper->createUser(), 'lastName', $lastName);

        if(count($errors) > 0)
        {
            $result->setError($errors[0]->getMessage(), ErrorCode::VALIDATION_ERROR);
                
            return new ApiResponse($result);
        }

        try
        {
            $user->setLastName($lastName)
                ->setUpdatedAt(new \DateTime('now'));

            $em->persist($user);

            $em->flush();

            $result->setData($userHelper->serialize($user));

            return new ApiResponse($result);
        }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/set_display_name", name="set_display_name")
     * @Method({"POST"})
     */
    public function setDisplayNameAction(Request $request)
    {
        $result     = new Result();
        $em         = $this->getDoctrine()->getManager();
        $userHelper = $this->get('user_helper');
        $user       = $userHelper->getUser();
        $option     = $request->request->get('display_name_option', null);

        if(!in_array($option, User::getDisplayNameOptions()))
        {
            $result->setError('Invalid display name option specified.', ErrorCode::VALIDATION_ERROR);
                
            return new ApiResponse($result);
        }

        try
        {
            $user->setDisplayNameOption($option)
                ->setUpdatedAt(new \DateTime('now'));

            $em->persist($user);

            $em->flush();

            $result->setData($userHelper->serialize($user));

            return new ApiResponse($result);
        }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
