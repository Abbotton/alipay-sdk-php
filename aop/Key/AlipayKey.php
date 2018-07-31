<?php

namespace Alipay\Key;

use Alipay\Exception\AlipayInvalidKeyException;
use Alipay\Exception\AlipayOpenSslException;

abstract class AlipayKey implements \Serializable
{
    protected $resource;

    /**
     * 创建密钥
     *
     * @param mixed $key
     *
     * @return static
     */
    public static function create($key)
    {
        $instance = new static();
        $instance->load($key);

        return $instance;
    }

    protected function __construct()
    {
    }

    public function __destruct()
    {
        if (is_resource($this->resource)) {
            @openssl_free_key($this->resource);
        }
    }

    public function __clone()
    {
        // 深拷贝需要重新加载密钥，以防密钥被释放
        $this->resource = $this->load($this->toString());
    }

    /**
     * 使用密钥字符串或路径加载密钥
     *
     * @param string $keyOrFilePath
     *
     * @throws AlipayInvalidKeyException
     *
     * @return void
     */
    protected function load($certificate)
    {
        if (is_file($certificate)) {
            $certificate = 'file://' . $certificate;
        }

        $resource = static::getKey($certificate);

        if ($resource === false) {
            throw new AlipayInvalidKeyException(openssl_error_string() . " ($certificate)");
        }

        $this->resource = $resource;
    }

    /**
     * 获取真实的密钥资源
     *
     * @return resource
     */
    public function asResource()
    {
        return $this->resource;
    }

    /**
     * 转换密钥为字符串
     *
     * @param resource $resource
     *
     * @return string
     */
    public static function toString($resource)
    {
        throw new AlipayOpenSslException(openssl_error_string());
    }

    abstract public static function getKey($certificate);

    public function serialize()
    {
        return (string) $this;
    }

    public function unserialize($data)
    {
        $this->load($data);
    }

    public function __toString()
    {
        return static::toString($this->resource);
    }
}
