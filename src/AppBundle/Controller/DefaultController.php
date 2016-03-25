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
     * @Route("/public/version", name="api_version")
     */
    public function apiVersionAction(Request $request)
    {
        $result     = new Result();
        $apiVersion = [
            'default_version'    => '1',
            'supported_versions' => ['1', '2']
        ];

        $result->setData($apiVersion);

        return new ApiResponse($result);
    }
}
