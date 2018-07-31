<?php

namespace Alipay;

use Alipay\Key\AlipayKeyPair;
use Alipay\Request\AbstractAlipayRequest;
use Alipay\Signer\AlipayRSA2Signer;
use Alipay\Signer\AlipaySigner;

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
     * 签名器
     *
     * @var AlipaySigner
     */
    protected $signer;

    /**
     * 请求回调
     *
     * @var AlipayRequester
     */
    protected $requester;

    /**
     * 响应解析器
     *
     * @var AlipayResponseFactory
     */
    protected $parser;

    /**
     * 密钥对
     *
     * @var AlipayKeyPair
     */
    protected $keyPair;

    /**
     * 创建 AopClient 实例
     *
     * @param string                $appId     应用 ID，请在开放平台管理页面获取
     * @param AlipayKeyPair         $keyPair   密钥对
     * @param AlipaySigner          $signer    签名器，用于生成 / 验证签名
     * @param AlipayRequester       $requester 请求器，用于发送 HTTP 请求
     * @param AlipayResponseFactory $parser    响应解析器，用于解析服务器原始响应
     */
    public function __construct($appId, AlipayKeyPair $keyPair, AlipaySigner $signer = null, AlipayRequester $requester = null, AlipayResponseFactory $parser = null)
    {
        $this->appId = $appId;
        $this->keyPair = $keyPair;
        $this->signer = $signer === null ? new AlipayRSA2Signer() : $signer;
        $this->requester = $requester === null ? new AlipayCurlRequester() : $requester;
        $this->parser = $parser === null ? new AlipayResponseFactory() : $parser;
    }

    /**
     * 拼接请求参数并签名
     *
     * @param AbstractAlipayRequest $request
     *
     * @return array
     */
    public function build(AbstractAlipayRequest $request)
    {
        // 组装系统参数
        $sysParams = [];
        $sysParams['app_id'] = $this->appId;
        $sysParams['version'] = static::API_VERSION;
        $sysParams['alipay_sdk'] = static::SDK_VERSION;

        $sysParams['charset'] = $this->requester->getCharset();
        $sysParams['format'] = $this->parser->getFormat();
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
        $totalParams['sign'] = $this->signer->generateByParams(
            $totalParams,
            $this->keyPair->getPrivateKey()
        );

        return $totalParams;
    }

    /**
     * 发起请求、解析响应、验证签名
     *
     * @param array $params
     *
     * @return AlipayResponse
     */
    public function request($params)
    {
        $raw = $this->requester->execute($params);

        $response = $this->parser->parse($raw);

        $this->signer->verify(
            $response->getSign(),
            $response->stripData(),
            $this->keyPair->getPublicKey()
        );

        return $response;
    }

    /**
     * 执行请求
     *
     * @param AbstractAlipayRequest $request
     *
     * @return AlipayResponse
     */
    public function execute(AbstractAlipayRequest $request)
    {
        $params = $this->build($request);

        $response = $this->request($params);

        return $response;
    }

    /**
     * 生成用于调用收银台 SDK 的字符串
     *
     * @param AbstractAlipayRequest $request
     *
     * @return string
     *
     * @author guofa.tgf
     */
    public function sdkExecute(AbstractAlipayRequest $request)
    {
        $params = $this->build($request);

        return http_build_query($params);
    }

    /**
     * 页面提交请求，生成已签名的跳转 URL
     *
     * @param AbstractAlipayRequest $request
     *
     * @return string
     */
    public function pageExecuteUrl(AbstractAlipayRequest $request)
    {
        $queryParams = $this->build($request);
        $url = $this->requester->getGateway() . '?' . http_build_query($queryParams);

        return $url;
    }

    /**
     * 页面提交请求，生成已签名的表单 HTML
     *
     * @param AbstractAlipayRequest $request
     *
     * @return string
     */
    public function pageExecuteForm(AbstractAlipayRequest $request)
    {
        $fields = $this->build($request);

        $html = "<form id='alipaysubmit' name='alipaysubmit' action='{$this->requester->getUrl()}' method='POST'>";
        foreach ($fields as $key => $value) {
            if (AlipayHelper::isEmpty($value)) {
                continue;
            }
            $value = htmlentities($value, ENT_QUOTES | ENT_HTML5);
            $html .= "<input type='hidden' name='{$key}' value='{$value}'/>";
        }
        $html .= "<input type='submit' value='ok' style='display:none;'></form>";
        $html .= "<script>document.forms['alipaysubmit'].submit();</script>";

        return $html;
    }
}
