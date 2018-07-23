<?php

namespace Alipay\Exception;

class AlipayInvalidPropertyException extends AlipayException
{
    protected $property;

    public function __construct($message, $property = '')
    {
        $this->property = $property;
        parent::__construct($message);
    }

    public function getProperty()
    {
        return $this->property;
    }
}
