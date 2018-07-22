<?php

namespace Alipay\Request;

abstract class AbstractAlipayRequest
{
    public static function className()
    {
        return __CLASS__;
    }

    abstract public function getApiMethodName();

    public function getApiVersion()
    {
        return '1.0';
    }

    abstract public function getNotifyUrl();

    abstract public function getApiParas();

    abstract public function getTerminalType();

    abstract public function getTerminalInfo();

    abstract public function getProdCode();
    
    abstract public function getReturnUrl();
}
