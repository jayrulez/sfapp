<?php

namespace AppBundle\Common;

class Error
{
	private $_code;
	private $_message = null;
	private $_exception = null;

	public function __construct($message = '', $code = '')
	{
		$this->_message = $message;
		$this->_code    = $code;
	}

	public function getMessage()
	{
		return $this->_message;
	}

	public function setException($exception)
	{
		$this->_exception = $exception;
		return $this;
	}

	public function setCode($code)
	{
		$this->_code = $code;
		return $this;
	}

	public function setMessage($message)
	{
		$this->_message = $message;
		return $this;
	}

	public function getException()
	{
		return $this->_exception;
	}

	public function getCode()
	{
		return $this->_code;
	}

	public function getError()
	{
		return $this->_code;
	}

	public function getErrorDescription()
	{
		return $this->_message;
	}
}
