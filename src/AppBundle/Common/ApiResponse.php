<?php

namespace AppBundle\Common;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * @param Result $data    The response data
     */
    public function __construct(Result $result, $headers = [])
    {
    	if(!isset($headers['Content-Type']))
    	{
    		$headers['Content-Type'] = 'application/json';
    	}

        parent::__construct($result->toJson(), $result->getStatusCode(), $headers, true);
    }
}