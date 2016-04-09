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
use AppBundle\Entity\UserSetting;

/**
 * @Route("/user_settings")
 */
class UserSettingController extends Controller
{
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

        if(!in_array($option, UserSetting::getDisplayNameOptions()))
        {
            $result->setError('Invalid display name option specified.', ErrorCode::VALIDATION_ERROR);

            return new ApiResponse($result);
        }

        try
        {
        	$userHelper->setDisplayNameOption($user, $option);

            $result->setData([]);

            return new ApiResponse($result);
        }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/set_two_factor_method", name="set_two_factor_method")
     * @Method({"POST"})
     */
    public function setTwoFactorMethodAction(Request $request)
    {
        $result     = new Result();
        $em         = $this->getDoctrine()->getManager();
        $userHelper = $this->get('user_helper');
        $user       = $userHelper->getUser();
        $option     = $request->request->get('two_factor_method', null);

        if(!in_array($option, UserSetting::getTwoFactorMethodOptions()))
        {
            $result->setError('Invalid two factor method specified.', ErrorCode::VALIDATION_ERROR);

            return new ApiResponse($result);
        }

        try
        {
            $userHelper->setDisplayNameOption($user, $option);

            $result->setData([]);

            return new ApiResponse($result);
        }catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
