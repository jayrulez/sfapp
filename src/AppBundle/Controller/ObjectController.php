<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\Result;

/**
 * @Route("")
 */
class ObjectController extends Controller
{
    /**
     * @Route("/react", name="react")
     * @Method({"POST"})
     */
    public function reactAction(Request $request)
    {
        $result     = new Result();

        try
        {
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
     * @Route("/share", name="share")
     * @Method({"POST"})
     */
    public function reactAction(Request $request)
    {
        $result     = new Result();

        try
        {
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
