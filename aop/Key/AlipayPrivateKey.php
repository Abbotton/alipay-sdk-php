<?php

namespace Alipay\Key;

class AlipayPrivateKey extends AlipayKey
{
    public static function toString($resource, $configargs = [])
    {
        return openssl_pkey_export($resource, $key, null, $configargs) ? $key : parent::toString($resource);
    }

    public static function getKey($certificate)
    {
        $certString = is_file($certificate) ? file_get_contents($certificate) : $certificate;
        return openssl_pkey_get_private($certString) ?: parent::getKey($certificate);
    }
}
