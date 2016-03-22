<?php

namespace AppBundle\Common;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * @param ApiResponseData $data    The response data
     */
    public function __construct(ApiResponseData $data, $headers = [])
    {
        parent::__construct($data->toJson(), $data->getStatusCode(), $headers, true);
    }
}