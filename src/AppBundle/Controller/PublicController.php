<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\VerificationCode;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\ApiResponseData;

class PublicController extends Controller
{
    /**
     * @Route("/api/public/verify_email_address", name="api_public_verify_email_address")
     */
    public function verifyEmailAddressAction(Request $request)
    {
    	$em      = $this->getDoctrine()->getManager();
    	$code    = $request->request->get('code');
    	$address = trim(strtolower($request->request->get('email_address')));
    	$responseData = new ApiResponseData();

    	$verificationCodeHelper = $this->get('verification_code_helper');

    	try
    	{
    		$emailAddress = $em->getRepository('AppBundle:EmailAddress')->find($address);

    		if($emailAddress == null || $emailAddress->getVerified())
    		{
    			$responseData->setError();
    			
	            return new ApiResponse([
	                'error'=>'invalid_email_address',
	                'error_description' => 'This email address cannot be verified.'
	            ]);
    		}

    		$verificationCode = $verificationCodeHelper->find(VerificationCode::TYPE_EMAIL_ADDRESS, $emailAddress->getAddress());

    		if($verificationCode == null || $verificationCode->isExpired() || $code != $verificationCode->getCode())
    		{
	            return new JsonResponse([
	                'error'=>'invalid_verification_code',
	                'error_description' => 'This verification code is invalid.'
	            ]);
    		}

    		$emailAddress->setVerified(true)
    			->setUpdatedAt(new \DateTime('now'));

    		$em->persist($emailAddress);
    		$em->remove($verificationCode);
    		$em->flush();

    		return new JsonResponse([], JsonResponse::HTTP_OK);
    	}catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            return new JsonResponse([
                'error'=>'internal_server_error',
                'error_description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
