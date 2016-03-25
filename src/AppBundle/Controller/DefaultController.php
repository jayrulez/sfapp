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
     * @Route("/public/version", name="api_version")
     */
    public function apiVersionAction(Request $request)
    {
        $result     = new Result();

        $result->setData([
            'version'    => $this->getParameter('api_version'),
            'supported_versions' => $this->getParameter('api_supported_versions')
        ]);

        return new ApiResponse($result);
    }
}
