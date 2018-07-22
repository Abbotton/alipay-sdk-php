<?php

namespace Alipay\Exception;

class AlipaySignValidationException extends AlipayException
{
    public function __construct($exceptSign, $data)
    {
        parent::__construct("Sign: {$exceptSign}, Data: {$data}");
    }
}