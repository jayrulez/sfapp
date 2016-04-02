<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use AppBundle\Entity\EmailAddress;
use AppBundle\Entity\MobileNumber;
use AppBundle\Event\UserRegistrationEvent;
use AppBundle\Event\UserLoginEvent;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\Result;
use AppBundle\Common\ErrorCode;

/**
 * @Route("/public")
 */
class AuthController extends Controller
{
    /**
     * @Route("/register", name="v1_register", requirements={
     *     "_version": "^1$"
     * })
     * @Method({"POST"})
     */
    public function registerV1Action(Request $request)
    {
        $result     = new Result();

    	$em         = $this->getDoctrine()->getManager();
        $userHelper = $this->get('user_helper');

        $firstName  = $userHelper->normalizeFirstName($request->request->get('first_name'));
        $lastName   = $userHelper->normalizeLastName($request->request->get('last_name'));
        $password   = $request->request->get('password');
    	$username   = $userHelper->generateUsername($firstName, $lastName);

        $user       = $userHelper->createUser();

    	$user->setUsername($username)
            ->setFirstName($firstName)
            ->setLastName($lastName)
    		->setPassword($password)
            ->setCreatedAt(new \DateTime('now'));

        if(($address = $request->request->get('email_address', null)) != null)
        {
            $emailAddressHelper = $this->get('email_address_helper');

            $address = $emailAddressHelper->normalizeAddress($address);

            $emailAddress = $emailAddressHelper->createEmailAddress();
            $emailAddress->setAddress($address)
                ->setVerified(false)
                ->setCreatedAt(new \DateTime('now'));

            $user->setPrimaryEmailAddress($emailAddress->getAddress())
                ->getEmailAddresses()->add($emailAddress);

                $emailAddress->setUser($user);
        }else if(($countryCode = $request->request->get('country_code', null)) != null && ($number = $request->request->get('mobile_number', null)) != null)
        {
            $mobileNumberhelper = $this->get('mobile_number_helper');

            $countryCode  = $mobileNumberhelper->normalizeCountryCode($countryCode);
            $number       = $mobileNumberhelper->normalizeNumber($number);
            $mobileNumber = $mobileNumberhelper->createMobileNumber();

            $mobileNumber->setCountryCode($countryCode)
                ->setNumber($number)
                ->setVerified(false)
                ->setCreatedAt(new \DateTime('now'));

            $user->setPrimaryMobileNumber($mobileNumber->getFullMobileNumber())
                ->getMobileNumbers()->add($mobileNumber);

            $mobileNumber->setUser($user);
        }else{
            $result->setError('Mobile number or email address is required.', ErrorCode::VALIDATION_ERROR);
                
            return new ApiResponse($result);
        }

    	$errors = $this->get('validator')->validate($user);

    	if(count($errors) > 0)
    	{
            $result->setError($errors[0]->getMessage(), ErrorCode::VALIDATION_ERROR);
                
            return new ApiResponse($result);
    	}

        $em->getConnection()->beginTransaction();

        try
        {
            $user->setPassword($this->get('security.password_encoder')->encodePassword($user, $user->getPassword()));

            $em->persist($user);

            $em->flush();

            $em->getConnection()->commit();

            $em->refresh($user);

            try
            {
                $event           = new UserRegistrationEvent($user, $request);
                $eventDispatcher = $this->get('event_dispatcher');
                $eventDispatcher->dispatch(UserRegistrationEvent::USER_REGISTRATION, $event);
            }catch(\Exception $e)
            {
                $this->get('logger')->error($e->getMessage());
            }

            $result->setData($user, ["password"]);

            return new ApiResponse($result);
        }catch(\Exception $e)
        {
            $em->getConnection()->rollBack();

            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/register", name="v2_register", requirements={
     *     "_version": "^2$"
     * })
     * @Method({"POST"})
     */
    public function registerV2Action(Request $request)
    {
        $result     = new Result();

        $em         = $this->getDoctrine()->getManager();
        $userHelper = $this->get('user_helper');

        $firstName  = $userHelper->normalizeFirstName($request->request->get('first_name'));
        $lastName   = $userHelper->normalizeLastName($request->request->get('last_name'));
        $password   = $request->request->get('password');
        $username   = $userHelper->generateUsername($firstName, $lastName);

        $user       = $userHelper->createUser();

        $user->setUsername($username)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPassword($password)
            ->setCreatedAt(new \DateTime('now'));

        if(($address = $request->request->get('email_address', null)) != null)
        {
            $emailAddressHelper = $this->get('email_address_helper');

            $address = $emailAddressHelper->normalizeAddress($address);

            $emailAddress = $emailAddressHelper->createEmailAddress();
            $emailAddress->setAddress($address)
                ->setVerified(false)
                ->setCreatedAt(new \DateTime('now'));

            $user->setPrimaryEmailAddress($emailAddress->getAddress())
                ->getEmailAddresses()->add($emailAddress);

                $emailAddress->setUser($user);
        }else if(($countryCode = $request->request->get('country_code', null)) != null && ($number = $request->request->get('mobile_number', null)) != null)
        {
            $mobileNumberhelper = $this->get('mobile_number_helper');

            $countryCode  = $mobileNumberhelper->normalizeCountryCode($countryCode);
            $number       = $mobileNumberhelper->normalizeNumber($number);
            $mobileNumber = $mobileNumberhelper->createMobileNumber();

            $mobileNumber->setCountryCode($countryCode)
                ->setNumber($number)
                ->setVerified(false)
                ->setCreatedAt(new \DateTime('now'));

            $user->setPrimaryMobileNumber($mobileNumber->getFullMobileNumber())
                ->getMobileNumbers()->add($mobileNumber);

            $mobileNumber->setUser($user);
        }else{
            $result->setError('Mobile number or email address is required.', ErrorCode::VALIDATION_ERROR);
                
            return new ApiResponse($result);
        }

        $errors = $this->get('validator')->validate($user);

        if(count($errors) > 0)
        {
            $result->setError($errors[0]->getMessage(), ErrorCode::VALIDATION_ERROR);
                
            return new ApiResponse($result);
        }

        $em->getConnection()->beginTransaction();

        try
        {
            $user->setPassword($this->get('security.password_encoder')->encodePassword($user, $user->getPassword()));

            $em->persist($user);

            $em->flush();

            $em->getConnection()->commit();

            $em->refresh($user);

            try
            {
                $event           = new UserRegistrationEvent($user, $request);
                $eventDispatcher = $this->get('event_dispatcher');
                $eventDispatcher->dispatch(UserRegistrationEvent::USER_REGISTRATION, $event);
            }catch(\Exception $e)
            {
                $this->get('logger')->error($e->getMessage());
            }

            $result->setData($user, ["password"]);

            return new ApiResponse($result);
        }catch(\Exception $e)
        {
            $em->getConnection()->rollBack();

            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/login", name="login")
     * @Method({"POST"})
     */
    public function loginAction(Request $request)
    {
        $result       = new Result();
        $em           = $this->getDoctrine()->getEntityManager();
        $username     = $request->request->get('username');

        try
        {
            $userHelper         = $this->get('user_helper');
            $emailAddressHelper = $this->get('email_address_helper');
            $mobileNumberhelper = $this->get('mobile_number_helper');

            if(strpos($username, '@') !== false)
            {
                $emailAddress = $emailAddressHelper->findByAddress($username);

                if($emailAddress != null)
                {
                    $request->request->set('username', $emailAddress->getUser()->getUsername());
                }
            }else{
                $mobileNumber = $mobileNumberhelper->findByNumber($username);

                if($mobileNumber != null)
                {
                    $request->request->set('username', $mobileNumber->getUser()->getUsername());
                }
            }

            $subRequest = Request::create('/oauth/v2/token', 'POST', $request->request->all());
            $httpKernel = $this->get('http_kernel');
            $response   = $httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
            $tokenData  = json_decode($response->getContent(), true);

            if(isset($tokenData['access_token']))
            {
                try
                {
                    $event           = new UserLoginEvent($tokenData, $request);
                    $eventDispatcher = $this->get('event_dispatcher');
                    $eventDispatcher->dispatch(UserLoginEvent::USER_LOGIN, $event);
                }catch(\Exception $e)
                {
                    $this->get('logger')->error($e->getMessage());
                }

                $result->setData($tokenData);
            
                return new ApiResponse($result);
            }else{
                $result->setError(isset($tokenData['error_description']) ? $tokenData['error_description'] : 'Invalid credentials provided.', ErrorCode::INVALID_PARAMETER);

                return new ApiResponse($result, ApiResponse::HTTP_BAD_REQUEST);
            }

        }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
