<?php

namespace Alipay\Exception;

class AlipayInvalidSignException extends AlipayException
{
    public function __construct($sign, $data, $externalMessage = '')
    {
        $message = $externalMessage == '' ? '' : $externalMessage . ': ';
        $message .= "Sign = {$sign}, Data = {$data}";
        parent::__construct($message);
    }
}
