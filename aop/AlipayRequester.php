<?php

namespace Alipay;

use Alipay\Exception\AlipayCurlException;

class AlipayRequester
{
    protected $gateway;

    protected $charset;

    public function __construct($gateway = 'https://openapi.alipay.com/gateway.do', $charset = 'UTF-8')
    {
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
     * @param  array  $params
     * @return mixed
     */
    public function execute($params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        foreach ($params as $key => &$value) {
            if (is_string($value) && $value[0] === '@' && class_exists('CURLFile')) {
                $file = substr($value, 1);
                if (is_file($file)) {
                    $value = new \CURLFile($file);
                }
            }
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new AlipayCurlException(curl_error($ch), curl_errno($ch));
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new AlipayHttpException($response, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $response;
    }
}
