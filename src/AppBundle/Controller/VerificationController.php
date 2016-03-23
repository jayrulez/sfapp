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
 * @Route("/api/public")
 */
class VerificationController extends Controller
{
    /**
     * @Route("/verify_email_address", name="verify_email_address")
     * @Method({"POST"})
     */
    public function verifyEmailAddressAction(Request $request)
    {
        $result  = new Result();
    	$em      = $this->getDoctrine()->getManager();
    	$code    = $request->request->get('code');
    	$address = trim(strtolower($request->request->get('email_address')));

    	$verificationCodeHelper = $this->get('verification_code_helper');

    	try
    	{
    		$emailAddress = $em->getRepository('AppBundle:EmailAddress')->find($address);

    		if($emailAddress == null || $emailAddress->getVerified())
    		{
    			$result->setError('This email address cannot be verified', ErrorCode::INVALID_EMAIL_ADDRESS);
    			
	            return new ApiResponse($result);
    		}

    		$verificationCode = $verificationCodeHelper->find(VerificationCode::TYPE_EMAIL_ADDRESS, $emailAddress->getAddress());

    		if($verificationCode == null || $verificationCode->isExpired() || $code != $verificationCode->getCode())
    		{
                $result->setError('This verification code is invalid', ErrorCode::INVALID_VERIFICATION_CODE);
                
                return new ApiResponse($result);
    		}

    		$emailAddress->setVerified(true)
    			->setUpdatedAt(new \DateTime('now'));

    		$em->persist($emailAddress);
    		$em->remove($verificationCode);
    		$em->flush();

            $result->setData($emailAddress);

    		return new ApiResponse($result);
    	}catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
