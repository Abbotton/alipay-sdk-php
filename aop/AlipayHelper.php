<?php

namespace Alipay;

use Alipay\Exception\AlipayCurlException;

class AlipayHelper
{
    /**
     * 校验 $value 是否非空
     *
     * @param  string|null $value
     * @return bool
     */
    public static function isEmpty($value)
    {
        return $value === null || trim($value) === '';
    }

    /**
     * 转换字符串为驼峰命名（fooBar）
     *
     * @param string $str
     * @param string $delimiters
     * @return string
     */
    public static function camelCase($str, $delimiters = ' ')
    {
        $str = static::studlyCase($str, $delimiters);
        $str = lcfirst($str);
        return $str;
    }

    /**
     * 转换字符串为变种驼峰命名（FooBar）
     *
     * @param string $str
     * @param string $delimiters
     * @return string
     */
    public static function studlyCase($str, $delimiters = ' ')
    {
        $str = ucwords($str, $delimiters);
        $str = str_replace($delimiters, '', $str);
        return $str;
    }

    /**
     * 获取用于发起请求的“时间戳”
     *
     * @return string
     */
    public static function getTimestamp()
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * CURL 提交请求
     *
     * @param  string $url
     * @param  array  $postFields
     * @return mixed
     */
    public static function curl($url, $postFields)
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

    /**
     * 构造用于提交 POST 请求的表单 HTML 代码
     *
     * @param  string $url 请求地址
     * @param  array $fields 表单参数数组
     * @return string
     */
    public static function buildRequestForm($url, $fields)
    {
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='{$url}' method='POST'>";
        foreach ($fields as $key => $val) {
            if (AlipayHelper::isEmpty($val)) {
                continue;
            }
            $val = htmlentities($val, ENT_QUOTES | ENT_HTML5);
            $sHtml .= "<input type='hidden' name='{$key}' value='{$val}'/>";
        }
        $sHtml .= "<input type='submit' value='ok' style='display:none;'></form>";
        $sHtml .= "<script>document.forms['alipaysubmit'].submit();</script>";
        return $sHtml;
    }

    /**
     * 构造用于提交 GET 请求的 URL 地址
     *
     * @param  string $url
     * @param  array $queryParams
     * @return string
     */
    public static function buildRequestUrl($url, $queryParams)
    {
        $queryString = http_build_query($queryParams);
        $fullUrl = $url . '?' . $queryString;
        return $fullUrl;
    }
}
