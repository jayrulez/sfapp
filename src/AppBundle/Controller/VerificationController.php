<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\VerificationCode;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\Result;
use AppBundle\Common\ErrorCode;

/**
 * @Route("/public")
 */
class VerificationController extends Controller
{
    /**
     * @Route("/verify_email_address", name="verify_email_address")
     * @Method({"POST"})
     */
    public function verifyEmailAddressAction(Request $request)
    {
        $result             = new Result();
        $emailAddressHelper = $this->get('email_address_helper');
    	$em                 = $this->getDoctrine()->getManager();
    	$code               = $request->request->get('code');
    	$address            = $emailAddressHelper->normalizeAddress($request->request->get('email_address'));

    	$verificationCodeHelper = $this->get('verification_code_helper');

    	try
    	{
    		$emailAddress = $em->getRepository('AppBundle:EmailAddress')->find($address);

    		if($emailAddress == null || $emailAddress->getVerified())
    		{
    			$result->setError('This email address cannot be verified', ErrorCode::INVALID_PARAMETER);
    			
	            return new ApiResponse($result);
    		}

    		$verificationCode = $verificationCodeHelper->find(VerificationCode::TYPE_EMAIL_ADDRESS, $emailAddress->getAddress());

    		if($verificationCode == null || $verificationCode->isExpired() || $code != $verificationCode->getCode())
    		{
                $result->setError('This verification code is invalid', ErrorCode::INVALID_PARAMETER);
                
                return new ApiResponse($result);
    		}

    		$emailAddress->setVerified(true)
    			->setUpdatedAt(new \DateTime('now'));

    		$em->persist($emailAddress);
    		$em->remove($verificationCode);
    		$em->flush();

            $result->setData($emailAddressHelper->serialize($emailAddress));

    		return new ApiResponse($result);
    	}catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/verify_mobile_number", name="verify_mobile_number")
     * @Method({"POST"})
     */
    public function verifyMobileNumberAction(Request $request)
    {
        $result             = new Result();
        $mobileNumberhelper = $this->get('mobile_number_helper');
        $em                 = $this->getDoctrine()->getManager();
        $code               = $request->request->get('code');
        $number             = $mobileNumberhelper->normalizeNumber($request->request->get('mobile_number'));

        $verificationCodeHelper = $this->get('verification_code_helper');

        try
        {
            $mobileNumber = $em->getRepository('AppBundle:MobileNumber')->findOneBy([
                'number' => $number
            ]);

            if($mobileNumber == null || $mobileNumber->getVerified())
            {
                $result->setError('This mobile number cannot be verified', ErrorCode::INVALID_PARAMETER);
                
                return new ApiResponse($result);
            }

            $verificationCode = $verificationCodeHelper->find(VerificationCode::TYPE_MOBILE_NUMBER, $mobileNumber->getNumber());

            if($verificationCode == null || $verificationCode->isExpired() || $code != $verificationCode->getCode())
            {
                $result->setError('This verification code is invalid', ErrorCode::INVALID_PARAMETER);
                
                return new ApiResponse($result);
            }

            $mobileNumber->setVerified(true)
                ->setUpdatedAt(new \DateTime('now'));

            $em->persist($mobileNumber);
            $em->remove($verificationCode);
            $em->flush();

            $result->setData($mobileNumberhelper->serialize($mobileNumber));

            return new ApiResponse($result);
        }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
