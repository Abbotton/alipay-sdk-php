<?php

namespace Alipay;

use Alipay\Exception\AlipayInvalidKeyException;
use Alipay\Exception\AlipayOpenSslException;

class AlipayKeyPair
{
    /**
     * 支付宝公钥
     * 支持文件路径或公钥字符串，用于验证签名
     *
     * @var resource
     */
    protected $publicKey;

    /**
     * 商户私钥（又称：小程序私钥，App私钥等）
     * 支持文件路径或私钥字符串，用于生成签名
     *
     * @var resource
     */
    protected $privateKey;

    public static function create($privateKey, $publicKey)
    {
        $instance = new static();
        $instance->privateKey = static::loadKey($privateKey, true);
        $instance->publicKey = static::loadKey($publicKey, false);
        return $instance;
    }

    protected function __construct()
    {
    }

    public function __destruct()
    {
        static::freeKey($this->privateKey);
        static::freeKey($this->publicKey);
    }

    public function __clone()
    {
        // 深拷贝需要重新加载密钥，以防密钥被释放
        $this->privateKey = static::loadKey($this->getPrivateKey(false), true);
        $this->publicKey = static::loadKey($this->getPublicKey(false), false);
    }

    /**
     * 生成密钥对
     *
     * @param array $configargs
     * @return static
     */
    public static function generate($configargs = [])
    {
        $configargs = array_merge([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ], $configargs);

        $resource = openssl_pkey_new($configargs);
        $privateKey = static::getKeyString($resource, true);
        $publicKey = static::getKeyString($resource, false);

        return static::create($privateKey, $publicKey);
    }

    public function getPrivateKey($resource = true)
    {
        if ($resource) {
            return $this->privateKey;
        } else {
            return static::getKeyString($this->privateKey, true);
        }
    }

    public function getPublicKey($resource = true)
    {
        if ($resource) {
            return $this->publicKey;
        } else {
            return static::getKeyString($this->publicKey, false);
        }
    }

    /**
     * 使用密钥字符串或路径加载密钥
     *
     * @param string $keyOrFilePath
     * @param bool   $isPrivate
     *
     * @throws AlipayInvalidKeyException
     *
     * @return resource
     */
    protected static function loadKey($keyOrFilePath, $isPrivate = true)
    {
        if (is_file($keyOrFilePath)) {
            $keyOrFilePath = 'file://' . $keyOrFilePath;
        }

        if ($isPrivate) {
            $keyResource = openssl_pkey_get_private($keyOrFilePath);
        } else {
            $keyResource = openssl_pkey_get_public($keyOrFilePath);
        }

        if ($keyResource === false) {
            throw new AlipayInvalidKeyException(openssl_error_string() . " ($keyOrFilePath)");
        }

        return $keyResource;
    }

    /**
     * 释放密钥资源
     *
     * @param resource $resource
     * @return void
     */
    protected static function freeKey($resource)
    {
        if (is_resource($resource)) {
            @openssl_free_key($resource);
        }
    }

    protected static function getKeyString($resource, $isPrivate = true)
    {
        if ($isPrivate) {
            if (openssl_pkey_export($resource, $key) !== false) {
                return $key;
            }
        } else {
            $detail = openssl_pkey_get_details($resource);
            if ($detail !== false) {
                return $detail['key'];
            }
        }
        throw new AlipayOpenSslException(openssl_error_string());
    }
}
