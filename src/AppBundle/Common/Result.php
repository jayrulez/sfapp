<?php

namespace AppBundle\Common;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use AppBundle\Normalizer\DateTimeNormalizer;

class Result
{
	private $_successful;
	private $_error             = null;
	private $_data              = null;
	private $_ignoredAttributes = [];

	public function __construct($data = null)
	{
		if($data instanceof Error)
		{
			$this->_error = $data;	
		}else{
			$this->_data = $data;
		}
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

	public function toJson()
	{
		$encoders           = [new JsonEncoder(new JsonEncode(JSON_UNESCAPED_SLASHES), new JsonDecode(false))];
		$objectNormalizer   = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());
		$dateTimeNormalizer = new DateTimeNormalizer();

		$objectNormalizer->setCircularReferenceHandler(function($object) {
			return "";
		});

		$objectNormalizer->setIgnoredAttributes($this->_ignoredAttributes);

		$normalizers = [$dateTimeNormalizer, $objectNormalizer];

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