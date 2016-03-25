<?php

namespace AppBundle\Common;

class ErrorCode
{
	const INTERVAL_SERVER_ERROR = 'internal_server_error';
	const VALIDATION_ERROR      = 'validation_error';
	const INVALID_CREDENTIALS   = 'invalid_credentials';
	const INVALID_EMAIL_ADDRESS = 'invalid_email_address';
	const INVALID_MOBILE_NUMBER = 'invalid_mobile_number';
	const INVALID_VERIFICATION_CODE = 'invalid_verification_code';
	const RESOURCE_NOT_FOUND        = 'resource_not_found';
}