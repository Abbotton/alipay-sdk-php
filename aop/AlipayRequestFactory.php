<?php

namespace Alipay;

use Alipay\Exception\AlipayException;
use Alipay\Request\AbstractAlipayRequest;



class AlipayRequestFactory
{
    /**
     * 通过 API 名称创建请求类实例
     *
     * @return AbstractAlipayRequest
     */
    public static function createByApi($apiName)
    {
        $className = ucwords($apiName, '.');
        $className = str_replace('.', '', $className);
        $className .= 'Request';
        return static::create($className);
    }

    /**
     * 创建请求类实例
     *
     * @return AbstractAlipayRequest
     */
    public static function create($className)
    {
        $className = 'Alipay\Request' . '\\' . $className;
        if(!class_exists($className)) {
            throw new AlipayException("Request class `{$className}` doesn't exist");
        }
        $abstractClass = AbstractAlipayRequest::className();
        if(!is_subclass_of($className, $abstractClass))
        {
            throw new AlipayException("Given class {$className} is not a subclass of {$abstractClass}");
        }
        return new $className();
    }
}