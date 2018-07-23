<?php

namespace Alipay\Exception;

class AlipayBase64Exception extends AlipayException
{
    public function __construct($value, $isEncoding = false)
    {
        $verb = $isEncoding ? 'encoded' : 'decoded';
        parent::__construct("Value `{$value}` cound not be {$verb}");
    }
}
