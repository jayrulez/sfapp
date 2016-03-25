<?php

namespace AppBundle\Common;

class ErrorCode
{
	const INTERVAL_SERVER_ERROR = 'internal_server_error';
	const VALIDATION_ERROR      = 'validation_error';
	const INVALID_PARAMETER     = 'invalid_parameter';
	const MISSING_PARAMETER     = 'missing_parameter';
	const RESOURCE_NOT_FOUND    = 'resource_not_found';
}