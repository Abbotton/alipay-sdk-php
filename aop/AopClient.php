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
     * 应用 ID
     *
     * @var string
     */
    protected $appId;

    /**
     * AlipaySign
     *
     * @var AlipaySign
     */
    protected $signHelper;

    /**
     * API 接口地址
     *
     * @var string
     */
    protected $gatewayUrl = 'https://openapi.alipay.com/gateway.do';

    /**
     * 响应数据格式，官方目前只支持 JSON
     *
     * @var string
     */
    protected $format = 'JSON';

    /**
     * 数据提交编码
     *
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     * 创建 AopClient 实例
     *
     * @param string     $appId
     * @param AlipaySign $signHelper
     */
    public function __construct($appId, AlipaySign $signHelper)
    {
        $this->appId = $appId;
        $this->signHelper = $signHelper;
    }

    /**
     * 生成用于调用收银台 SDK 的字符串
     *
     * @param  AbstractAlipayRequest $request
     * @return string
     * @author guofa.tgf
     */
    public function sdkExecute(AbstractAlipayRequest $request)
    {
        $params = [];
        $params['app_id'] = $this->appId;
        $params['version'] = static::API_VERSION;
        $params['charset'] = $this->charset;
        $params['format'] = $this->format;
        $params['sign_type'] = $this->signHelper->getSignType();
        $params['method'] = $request->getApiMethodName();
        $params['timestamp'] = AlipayHelper::getTimestamp();
        $params['alipay_sdk'] = static::SDK_VERSION;
        $params['notify_url'] = $request->getNotifyUrl();

        $apiParams = $request->getApiParams();
        $params['biz_content'] = $apiParams['biz_content'];
        $params['sign'] = $this->signHelper->generateByParams($params);

        return http_build_query($params);
    }

    /**
     * 页面提交执行方法
     *
     * @param  AbstractAlipayRequest $request
     * @param  string                $httpMethod 提交方式。两个值可选：POST、GET
     * @return string 构建好的、签名后的最终跳转 URL（GET）或字符串形式的表单（POST）
     * @author 笙默
     */
    public function pageExecute(AbstractAlipayRequest $request, $httpMethod = 'POST')
    {
        // 组装系统参数
        $sysParams = [];
        $sysParams['app_id'] = $this->appId;
        $sysParams['version'] = static::API_VERSION;
        $sysParams['charset'] = $this->charset;
        $sysParams['format'] = $this->format;
        $sysParams['sign_type'] = $this->signHelper->getSignType();
        $sysParams['method'] = $request->getApiMethodName();
        $sysParams['timestamp'] = AlipayHelper::getTimestamp();
        $sysParams['alipay_sdk'] = static::SDK_VERSION;
        $sysParams['notify_url'] = $request->getNotifyUrl();
        $sysParams['return_url'] = $request->getReturnUrl();

        $sysParams['terminal_type'] = $request->getTerminalType();
        $sysParams['terminal_info'] = $request->getTerminalInfo();
        $sysParams['prod_code'] = $request->getProdCode();

        // 获取业务参数
        $apiParams = $request->getApiParams();

        // 签名
        $totalParams = array_merge($apiParams, $sysParams);
        $totalParams["sign"] = $this->signHelper->generateByParams($totalParams);

        $httpMethod = strtoupper($httpMethod);
        switch ($httpMethod) {
            case 'GET':
                $queryString = http_build_query($totalParams);
                $requestUrl = $this->gatewayUrl . "?" . $queryString;
                return $requestUrl;

            case 'POST':
                return $this->buildRequestForm($totalParams);

            default:
                throw new \InvalidArgumentException('Unsupported HTTP Method: ' . $httpMethod);
        }
    }

    /**
     * 建立请求，以表单 HTML 形式构造
     *
     * @param  array $params 请求参数数组
     * @return string 提交表单 HTML 文本
     */
    protected function buildRequestForm($params)
    {
        $charset = urlencode($this->charset);
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='{$this->gatewayUrl}?charset={$charset}' method='POST'>";
        foreach ($params as $key => $val) {
            if (false === AlipayHelper::isEmpty($val)) {
                $val = htmlentities($val, ENT_QUOTES | ENT_HTML5);
                $sHtml .= "<input type='hidden' name='{$key}' value='{$val}'/>";
            }
        }
        $sHtml .= "<input type='submit' value='ok' style='display:none;'></form>";
        $sHtml .= "<script>document.forms['alipaysubmit'].submit();</script>";
        return $sHtml;
    }

    /**
     * 执行请求
     *
     * @param  AbstractAlipayRequest $request
     * @param  string                $authToken
     * @param  string                $appInfoAuthtoken
     * @return AlipayResponse
     */
    public function execute(AbstractAlipayRequest $request, $authToken = '', $appInfoAuthtoken = '')
    {
        // 组装系统参数
        $sysParams = [];
        $sysParams['app_id'] = $this->appId;
        $sysParams['version'] = static::API_VERSION;
        $sysParams['charset'] = $this->charset;
        $sysParams['format'] = $this->format;
        $sysParams['sign_type'] = $this->signHelper->getSignType();
        $sysParams['method'] = $request->getApiMethodName();
        $sysParams['timestamp'] = AlipayHelper::getTimestamp();
        $sysParams['alipay_sdk'] = static::SDK_VERSION;
        $sysParams['notify_url'] = $request->getNotifyUrl();

        $sysParams['terminal_type'] = $request->getTerminalType();
        $sysParams['terminal_info'] = $request->getTerminalInfo();
        $sysParams['prod_code'] = $request->getProdCode();
        
        $sysParams['auth_token'] = $authToken;
        $sysParams['app_auth_token'] = $appInfoAuthtoken;

        // 获取业务参数
        $apiParams = $request->getApiParams();

        // 签名
        $totalParams = array_merge($apiParams, $sysParams);
        $totalParams['sign'] = $this->signHelper->generateByParams($totalParams);

        // 拼接请求地址
        $requestUrl = $this->gatewayUrl . '?charset=' . urlencode($this->charset);

        // 发起请求
        $resp = AlipayHelper::curl($requestUrl, $totalParams);

        $alipayResp = AlipayResponse::parse($resp, $this->format);

        $sign = $alipayResp->getSign();
        $signData = $alipayResp->stripData();

        // 验签
        $this->signHelper->verify($sign, $signData);

        return $alipayResp;
    }
}
