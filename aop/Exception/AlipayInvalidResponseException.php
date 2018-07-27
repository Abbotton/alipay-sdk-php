<?php

namespace Alipay\Exception;

use Alipay\AlipayResponse;

class AlipayInvalidResponseException extends AlipayException
{
    protected $response;

    public function __construct($response, $externalMessage = '')
    {
        $this->response = $response;

        if (is_array($response)) {
            $response = (object) $response;
        }

        if (is_object($response) && isset($response->{AlipayResponse::ERROR_NODE})) {
            $errorResponse = $response->{AlipayResponse::ERROR_NODE};
        } else {
            $errorResponse = $response;
        }

        $message = $externalMessage == '' ? '' : $externalMessage . ': ';
        if (is_array($errorResponse)) {
            $errorResponse = (object) $errorResponse;
        }
        if (is_object($errorResponse)) {
            $message .= isset($errorResponse->msg) ? $errorResponse->msg : '';
            $message .= isset($errorResponse->sub_msg) ? " ($errorResponse->sub_msg)" : '';
            $code = isset($errorResponse->code) ? $errorResponse->code : 0;
        } else {
            $message .= $errorResponse;
            $code = 0;
        }

        parent::__construct($message, $code);
    }

    public function getResponse()
    {
        return $this->response;
    }
}
