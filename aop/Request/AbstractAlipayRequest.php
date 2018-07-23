<?php

namespace Alipay\Request;

use Alipay\Exception\AlipayException;
use Alipay\Exception\AlipayInvalidPropertyException;

abstract class AbstractAlipayRequest
{
    public static function className()
    {
        return __CLASS__;
    }

    public function getApiMethodNameNew()
    {
        $name = (new \ReflectionClass($this))->getShortName();
        $name = preg_replace('/Request$/', '', $name);
        $name = preg_replace('/([A-Z])/s','.$1', $name);
        $name = trim($name, '.');
        $name = strtolower($name);
        return $name;
    }

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

    public function __construct($config = [])
    {
        foreach($config as $key => $value) {
            $this->$key = $value;
        }
    }

    public function __destruct()
    {
    }

    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new AlipayInvalidPropertyException('Getting write-only property', $name);
        }
        throw new AlipayInvalidPropertyException('Getting unknown property', $name);
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new AlipayInvalidPropertyException('Setting read-only property', $name);
        } else {
            throw new AlipayInvalidPropertyException('Setting unknown property', $name);
        }
    }

    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }
        return false;
    }

    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new AlipayInvalidPropertyException('Unsetting read-only property', $name);
        }
    }
}
