<?php

namespace Alipay\Exception;

class AlipayResponseException extends AlipayException
{
    const ERROR_NODE = 'error_response';

    public function __construct($response, $externalMessage = '')
    {
        $errorResponse = $response->{static::ERROR_NODE};
        if (is_object($errorResponse)) {
            $msg = $externalMessage == '' ? '' : $externalMessage . ': ';
            $msg .= isset($errorResponse->msg) ? $errorResponse->msg : '';
            $msg .= isset($errorResponse->sub_msg) ? " ($errorResponse->sub_msg)" : '';

            $code = isset($errorResponse->code) ? $errorResponse->code : 0;
            parent::__construct($msg, $code);
        }
    }
}
