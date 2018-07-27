<?php

namespace Alipay;

class AlipayHelper
{
    /**
     * 校验 $value 是否非空
     *
     * @param string|null $value
     *
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
     *
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
     *
     * @return string
     */
    public static function studlyCase($str, $delimiters = ' ')
    {
        $str = ucwords($str, $delimiters);
        $str = str_replace($delimiters, '', $str);

        return $str;
    }
}
