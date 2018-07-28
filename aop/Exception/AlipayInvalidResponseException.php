<?php

namespace Alipay\Exception;

class AlipayInvalidResponseException extends AlipayException
{
    protected $response;

    public function __construct($response, $message = '')
    {
        $this->response = $response;

        $message = $message == '' ? '' : $message . ': ';
        $message .= $response;

        parent::__construct($message);
    }

    public function getResponse()
    {
        return $this->response;
    }
}
