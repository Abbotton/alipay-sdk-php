<?php

namespace Alipay;

use Alipay\Exception\AlipayInvalidPropertyException;
use Alipay\Exception\AlipayInvalidRequestException;
use Alipay\Request\AbstractAlipayRequest;

class AlipayRequestFactory
{
    /**
     * 通过 API 名称创建请求类实例
     *
     * @param string $apiName
     * @param array  $config
     *
     * @return AbstractAlipayRequest
     */
    public static function createByApi($apiName, $config = [])
    {
        $className = AlipayHelper::studlyCase($apiName, '.') . 'Request';

        return static::create($className, $config);
    }

    /**
     * 创建请求类实例
     *
     * @param string $className
     * @param array  $config
     *
     * @return AbstractAlipayRequest
     */
    public static function create($className, $config = [])
    {
        $className = 'Alipay\Request' . '\\' . $className;

        if (!class_exists($className)) {
            throw new AlipayInvalidRequestException("Request class `{$className}` doesn't exist");
        }
        $abstractClass = AbstractAlipayRequest::className();
        if (!is_subclass_of($className, $abstractClass)) {
            throw new AlipayInvalidRequestException("Given class {$className} is not a subclass of {$abstractClass}");
        }

        $instance = new $className();

        try {
            foreach ($config as $key => $value) {
                $property = AlipayHelper::studlyCase($key, '_');
                $instance->$property = $value;
            }
        } catch (AlipayInvalidPropertyException $ex) {
            throw new AlipayInvalidRequestException($ex->getMessage() . ': ' . $key);
        }

        return $instance;
    }
}
