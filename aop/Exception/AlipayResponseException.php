<?php

namespace Alipay\Exception;

class AlipayResponseException extends AlipayException
{
    const ERROR_NODE = 'error_response';

    public function __construct($response, $externalMessage = '')
    {
        if (is_string($response)) {
            $response = json_decode($response);
        } elseif (is_array($response)) {
            $response = (object) $response;
        }
        if (!is_object($response) || !isset($response->{static::ERROR_NODE})) {
            $message = $externalMessage;
            $code = 0;
        } else {
            $errorResponse = $response->{static::ERROR_NODE};
            $message = $externalMessage == '' ? '' : $externalMessage . ': ';
            if (is_object($errorResponse)) {
                $message .= isset($errorResponse->msg) ? $errorResponse->msg : '';
                $message .= isset($errorResponse->sub_msg) ? " ($errorResponse->sub_msg)" : '';
                $code = isset($errorResponse->code) ? $errorResponse->code : 0;
            } else {
                $message .= $errorResponse;
                $code = 0;
            }
        }
        parent::__construct($message, $code);
    }
}
