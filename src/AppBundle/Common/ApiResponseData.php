<?php

namespace AppBundle\Common;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ApiResponseData
{
	private $_successful;
	private $_error      = null;
	private $_data       = null;
	private $_statusCode = null;

	public function __construct($data = null, $statusCode = Response::HTTP_OK)
	{
		if($data instanceof Error)
		{
			$this->_error = $data;	
		}else{
			$this->_data = $data;
		}

		$this->_statusCode = $statusCode;
	}

	public function isSuccessful()
	{
		return empty($this->_errors) ? true : false;
	}

	public function getStatusCode()
	{
		return $this->_statusCode;
	}

	public function toJson()
	{
		$encoders = [new JsonEncoder()];

		$normalizer = new ObjectNormalizer();
		$normalizer->setCircularReferenceHandler(function($object) {
			return "";
		});

		$normalizers = [$normalizer];

		$serializer = new Serializer($normalizers, $encoders);

		$json = $serializer->serialize($this->_data, 'json');

		return $json;
	}
}