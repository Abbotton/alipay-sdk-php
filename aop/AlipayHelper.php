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
}
