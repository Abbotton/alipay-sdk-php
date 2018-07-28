<?php

namespace Alipay;

use Alipay\Exception\AlipayErrorResponseException;
use Alipay\Exception\AlipayInvalidResponseException;

class AlipayResponse
{
    /**
     * 响应签名节点名
     */
    const SIGN_NODE = 'sign';

    /**
     * 响应错误节点名
     */
    const ERROR_NODE = 'error_response';

    /**
     * 原始响应
     *
     * @var string
     */
    protected $raw;

    /**
     * 已解析的响应
     *
     * @var mixed
     */
    protected $data;

    public function __construct($raw, $data)
    {
        $this->raw = $raw;
        $this->data = $data;
    }

    /**
     * 获取原始响应的被签名数据，用于验证签名
     *
     * @return string
     *
     * @see    AlipaySigner::verify()
     */
    public function stripData()
    {
        $nodeName = current(array_keys($this->data));
        $nodeIndex = strpos($this->raw, $nodeName);

        $signDataStartIndex = $nodeIndex + strlen($nodeName) + 2;
        $signIndex = strrpos($this->raw, '"' . static::SIGN_NODE . '"');

        $signDataEndIndex = $signIndex - 1;
        $indexLen = $signDataEndIndex - $signDataStartIndex;
        if ($indexLen < 0) {
            throw new AlipayInvalidResponseException($this->raw, 'Response data not found');
        }

        return substr($this->raw, $signDataStartIndex, $indexLen);
    }

    /**
     * 获取响应内的签名
     *
     * @return string
     */
    public function getSign()
    {
        if (isset($this->data[static::SIGN_NODE])) {
            return $this->data[static::SIGN_NODE];
        }

        throw new AlipayInvalidResponseException($this->raw, 'Response sign not found');
    }

    /**
     * 获取响应内的数据
     *
     * @param bool $assoc
     *
     * @return mixed
     */
    public function getData($assoc = true)
    {
        if ($this->isSuccess() === false) {
            throw new AlipayErrorResponseException($this->getError());
        }
        $result = reset($this->data);
        if ($assoc == false) {
            $result = (object) ($result);
        }

        return $result;
    }

    /**
     * 获取原始响应
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * 根据是否存在错误字段，判断响应是否成功
     *
     * @return bool
     */
    public function isSuccess()
    {
        return !isset($this->data[static::ERROR_NODE]);
    }

    /**
     * 获取响应内的错误
     *
     * @return mixed|null
     */
    public function getError($assoc = true)
    {
        if ($this->isSuccess()) {
            return null;
        }
        $result = $this->data[static::ERROR_NODE];
        if ($assoc == false) {
            $result = (object) ($result);
        }

        return $result;
    }
}
