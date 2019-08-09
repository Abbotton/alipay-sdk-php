<?php

namespace Alipay;

use Alipay\Exception\AlipayCurlException;
use Alipay\Exception\AlipayHttpException;

class AlipayCurlRequester extends AlipayRequester
{
    /**
     * Curl 选项
     *
     * @param array $options
     */
    public $options = [];


    /**
     * AlipayCurlRequester constructor.
     * @param $isProd
     * @param array $options
     */
    public function __construct($isProd, $options = [])
    {
        $this->options = $options + [
                CURLOPT_FAILONERROR => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded",
                ),
            ];
        if ($isProd) {
            parent::__construct([$this, 'post']);
        } else {
            parent::__construct([$this, 'post'], static::ALIPAY_OPEN_API_GATEWAY_SANDBOX);
        }
    }

    /**
     * 发起 POST 请求
     *
     * @param string $url
     * @param array $params
     *
     * @return mixed
     * @throws AlipayCurlException
     * @throws AlipayHttpException
     */
    public function post($url, $params)
    {
        $ch = curl_init();

        curl_setopt_array($ch, $this->options);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//        foreach ($params as &$value) {
//            if (is_string($value) && strlen($value) > 0 && $value[0] === '@' && class_exists('CURLFile')) {
//                $file = substr($value, 1);
//                if (is_file($file)) {
//                    $value = new CURLFile($file);
//                }
//            }
//        }
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $queryStr = http_build_query($params);

        curl_setopt($ch,
            CURLOPT_POSTFIELDS,
            $queryStr
        );

        $response = curl_exec($ch);

        if ($response === false) {
            curl_close($ch);

            throw new AlipayCurlException(curl_error($ch), curl_errno($ch));
        }

        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (200 !== $httpStatusCode) {
            curl_close($ch);

            throw new AlipayHttpException($response, $httpStatusCode);
        }

        curl_close($ch);

        return $response;
    }
}
