<?php

namespace Alipay;

use Alipay\Exception\AlipayBase64Exception;
use Alipay\Exception\AlipayInvalidSignException;
use Alipay\Exception\AlipayOpenSslException;
use Alipay\Key\AlipayKeyPair;
use Alipay\Request\AlipayRequest;
use Alipay\Signer\AlipayRSA2Signer;
use Alipay\Signer\AlipaySigner;

class AopClient
{
    /**
     * SDK 版本.
     */
    public const SDK_VERSION = 'alipay-sdk-PHP-4.11.14.ALL';

    /**
     * API 版本.
     */
    public const API_VERSION = '1.0';

    /**
     * 应用 ID.
     *
     * @var string
     */
    protected $appId;

    /**
     * 签名器.
     *
     * @var AlipaySigner
     */
    protected $signer;

    /**
     * 请求发送器.
     *
     * @var AlipayRequester
     */
    protected $requester;

    /**
     * 响应解析器.
     *
     * @var AlipayResponseFactory
     */
    protected $parser;

    /**
     * 密钥对.
     *
     * @var AlipayKeyPair
     */
    protected $keyPair;

    /**
     * 创建客户端.
     *
     * @param string                $appId     应用 ID，请在开放平台管理页面获取
     * @param AlipayKeyPair         $keyPair   密钥对，用于存储支付宝公钥和应用私钥
     * @param AlipaySigner          $signer    签名器，用于生成和验证签名
     * @param AlipayRequester       $requester 请求发送器，用于发送 HTTP 请求
     * @param AlipayResponseFactory $parser    响应解析器，用于解析服务器原始响应
     */
    public function __construct(
        $appId,
        AlipayKeyPair $keyPair,
        AlipaySigner $signer = null,
        AlipayRequester $requester = null,
        AlipayResponseFactory $parser = null
    ) {
        $this->appId = $appId;
        $this->keyPair = $keyPair;
        $this->signer = $signer === null ? new AlipayRSA2Signer() : $signer;
        $this->requester = $requester === null ? new AlipayCurlRequester() : $requester;
        $this->parser = $parser === null ? new AlipayResponseFactory() : $parser;
    }

    /**
     * 解密被支付宝加密的敏感数据.
     *
     * @param string $encryptedData Base64 格式的已加密的数据，如手机号
     * @param string $encodedKey    Base64 编码后的密钥
     * @param string $cipher        解密算法，保持默认值即可
     *
     * @throws AlipayOpenSslException
     *
     * @return string
     *
     * @see https://docs.alipay.com/mini/introduce/aes
     * @see https://docs.alipay.com/mini/introduce/getphonenumber
     */
    public static function decrypt($encryptedData, $encodedKey, $cipher = 'aes-128-cbc')
    {
        $key = base64_decode($encodedKey);
        if ($key === false) {
            throw new AlipayBase64Exception($encodedKey);
        }

        if (! in_array($cipher, openssl_get_cipher_methods(), true)) {
            throw new AlipayOpenSslException("Cipher algorithm {$cipher} not available");
        }

        $result = openssl_decrypt($encryptedData, $cipher, $key);
        if ($result === false) {
            throw new AlipayOpenSslException();
        }

        return $result;
    }

    /**
     * 一键执行请求.
     *
     * @param  AlipayRequest  $request
     * @return AlipayResponse
     * @throws AlipayBase64Exception
     * @throws AlipayInvalidSignException
     * @throws AlipayOpenSslException
     * @throws Exception\AlipayInvalidResponseException
     */
    public function execute(AlipayRequest $request)
    {
        $params = $this->build($request);

        $response = $this->request($params);

        return $response;
    }

    /**
     * 拼接请求参数并签名.
     *
     * @param  AlipayRequest  $request
     * @return array
     * @throws AlipayBase64Exception
     * @throws AlipayOpenSslException
     */
    public function build(AlipayRequest $request)
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

        $sysParams['terminal_type'] = $request->getTerminalType();
        $sysParams['terminal_info'] = $request->getTerminalInfo();
        $sysParams['prod_code'] = $request->getProdCode();

        $sysParams['auth_token'] = $request->getAuthToken();
        $sysParams['app_auth_token'] = $request->getAppAuthToken();
        $sysParams['biz_content'] = $request->getBizContent();
        $sysParams = array_merge($sysParams, get_object_vars($request));
        // 转换可能是数组的参数
        foreach ($sysParams as $key => &$param) {
            if (is_array($param) || is_object($param)) {
                $param = json_encode($param, JSON_UNESCAPED_UNICODE);
            }
            if (is_null($param)) {
                unset($sysParams[$key]);
            }
        }

        // 签名
        $sysParams['sign'] = $this->signer->generateByParams(
            $sysParams,
            $this->keyPair->getPrivateKey()->asResource()
        );

        return $sysParams;
    }

    /**
     * 发起请求、解析响应、验证签名.
     *
     * @param $params
     * @return AlipayResponse
     * @throws AlipayBase64Exception
     * @throws AlipayInvalidSignException
     * @throws AlipayOpenSslException
     * @throws Exception\AlipayInvalidResponseException
     */
    public function request($params)
    {
        $raw = $this->requester->execute($params);

        $response = $this->parser->parse($raw);

        $this->signer->verify(
            $response->getSign(),
            $response->stripData(),
            $this->keyPair->getPublicKey()->asResource()
        );

        return $response;
    }

    /**
     * 仅拼接请求参数并签名，但不发起请求.
     *
     * @param  AlipayRequest  $request
     * @return string
     * @throws AlipayBase64Exception
     * @throws AlipayOpenSslException
     */
    public function sdkExecute(AlipayRequest $request)
    {
        $params = $this->build($request);

        return http_build_query($params);
    }

    /**
     * 仅拼接请求参数并签名，生成跳转 URL.
     *
     * @param  AlipayRequest  $request
     * @return string
     * @throws AlipayBase64Exception
     * @throws AlipayOpenSslException
     */
    public function pageExecuteUrl(AlipayRequest $request)
    {
        $queryParams = $this->build($request);
        $url = $this->requester->getGateway().'?'.http_build_query($queryParams);

        return $url;
    }

    /**
     * 仅拼接请求参数并签名，生成表单 HTML.
     *
     * @param  AlipayRequest  $request
     * @return string
     * @throws AlipayBase64Exception
     * @throws AlipayOpenSslException
     */
    public function pageExecuteForm(AlipayRequest $request)
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

    /**
     * 验证由支付宝服务器发来的回调通知请求，其签名数据是否未被篡改.
     *
     * @param  null  $params
     * @return bool
     * @throws AlipayBase64Exception
     * @throws AlipayOpenSslException
     */
    public function verify($params = null)
    {
        if ($params === null) {
            $params = $_POST;
        }

        try {
            $this->signer->verifyByParams(
                $params,
                $this->keyPair->getPublicKey()->asResource()
            );
        } catch (AlipayInvalidSignException $ex) {
            return false;
        } catch (\InvalidArgumentException $ex) {
            return false;
        }

        return true;
    }

    /**
     * 获取应用 ID.
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * 获取与本客户端关联的密钥对.
     *
     * @return AlipayKeyPair
     */
    public function getKeyPair()
    {
        return $this->keyPair;
    }

    /**
     * 获取与本客户端关联的响应解析器.
     *
     * @return AlipayResponseFactory
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * 获取与本客户端关联的请求发送器.
     *
     * @return AlipayRequester
     */
    public function getRequester()
    {
        return $this->requester;
    }

    /**
     * 获取与本客户端关联的签名器.
     *
     * @return AlipaySigner
     */
    public function getSigner()
    {
        return $this->signer;
    }
}
