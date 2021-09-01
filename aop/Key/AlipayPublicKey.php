<?php

namespace Alipay\Key;

class AlipayPublicKey extends AlipayKey
{
    public static function toString($resource)
    {
        $detail = openssl_pkey_get_details($resource);

        return $detail !== false && isset($detail['key']) ? $detail['key'] : parent::toString($resource);
    }

    public static function getKey($certificate)
    {
        $certString = is_file($certificate) ? file_get_contents($certificate) : $certificate;

        return openssl_pkey_get_public($certString) ?: parent::getKey($certificate);
    }
}
