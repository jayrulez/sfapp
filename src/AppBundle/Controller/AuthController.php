<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\EmailAddress;
use AppBundle\Entity\MobileNumber;
use AppBundle\Event\UserRegistrationEvent;

class AuthController extends Controller
{
    /**
     * @Route("/api/auth/register", name="api_auth_register")
     * @Method({"POST"})
     */
    public function registerAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

    	$user = $em->getRepository('AppBundle:User')->createUser();

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
            return new JsonResponse([
                'error' => 'Mobile Number or Email Address is required.'
            ]);
        }

    	$errors = $this->get('validator')->validate($user);

    	if(count($errors) > 0)
    	{
        	return new JsonResponse([
        		'error' => $errors[0]->getMessage()
        	]);
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

            return new JsonResponse([
                'username' => $user->getUsername()
            ]);
        }catch(\Exception $e)
        {
            $em->getConnection()->rollBack();

            $this->get('logger')->error($e->getMessage());

            return new JsonResponse([
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/api/auth/login", name="api_auth_login")
     * @Method({"POST"})
     */
    public function loginAction(Request $request)
    {
        return new JsonResponse([]);
    }
}
