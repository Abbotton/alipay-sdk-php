<?php

namespace Alipay;

use Alipay\Exception\AlipayInvalidPropertyException;
use Alipay\Exception\AlipayInvalidRequestException;
use Alipay\Request\AlipayRequest;

class AlipayRequestFactory
{
    /**
     * 创建请求类实例
     *
     * @param $apiName
     * @param array $config
     *
     * @return AlipayRequest
     */
    public static function create($apiName, $config = [])
    {
        return call_user_func([new static(), 'createByApi'], $apiName, $config);
    }

    /**
     * 通过 `API 名称` 创建请求类实例.
     *
     * @param $apiName
     * @param array $config
     *
     * @throws AlipayInvalidRequestException
     *
     * @return AlipayRequest
     */
    private function createByApi($apiName, $config = [])
    {
        $config = array_merge($config, ['api_method_name' => $apiName]);
        $request = new AlipayRequest();

        try {
            foreach ($config as $key => $value) {
                $property = AlipayHelper::studlyCase($key, '_');
                $request->$property = $value;
            }
        } catch (AlipayInvalidPropertyException $ex) {
            throw new AlipayInvalidRequestException($ex->getMessage().': '.$key);
        }

        return $request;
    }
}
