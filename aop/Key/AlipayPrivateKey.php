<?php

namespace Alipay\Key;

class AlipayPrivateKey extends AlipayKey
{
    public static function toString($resource)
    {
        return openssl_pkey_export($resource, $key) ? $key : parent::toString();
    }

    public static function getKey($certificate)
    {
        return openssl_pkey_get_private($certificate);
    }
}
