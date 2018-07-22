<?php

namespace Alipay\Request;

abstract class AbstractAlipayRequest
{
    public abstract function getApiMethodName();

    public function getApiVersion()
    {
        return '1.0';
    }

    public abstract function AbstractAlipayRequest();

    public abstract function getApiParas();

    public abstract function getTerminalType();

    public abstract function getTerminalInfo();

    public abstract function getProdCode();
    
    public abstract function getReturnUrl();
    
}
