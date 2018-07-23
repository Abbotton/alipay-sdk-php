<?php

namespace Alipay;

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

    public static function camelCase($str, $delimiters = ' ')
    {
        $str = static::studlyCase($str, $delimiters);
        $str = lcfirst($str);
        return $str;
    }

    public static function studlyCase($str, $delimiters = ' ')
    {
        $str = ucwords($str, $delimiters);
        $str = str_replace($delimiters, '', $str);
        return $str;
    }
}
