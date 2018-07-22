<?php

namespace Alipay;

use Alipay\Exception\AlipayCurlException;
use Alipay\Exception\AlipayException;
use Alipay\Exception\AlipayHttpException;
use Alipay\Request\AbstractAlipayRequest;

class AopClient
{
    /**
     * SDK 版本
     */
    const SDK_VERSION = "alipay-sdk-php-20180705";

    /**
     * 响应数据节点名后缀
     */
    const RESPONSE_SUFFIX = "_response";

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
    public function __construct($appId, $signHelper)
    {
        $this->appId = $appId;
        $this->signHelper = $signHelper;
    }

    /**
     * 使用 CURL 提交请求
     *
     * @param  string $url
     * @param  array  $postFields
     * @return void
     */
    protected function curl($url, $postFields)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        foreach ($postFields as $key => &$value) {
            if (is_string($value) && strpos($value, '@') === 0 && is_file(substr($value, 1))) {
                if (class_exists('CURLFile')) {
                    $value = new \CURLFile(substr($value, 1));
                }
            }
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new AlipayCurlException(curl_error($ch), curl_error($ch));
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new AlipayHttpException($response, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $response;
    }

    /**
     * 生成用于调用收银台SDK的字符串
     *
     * @param  AbstractAlipayRequest $request SDK接口的请求参数对象
     * @return string
     * @author guofa.tgf
     */
    public function sdkExecute(AbstractAlipayRequest $request)
    {
        $params['app_id'] = $this->appId;
        $params['method'] = $request->getApiMethodName();
        $params['format'] = $this->format;
        $params['sign_type'] = $this->signHelper->getSignType();
        $params['timestamp'] = date("Y-m-d H:i:s");
        $params['alipay_sdk'] = static::SDK_VERSION;
        $params['charset'] = $this->charset;
        $params['version'] = $request->getApiVersion();

        if ($notify_url = $request->getNotifyUrl()) {
            $params['notify_url'] = $notify_url;
        }

        $dict = $request->getApiParas();
        $params['biz_content'] = $dict['biz_content'];
        $params['sign'] = $this->signHelper->generateByParams($params);

        return http_build_query($params);
    }

    /**
     * 页面提交执行方法
     *
     * @param  AbstractAlipayRequest $request    跳转类接口的request
     * @param  string                $httpmethod 提交方式。两个值可选：post、get
     * @return string 构建好的、签名后的最终跳转URL（GET）或String形式的form（POST）
     * @author 笙默
     */
    public function pageExecute(AbstractAlipayRequest $request, $httpmethod = "POST")
    {
        //组装系统参数
        $sysParams["app_id"] = $this->appId;
        $sysParams["version"] = $request->getApiVersion();
        $sysParams["format"] = $this->format;
        $sysParams["sign_type"] = $this->signHelper->getSignType();
        $sysParams["method"] = $request->getApiMethodName();
        $sysParams["timestamp"] = date("Y-m-d H:i:s");
        $sysParams["alipay_sdk"] = static::SDK_VERSION;
        $sysParams["terminal_type"] = $request->getTerminalType();
        $sysParams["terminal_info"] = $request->getTerminalInfo();
        $sysParams["prod_code"] = $request->getProdCode();
        $sysParams["notify_url"] = $request->getNotifyUrl();
        $sysParams["return_url"] = $request->getReturnUrl();
        $sysParams["charset"] = $this->charset;

        //获取业务参数
        $apiParams = $request->getApiParas();

        //签名
        $totalParams = array_merge($apiParams, $sysParams);
        $totalParams["sign"] = $this->signHelper->generateByParams($totalParams);

        if ("GET" == strtoupper($httpmethod)) {
            //拼接GET请求串
            $preString = http_build_query($totalParams);
            $requestUrl = $this->gatewayUrl . "?" . $preString;
            return $requestUrl;
        } else {
            //拼接表单字符串
            return $this->buildRequestForm($totalParams);
        }
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     *
     * @param  array $params 请求参数数组
     * @return string 提交表单HTML文本
     */
    protected function buildRequestForm($params)
    {
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='" . $this->gatewayUrl . "?charset=" . $this->charset . "' method='POST'>";
        while (list($key, $val) = each($params)) {
            if (false === AlipayHelper::isEmpty($val)) {
                $val = str_replace("'", "&apos;", $val);
                $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
            }
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit' value='ok' style='display:none;''></form>";
        $sHtml = $sHtml . "<script>document.forms['alipaysubmit'].submit();</script>";
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
        //组装系统参数
        $sysParams["app_id"] = $this->appId;
        $sysParams["version"] = $request->getApiVersion();
        $sysParams["format"] = $this->format;
        $sysParams["sign_type"] = $this->signHelper->getSignType();
        $sysParams["method"] = $request->getApiMethodName();
        $sysParams["timestamp"] = date("Y-m-d H:i:s");
        $sysParams["auth_token"] = $authToken;
        $sysParams["alipay_sdk"] = static::SDK_VERSION;
        $sysParams["terminal_type"] = $request->getTerminalType();
        $sysParams["terminal_info"] = $request->getTerminalInfo();
        $sysParams["prod_code"] = $request->getProdCode();
        $sysParams["notify_url"] = $request->getNotifyUrl();
        $sysParams["charset"] = $this->charset;
        $sysParams["app_auth_token"] = $appInfoAuthtoken;

        //获取业务参数
        $apiParams = $request->getApiParas();

        //签名
        $totalParams = array_merge($apiParams, $sysParams);
        $sysParams["sign"] = $this->signHelper->generateByParams($totalParams);

        //系统参数放入GET请求串
        $requestUrl = $this->gatewayUrl . '?' . http_build_query($sysParams);

        //发起HTTP请求
        $resp = $this->curl($requestUrl, $apiParams);

        $alipayResp = AlipayResponse::parse($resp, $this->format);

        $sign = $alipayResp->getSign();
        $signData = $alipayResp->stripData();

        // 验签
        $this->signHelper->verify($sign, $signData);

        return $alipayResp;
    }
}
