<?php

namespace Alipay\Exception;

class AlipayResponseException extends AlipayException
{
    const ERROR_NODE = 'error_response';

    public function __construct($response, $externalMessage = '')
    {
        $errorResponse = $response->{static::ERROR_NODE};
    }
}