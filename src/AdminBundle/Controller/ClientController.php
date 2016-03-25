<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\Result;
use AppBundle\Common\ErrorCode;
use AppBundle\Entity\Client;

/**
 * @Route("/api/clients")
 */
class ClientController extends Controller
{
    /**
     * @Route("/create", name="create_client")
     * @Method({"POST"})
     */
	public function createAction(Request $request)
	{
        $result        = new Result();
        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $client        = $clientManager->createClient();

        try
        {
	        $client->setRedirectUris(array('http://localhost:8000'));
	        $client->setAllowedGrantTypes(array('token', 'authorization_code', 'client_credentials', 'password', 'refresh_token'));
	        $clientManager->updateClient($client);

	        $result->setData($client);

	        return new ApiResponse($result);
    	}catch(\Exception $e)
        {
            $this->get('logger')->error($e->getMessage());

            $result->setError($e->getMessage(), ErrorCode::INTERVAL_SERVER_ERROR);

            return new ApiResponse($result, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
	}
}
