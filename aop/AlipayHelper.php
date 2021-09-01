<?php

namespace Alipay;

class AlipayHelper
{
    /**
     * 校验某字符串或可被转换为字符串的数据，是否为 NULL 或均为空白字符.
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
     * 转换字符串为变种驼峰命名（例如：FooBar）.
     *
     * @param $str
     * @param string $delimiters
     *
     * @return array|string|string[]
     */
    public static function studlyCase($str, $delimiters = ' ')
    {
        return version_compare(PHP_VERSION, '5.6', '<')
            ? str_replace(' ', '', ucwords(str_replace($delimiters, ' ', $str)))
            : str_replace($delimiters, '', ucwords($str, $delimiters));
    }
}
