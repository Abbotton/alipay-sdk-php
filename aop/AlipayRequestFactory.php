<?php

namespace Alipay;

use Alipay\Exception\AlipayInvalidPropertyException;
use Alipay\Exception\AlipayInvalidRequestException;
use Alipay\Request\AbstractAlipayRequest;

class AlipayRequestFactory
{
    /**
     * 通过 `API 名称` 创建请求类实例
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
     * 通过 `请求类名` 创建请求类实例
     *
     * @param string $className
     * @param array  $config
     *
     * @return AbstractAlipayRequest
     */
    public static function createByClass($className, $config = [])
    {
        $className = 'Alipay\Request' . '\\' . $className;

        static::validate($className);

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

    /**
     * 验证某类可否被创建
     *
     * @param string $className
     *
     * @return void
     */
    protected static function validate($className)
    {
        if (!class_exists($className)) {
            throw new AlipayInvalidRequestException("Class `{$className}` doesn't exist");
        }
        $abstractClass = AbstractAlipayRequest::className();
        if (!is_subclass_of($className, $abstractClass)) {
            throw new AlipayInvalidRequestException("Class {$className} doesn't extend {$abstractClass}");
        }
    }

    /**
     * 创建请求类实例
     *
     * @param string $classOrApi
     * @param array  $config
     *
     * @return AbstractAlipayRequest
     */
    public static function create($classOrApi, $config = [])
    {
        if (strpos($classOrApi, '.')) {
            return static::createByApi($classOrApi, $config);
        } else {
            return static::createByClass($classOrApi, $config);
        }
    }
}
