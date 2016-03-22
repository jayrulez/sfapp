<?php

namespace AppBundle\Common;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Result
{
	private $_successful;
	private $_error             = null;
	private $_data              = null;
	private $_statusCode        = null;
	private $_ignoredAttributes = [];

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

	public function setData($data, array $ignoredAttributes = [])
	{
		$this->_data = $data;

		$this->_ignoredAttributes = $ignoredAttributes;

		return $this;
	}

	public function setError($message, $code = '')
	{
		$this->_error = new Error($message, $code);

		return $this;
	}

	private function isSuccessful()
	{
		return $this->_error == null ? true : false;
	}

	public function getStatusCode()
	{
		return $this->_statusCode;
	}

	public function toJson()
	{
		$encoders   = [new JsonEncoder(new JsonEncode(JSON_UNESCAPED_SLASHES), new JsonDecode(false))];
		$normalizer = new ObjectNormalizer();

		$normalizer->setCircularReferenceHandler(function($object) {
			return "";
		});

		$normalizer->setIgnoredAttributes($this->_ignoredAttributes);

		$normalizers = [$normalizer];

		$serializer = new Serializer($normalizers, $encoders);

		$jsonData = [
			"success" => $this->isSuccessful()
		];

		if($this->isSuccessful())
		{
			$jsonData["data"] = $this->_data;
		}else{
			$jsonData["data"] = $this->_error;
		}

		$json = $serializer->serialize($jsonData, 'json');

		return $json;
	}
}