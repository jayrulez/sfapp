<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\Result;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     * @Route("/api/public/version", name="api_version")
     */
    public function apiVersionAction(Request $request)
    {
        $result     = new Result();
        $apiVersion = [];

        $result->setData($apiVersion);

        return new ApiResponse($result);
    }
}
