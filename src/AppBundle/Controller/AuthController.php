<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use AppBundle\Entity\EmailAddress;
use AppBundle\Entity\MobileNumber;
use AppBundle\Event\UserRegistrationEvent;
use AppBundle\Event\UserLoginEvent;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\Result;
use AppBundle\Common\ErrorCode;

/**
 * @Route("/api/public")
 */
class AuthController extends Controller
{
    /**
     * @Route("/register", name="register")
     * @Method({"POST"})
     */
    public function registerAction(Request $request)
    {
        $result    = new Result();
    	$em        = $this->getDoctrine()->getManager();
    	$user      = $em->getRepository('AppBundle:User')->createUser();
        $firstName = trim(ucwords(strtolower($request->request->get('first_name'))));
        $lastName  = trim(ucwords(strtolower($request->request->get('last_name'))));
        $password  = $request->request->get('password');
    	$username  = substr(strtolower($firstName), 0, 3) . substr(strtolower($lastName), 0, 3) . uniqid();

    	$user->setUsername($username)
            ->setFirstName($firstName)
            ->setLastName($lastName)
    		->setPassword($password)
    		->setRoles(['ROLE_USER'])
            ->setCreatedAt(new \DateTime('now'));

        if(($address = $request->request->get('email_address', null)) != null)
        {
            $address = trim(strtolower($address));

            $emailAddress = new EmailAddress();
            $emailAddress->setAddress($address)
                ->setVerified(false)
                ->setCreatedAt(new \DateTime('now'));

            $user->setPrimaryEmailAddress($emailAddress->getAddress())
                ->getEmailAddresses()->add($emailAddress);

                $emailAddress->setUser($user);
        }else if(($countryCode = $request->request->get('country_code', null)) != null && ($number = $request->request->get('mobile_number', null)) != null)
        {
            $countryCode = trim($countryCode);
            $number      = trim($number);

            $mobileNumber = new MobileNumber();

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

            $result->setStatusCode(ApiResponse::HTTP_INTERNAL_SERVER_ERROR)
                ->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result);
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
        $password     = $request->request->get('password');
        $clientId     = $request->request->get('client_id');
        $clientSecret = $request->request->get('client_secret');
        $grantType    = $request->request->get('grant_type');

        try
        {
            if(strpos($username, '@') !== false)
            {
                $username = trim(strtolower($username));

                $emailAddress = $em->getRepository('AppBundle:EmailAddress')->find($username);

                if($emailAddress != null)
                {
                    $username = $emailAddress->getUser()->getUsername();
                }
            }else{
                $mobileNumber = $em->getRepository('AppBundle:MobileNumber')->findOneBy([
                    'number' => $username
                ]);

                if($mobileNumber != null)
                {
                    $username = $mobileNumber->getUser()->getUsername();
                }
            }

            $parameters = [
                'username'      => $username,
                'password'      => $password,
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'grant_type'    => $grantType,
            ];

            $subRequest = Request::create('/api/oauth2/token', 'POST', $parameters);
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
                $result->setError('Invalid credentials provided.', ErrorCode::INVALID_CREDENTIALS);

                return new ApiResponse($result);
            }

        }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $this->get('logger')->error($e->getMessage());

            $result->setStatusCode(ApiResponse::HTTP_INTERNAL_SERVER_ERROR)
                ->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result);
        }
    }
}
