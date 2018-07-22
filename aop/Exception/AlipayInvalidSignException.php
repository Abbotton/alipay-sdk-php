<?php

namespace Alipay\Exception;

class AlipaySignValidationException extends AlipayException
{
    public function __construct($sign, $data, $externalMessage = '')
    {
        $message = $externalMessage == '' ? '' : $externalMessage . ': ';
        $message .= "Sign = {$sign}, Data = {$data}";
        parent::__construct($message);
    }
}