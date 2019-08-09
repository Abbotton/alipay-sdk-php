<?php

namespace Alipay;

class AlipayRequester
{
    const ALIPAY_OPEN_API_GATEWAY_PROD = "https://openapi.alipay.com/gateway.do";
    const ALIPAY_OPEN_API_GATEWAY_SANDBOX = "https://openapi.alipaydev.com/gateway.do";
    protected $gateway;

    protected $charset;

    protected $callback;

    public function __construct(
        callable $callback,
        $gateway = self::ALIPAY_OPEN_API_GATEWAY_PROD,
        $charset = 'UTF-8'
    )
    {
        $this->callback = $callback;
        $this->gateway = $gateway;
        $this->charset = $charset;
    }

    public function getGateway()
    {
        return $this->gateway;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function getUrl()
    {
        return $this->getGateway() . '?charset=' . urlencode($this->getCharset());
    }

    /**
     * 提交请求
     *
     * @param array $params
     *
     * @return mixed
     */
    public function execute($params)
    {
        return call_user_func($this->callback, $this->getUrl(), $params);
    }
}
