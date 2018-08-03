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
     * @param string $key
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
        if ($this->resource !== null) {
            throw new AlipayInvalidKeyException('Resource of key has already been initialized');
        }
        
        if (is_file($certificate)) {
            $certificate = 'file://' . $certificate;
        }

        $this->resource = static::getKey($certificate);
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
     * 从密钥资源初始化本对象
     *
     * @return static
     */
    public static function fromResource($resource)
    {
        $instance = new static();
        $instance->resource = $resource;
        return $instance;
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

    public static function getKey($certificate)
    {
        throw new AlipayInvalidKeyException(openssl_error_string() . " ($certificate)");
    }

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
