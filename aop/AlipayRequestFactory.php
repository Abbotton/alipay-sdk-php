<?php

namespace Alipay;

use Alipay\Request\AlipayRequest;

class AlipayRequestFactory
{
    /**
     * 创建请求类实例.
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
     * @param  array  $config
     * @return AlipayRequest
     */
    private function createByApi($apiName, $config = [])
    {
        $config = array_merge($config, ['method' => $apiName]);

        return new AlipayRequest($config);
    }
}
