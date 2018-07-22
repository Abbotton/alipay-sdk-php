<?php

namespace Alipay;

use Alipay\Exception\AlipayException;
use Alipay\Helper\CryptHelper;
use Alipay\Exception\AlipayResponseException;
use Alipay\Exception\AlipayInvalidKeyException;

class AopClient
{
    const SDK_VERSION = "alipay-sdk-php-20180705";

    const RESPONSE_SUFFIX = "_response";

    const SIGN_NODE_NAME = "sign";

    //应用ID
    protected $appId;

    //签名类型
    protected $signType = "RSA";

    //私钥文件路径
    protected $appPrivateKey;

    //使用文件读取文件格式，请只传递该值
    protected $alipayPublicKey;

    //网关
    protected $gatewayUrl = "https://openapi.alipay.com/gateway.do";

    //返回数据格式
    protected $format = "json";

    //api版本
    protected $apiVersion = "1.0";

    // 表单提交字符集编码
    protected $postCharset = "UTF-8";

    protected function getKey($keyOrFilePath, $isPrivate = true)
    {
        if (file_exists($keyOrFilePath) && is_file($keyOrFilePath)) {
            $key = file_get_contents($keyOrFilePath);
        } else {
            $key = $keyOrFilePath;
        }
        if ($isPrivate) {
            $keyResource = openssl_pkey_get_private($key);
        } else {
            $keyResource = openssl_pkey_get_public($key);
        }
        if ($keyResource === false) {
            throw new AlipayInvalidKeyException('Invalid key: ' . $keyOrFilePath);
        }
        return $keyResource;
    }

    public static function create($appId, $signType, $appPrivateKey, $alipayPublicKey)
    {
        $instance = new self();
        $instance->appId = $appId;
        $instance->signType = $signType;
        $instance->appPrivateKey = $instance->getKey($appPrivateKey, true);
        $instance->alipayPublicKey = $instance->getKey($alipayPublicKey, false);
        return $instance;
    }

    protected function __construct()
    {
    }

    public function __destruct()
    {
        @openssl_free_key($this->appPrivateKey);
        @openssl_free_key($this->alipayPublicKey);
    }

    protected function curl($url, $postFields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $postBodyString = "";
        $encodeArray = array();
        $postMultipart = false;

        if (is_array($postFields) && 0 < count($postFields)) {
            foreach ($postFields as $k => $v) {
                if ("@" != substr($v, 0, 1)) { //判断是不是文件上传
                    $postBodyString .= "$k=" . urlencode($v) . "&";
                } else { //文件上传用multipart/form-data，否则用www-form-urlencoded
                    $postMultipart = true;
                    $encodeArray[$k] = new \CURLFile(substr($v, 1));
                }
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeArray);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }

        if ($postMultipart) {
            $headers = array('content-type: multipart/form-data;charset=' . $this->postCharset . ';boundary=' . $this->getMillisecond());
        } else {
            $headers = array('content-type: application/x-www-form-urlencoded;charset=' . $this->postCharset);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new AlipayException(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new AlipayException($reponse, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $reponse;
    }

    protected function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 生成用于调用收银台SDK的字符串
     *
     * @param  $request SDK接口的请求参数对象
     * @return string
     * @author guofa.tgf
     */
    public function sdkExecute($request)
    {
        $params['app_id'] = $this->appId;
        $params['method'] = $request->getApiMethodName();
        $params['format'] = $this->format;
        $params['sign_type'] = $this->signType;
        $params['timestamp'] = date("Y-m-d H:i:s");
        $params['alipay_sdk'] = static::SDK_VERSION;
        $params['charset'] = $this->postCharset;

        $version = $request->getApiVersion();
        $params['version'] = $this->checkEmpty($version) ? $this->apiVersion : $version;

        if ($notify_url = $request->getNotifyUrl()) {
            $params['notify_url'] = $notify_url;
        }

        $dict = $request->getApiParas();
        $params['biz_content'] = $dict['biz_content'];

        ksort($params);

        $params['sign'] = $this->generateSign($params, $this->signType);

        return http_build_query($params);
    }

    /**
     * 页面提交执行方法
     *
     * @param  $request 跳转类接口的request
     * @param  string $httpmethod 提交方式。两个值可选：post、get
     * @return string 构建好的、签名后的最终跳转URL（GET）或String形式的form（POST）
     * @author 笙默
     */
    public function pageExecute($request, $httpmethod = "POST")
    {
        $iv = null;

        if (!$this->checkEmpty($request->getApiVersion())) {
            $iv = $request->getApiVersion();
        } else {
            $iv = $this->apiVersion;
        }

        //组装系统参数
        $sysParams["app_id"] = $this->appId;
        $sysParams["version"] = $iv;
        $sysParams["format"] = $this->format;
        $sysParams["sign_type"] = $this->signType;
        $sysParams["method"] = $request->getApiMethodName();
        $sysParams["timestamp"] = date("Y-m-d H:i:s");
        $sysParams["alipay_sdk"] = static::SDK_VERSION;
        $sysParams["terminal_type"] = $request->getTerminalType();
        $sysParams["terminal_info"] = $request->getTerminalInfo();
        $sysParams["prod_code"] = $request->getProdCode();
        $sysParams["notify_url"] = $request->getNotifyUrl();
        $sysParams["return_url"] = $request->getReturnUrl();
        $sysParams["charset"] = $this->postCharset;

        //获取业务参数
        $apiParams = $request->getApiParas();

        if (method_exists($request, "getNeedEncrypt") && $request->getNeedEncrypt()) {
            throw new AlipayException('AES Encrypt / Decrypr has been deprecated!');
        }

        $totalParams = array_merge($apiParams, $sysParams);

        ksort($totalParams);

        //签名
        $totalParams["sign"] = $this->generateSign($totalParams, $this->signType);

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
     * @param  $para_temp 请求参数数组
     * @return string 提交表单HTML文本
     */
    protected function buildRequestForm($para_temp)
    {
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='" . $this->gatewayUrl . "?charset=" . trim($this->postCharset) . "' method='POST'>";
        while (list($key, $val) = each($para_temp)) {
            if (false === $this->checkEmpty($val)) {
                //$val = $this->characet($val, $this->postCharset);
                $val = str_replace("'", "&apos;", $val);
                //$val = str_replace("\"","&quot;",$val);
                $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
            }
        }

        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit' value='ok' style='display:none;''></form>";

        $sHtml = $sHtml . "<script>document.forms['alipaysubmit'].submit();</script>";

        return $sHtml;
    }

    public function execute($request, $authToken = null, $appInfoAuthtoken = null)
    {
        $iv = null;

        if (!$this->checkEmpty($request->getApiVersion())) {
            $iv = $request->getApiVersion();
        } else {
            $iv = $this->apiVersion;
        }

        //组装系统参数
        $sysParams["app_id"] = $this->appId;
        $sysParams["version"] = $iv;
        $sysParams["format"] = $this->format;
        $sysParams["sign_type"] = $this->signType;
        $sysParams["method"] = $request->getApiMethodName();
        $sysParams["timestamp"] = date("Y-m-d H:i:s");
        $sysParams["auth_token"] = $authToken;
        $sysParams["alipay_sdk"] = static::SDK_VERSION;
        $sysParams["terminal_type"] = $request->getTerminalType();
        $sysParams["terminal_info"] = $request->getTerminalInfo();
        $sysParams["prod_code"] = $request->getProdCode();
        $sysParams["notify_url"] = $request->getNotifyUrl();
        $sysParams["charset"] = $this->postCharset;
        $sysParams["app_auth_token"] = $appInfoAuthtoken;

        //获取业务参数
        $apiParams = $request->getApiParas();

        if (method_exists($request, "getNeedEncrypt") && $request->getNeedEncrypt()) {
            throw new AlipayException('AES Encrypt / Decrypr has been deprecated!');
        }

        //签名
        $sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams), $this->signType);

        //系统参数放入GET请求串
        $requestUrl = $this->gatewayUrl . '?' . http_build_query($sysParams);

        //发起HTTP请求
        $resp = $this->curl($requestUrl, $apiParams);

        // 将返回结果转换本地文件编码
        // $r = iconv($this->postCharset, $this->fileCharset . "//IGNORE", $resp);

        $signData = null;

        if ("json" == $this->format) {
            $respObject = json_decode($resp);
            if (null !== $respObject) {
                $respWellFormed = true;
                $signData = $this->parserJSONSignData($request, $resp, $respObject);
            }
        } else {
            throw new AlipayException('Unsupported format: ' . $format);
        }

        // 验签
        $this->checkResponseSign($request, $signData, $resp, $respObject);

        // 解密
        if (method_exists($request, "getNeedEncrypt") && $request->getNeedEncrypt()) {
            throw new AlipayException('AES Encrypt / Decrypr has been deprecated!');
        }

        return $respObject;
    }

    /**
     * 校验 $value 是否非空
     *
     * @param  string|null $value
     * @return bool
     */
    protected function checkEmpty($value)
    {
        return $value === null || trim($value) === '';
    }

    public function generateSign($params, $signType = "RSA")
    {
        return $this->sign($this->getSignContent($params), $signType);
    }

    public function getSignContent($params)
    {
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset($k, $v);
        return $stringToBeSigned;
    }

    protected function sign($data, $signType = 'RSA')
    {
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $this->appPrivateKey, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $this->appPrivateKey);
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    public function verify($data, $sign, $signType = 'RSA')
    {
        if ("RSA2" == $signType) {
            return 1 === openssl_verify($data, base64_decode($sign), $this->alipayPublicKey, OPENSSL_ALGO_SHA256);
        } else {
            return 1 === openssl_verify($data, base64_decode($sign), $this->alipayPublicKey);
        }
    }


    public function parserResponseSubCode($request, $responseContent, $respObject, $format)
    {
        if ("json" == $format) {
            $apiName = $request->getApiMethodName();
            $rootNodeName = str_replace(".", "_", $apiName) . static::RESPONSE_SUFFIX;
            $errorNodeName = AlipayResponseException::ERROR_NODE;

            if (isset($respObject->$rootNodeName)) {
                $rInnerObject = $respObject->$rootNodeName;
            } elseif (isset($respObject->$errorNodeName)) {
                $rInnerObject = $respObject->$errorNodeName;
            } else {
                return null;
            }
            
            if (isset($rInnerObject->sub_code)) {
                return $rInnerObject->sub_code;
            } else {
                return null;
            }
        } else {
            throw new AlipayException('Unsupported format: ' . $format);
        }
    }

    public function parserJSONSignData($request, $responseContent, $responseJson)
    {
        $signData = new SignData();

        $signData->sign = $this->parserJSONSign($responseJson);
        $signData->signSourceData = $this->parserJSONSignSource($request, $responseContent);

        return $signData;
    }

    public function parserJSONSignSource($request, $responseContent)
    {
        $apiName = $request->getApiMethodName();
        $rootNodeName = str_replace(".", "_", $apiName) . static::RESPONSE_SUFFIX;

        $rootIndex = strpos($responseContent, $rootNodeName);
        $errorIndex = strpos($responseContent, AlipayResponseException::ERROR_NODE);

        if ($rootIndex > 0) {
            return $this->parserJSONSource($responseContent, $rootNodeName, $rootIndex);
        } elseif ($errorIndex > 0) {
            return $this->parserJSONSource($responseContent, AlipayResponseException::ERROR_NODE, $errorIndex);
        } else {
            return null;
        }
    }

    public function parserJSONSource($responseContent, $nodeName, $nodeIndex)
    {
        $signDataStartIndex = $nodeIndex + strlen($nodeName) + 2;
        $signIndex = strrpos($responseContent, "\"" . static::SIGN_NODE_NAME . "\"");
        // 签名前-逗号
        $signDataEndIndex = $signIndex - 1;
        $indexLen = $signDataEndIndex - $signDataStartIndex;
        if ($indexLen < 0) {
            return null;
        }

        return substr($responseContent, $signDataStartIndex, $indexLen);
    }

    public function parserJSONSign($responseJson)
    {
        if (isset($responseJson->sign)) {
            return $responseJson->sign;
        }
        throw new AlipayResponseException($responseJson, 'Response sign not found');
    }

    /**
     * 验签
     *
     * @param  $request
     * @param  $signData
     * @param  $resp
     * @param  $respObject
     * @throws Exception
     */
    public function checkResponseSign($request, $signData, $resp, $respObject)
    {
        if ($signData == null || $this->checkEmpty($signData->sign) || $this->checkEmpty($signData->signSourceData)) {
            throw new AlipayException(" check sign Fail! The reason : signData is Empty");
        }

        $checkResult = $this->verify($signData->signSourceData, $signData->sign, $this->signType);

        if (!$checkResult) {
            if (strpos($signData->signSourceData, "\\/") > 0) {
                $signData->signSourceData = str_replace("\\/", "/", $signData->signSourceData);

                $checkResult = $this->verify($signData->signSourceData, $signData->sign, $this->signType);

                if (!$checkResult) {
                    throw new AlipayException("check sign Fail! [sign=" . $signData->sign . ", signSourceData=" . $signData->signSourceData . "]");
                }
            } else {
                throw new AlipayException("check sign Fail! [sign=" . $signData->sign . ", signSourceData=" . $signData->signSourceData . "]");
            }
        }
    }
}
