<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     * @Route("/api/public/version", name="api_version")
     */
    public function apiVersionAction(Request $request)
    {
        return new JsonResponse([]);
    }
}
