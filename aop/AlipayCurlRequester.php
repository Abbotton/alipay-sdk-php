<?php

namespace Alipay;

use Alipay\Exception\AlipayCurlException;
use Alipay\Exception\AlipayHttpException;

class AlipayCurlRequester extends AlipayRequester
{
    public function __construct()
    {
        parent::__construct([$this, 'post']);
    }

    public function post($url, $params)
    {
        $ch = curl_init();
        if ($ch === false) {
            throw new AlipayCurlException('CURL initialization error');
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        foreach ($params as &$value) {
            if (is_string($value) && $value[0] === '@' && class_exists('CURLFile')) {
                $file = substr($value, 1);
                if (is_file($file)) {
                    $value = new \CURLFile($file);
                }
            }
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new AlipayCurlException(curl_error($ch), curl_errno($ch));
        }

        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (200 !== $httpStatusCode) {
            throw new AlipayHttpException($response, $httpStatusCode);
        }

        curl_close($ch);

        return $response;
    }
}
