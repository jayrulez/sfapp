<?php

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use AppBundle\Common\ApiResponse;
use AppBundle\Common\Result;
use AppBundle\Common\ErrorCode;

class ApiVersionListener
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

    /**
     * Listen for request events
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onCoreRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType())
        {
            return;
        }

        $request           = $event->getRequest();
        $supportedVersions = $this->container->getParameter('api_supported_versions');
        $requestVersion    = $request->attributes->get('_version');

        if(empty($requestVersion))
        {
            return;
        }

        if(!in_array($requestVersion, $supportedVersions))
        {
        	$result = new Result();

            $result->setError("The requested api version 'v$requestVersion' is not supported.", ErrorCode::UNSUPPORTED_API_VERSION);

            $response = new ApiResponse($result, ApiResponse::HTTP_NOT_FOUND);

            $event->setResponse($response);
        }
    }
}