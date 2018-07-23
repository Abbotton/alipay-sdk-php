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

    public function getApiMethodName()
    {
        $name = (new \ReflectionClass($this))->getShortName();
        $name = preg_replace('/Request$/', '', $name);
        $name = preg_replace('/([A-Z])/s', '.$1', $name);
        $name = trim($name, '.');
        $name = strtolower($name);
        return $name;
    }

    public function getApiVersion()
    {
        return '1.0';
    }

    abstract public function getApiParams();

    protected $notifyUrl;

    protected $returnUrl;

    protected $terminalType;

    protected $terminalInfo;

    protected $prodCode;

    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }
    
    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    public function getTerminalType()
    {
        return $this->terminalType;
    }

    public function setTerminalType($terminalType)
    {
        $this->terminalType = $terminalType;
    }

    public function getTerminalInfo()
    {
        return $this->terminalInfo;
    }

    public function setTerminalInfo($terminalInfo)
    {
        $this->terminalInfo = $terminalInfo;
    }

    public function getProdCode()
    {
        return $this->prodCode;
    }

    public function setProdCode($prodCode)
    {
        $this->prodCode = $prodCode;
    }

    public function __construct($config = [])
    {
        foreach ($config as $key => $value) {
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
