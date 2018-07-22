<?php

namespace Alipay\Exception;

class AlipaySignValidationError extends AlipayException
{
    public function __construct($exceptSign, $data)
    {
        parent::__construct("Sign: {$exceptSign}, Data: {$data}");
    }
}