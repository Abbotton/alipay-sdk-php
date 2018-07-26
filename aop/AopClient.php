<?php

namespace Alipay;

use Alipay\Exception\AlipayException;
use Alipay\Exception\AlipayHttpException;
use Alipay\Request\AbstractAlipayRequest;

class AopClient
{
    /**
     * SDK 版本
     */
    const SDK_VERSION = 'alipay-sdk-php-20180705';

    /**
     * API 版本
     */
    const API_VERSION = '1.0';

    /**
     * 响应格式
     */
    const FORMAT = 'JSON';

    /**
     * 应用 ID
     *
     * @var string
     */
    protected $appId;

    /**
     * 签名器
     *
     * @var AlipaySign
     */
    protected $signer;

    /**
     * 请求回调函数
     *
     * @var callable
     */
    protected $requester;

    /**
     * 创建 AopClient 实例
     *
     * @param string     $appId     应用 ID，请在开放平台管理页面获取
     * @param AlipaySign $signer    签名器，用于生成 / 验证签名
     * @param callable   $requester 请求回调函数，形如 `function (string $url, array $params);`
     */
    public function __construct($appId, AlipaySign $signer, callable $requester = null)
    {
        $this->appId = $appId;
        $this->signer = $signer;
        $this->requester = $requester;
    }

    /**
     * 拼接请求参数并签名
     *
     * @param  AbstractAlipayRequest $request
     * @return array
     */
    public function build(AbstractAlipayRequest $request)
    {
        // 组装系统参数
        $sysParams = [];
        $sysParams['app_id'] = $this->appId;
        $sysParams['charset'] = $this->getCharset();

        $sysParams['version'] = static::API_VERSION;
        $sysParams['alipay_sdk'] = static::SDK_VERSION;
        $sysParams['format'] = static::FORMAT;

        $sysParams['sign_type'] = $this->signer->getSignType();

        $sysParams['method'] = $request->getApiMethodName();
        $sysParams['timestamp'] = $request->getTimestamp();
        $sysParams['notify_url'] = $request->getNotifyUrl();
        $sysParams['return_url'] = $request->getReturnUrl();

        // $sysParams['terminal_type'] = $request->getTerminalType();
        // $sysParams['terminal_info'] = $request->getTerminalInfo();
        // $sysParams['prod_code'] = $request->getProdCode();
        
        $sysParams['auth_token'] = $request->getAuthToken();
        $sysParams['app_auth_token'] = $request->getAppAuthToken();

        // 获取业务参数
        $apiParams = $request->getApiParams();

        // 签名
        $totalParams = array_merge($apiParams, $sysParams);
        $totalParams['sign'] = $this->signer->generateByParams($totalParams);
        return $totalParams;
    }

    /**
     * 拼接请求参数并签名后发起请求
     *
     * @param AbstractAlipayRequest $request
     * @return mixed
     */
    public function request(AbstractAlipayRequest $request)
    {
        $totalParams = $this->build($request);
        
        return call_user_func_array($this->requester, [$this->getGateway(), $totalParams]);
    }

    /**
     * 解析响应数据并验证签名
     *
     * @param mixed $raw 原始响应数据
     * @return AlipayResponse
     */
    public function response($raw)
    {
        $response = AlipayResponse::parse($raw);

        $this->signer->verify(
            $response->getSign(),
            $response->stripData()
        );

        return $response;
    }

    public function getGateway()
    {
        return 'https://openapi.alipay.com/gateway.do';
    }

    public function getCharset()
    {
        return 'UTF-8';
    }
}
