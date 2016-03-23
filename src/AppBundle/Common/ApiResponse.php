<?php

namespace AppBundle\Common;

use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends Response
{
    /**
     * @param Result $data    The response data
     */
    public function __construct(Result $result, $httpStatusCode = self::HTTP_OK, $headers = [])
    {
    	if(!isset($headers['Content-Type']))
    	{
    		$headers['Content-Type'] = 'application/json';
    	}

        parent::__construct($result->toJson(), $httpStatusCode, $headers, true);
    }
}