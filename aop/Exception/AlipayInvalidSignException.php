<?php

namespace Alipay\Exception;

class AlipayInvalidSignException extends AlipayException
{
    protected $sign;

    protected $data;

    public function __construct($sign, $data)
    {
        $this->sign = $sign;
        $this->data = $data;
        parent::__construct('Signature did not match.');
    }

    public function getSign()
    {
        return $this->sign;
    }

    public function getData()
    {
        return $this->data;
    }
}
