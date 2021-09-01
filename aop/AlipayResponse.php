<?php

namespace Alipay;

use Alipay\Exception\AlipayErrorResponseException;
use Alipay\Exception\AlipayInvalidResponseException;

class AlipayResponse
{
    /**
     * 响应签名节点名.
     */
    public const SIGN_NODE = 'sign';

    /**
     * 响应错误节点名.
     */
    public const ERROR_NODE = 'error_response';

    /**
     * 原始响应.
     *
     * @var string
     */
    protected $raw;

    /**
     * 已解析的响应.
     *
     * @var mixed
     */
    protected $parsed;

    public function __construct($raw, $data)
    {
        $this->raw = $raw;
        $this->parsed = $data;
    }

    /**
     * 获取原始响应的被签名数据，用于验证签名.
     *
     * @throws AlipayInvalidResponseException
     *
     * @return false|string
     *
     * @see AlipaySigner::verify()
     */
    public function stripData()
    {
        $nodeName = current(array_keys($this->parsed));
        $nodeIndex = strpos($this->raw, $nodeName);

        $signDataStartIndex = $nodeIndex + strlen($nodeName) + 2;
        $signIndex = strrpos($this->raw, '"'.static::SIGN_NODE.'"');

        $signDataEndIndex = $signIndex - 1;
        $indexLen = $signDataEndIndex - $signDataStartIndex;
        if ($indexLen < 0) {
            throw new AlipayInvalidResponseException($this->raw, 'Response data not found');
        }

        return substr($this->raw, $signDataStartIndex, $indexLen);
    }

    /**
     * 获取响应内的签名.
     *
     * @throws AlipayInvalidResponseException
     *
     * @return mixed
     */
    public function getSign()
    {
        if (isset($this->parsed[static::SIGN_NODE])) {
            return $this->parsed[static::SIGN_NODE];
        }

        throw new AlipayInvalidResponseException($this->raw, 'Response sign not found');
    }

    /**
     * 获取响应内的数据.
     *
     * @param bool $assoc
     *
     * @throws AlipayErrorResponseException
     *
     * @return mixed|object
     */
    public function getData($assoc = true)
    {
        if ($this->isSuccess() === false) {
            throw new AlipayErrorResponseException($this->getError());
        }
        $result = $this->getFirstElement();
        if ($assoc == false) {
            $result = (object) ($result);
        }

        return $result;
    }

    /**
     * 判断响应是否成功.
     *
     * @return bool
     */
    public function isSuccess()
    {
        if (isset($this->parsed[static::ERROR_NODE])) {
            return false;
        }
        $data = $this->getFirstElement();

        return !isset($data['code']) || empty($data['code']) || $data['code'] == '10000';
    }

    /**
     * 获取响应数据内的首元素.
     *
     * @return mixed
     */
    protected function getFirstElement()
    {
        $data = array_reverse($this->parsed);

        return array_pop($data);
    }

    /**
     * 获取响应内的错误.
     *
     * @return mixed|null
     */
    public function getError($assoc = true)
    {
        if ($this->isSuccess()) {
            return null;
        }
        if (isset($this->parsed[static::ERROR_NODE])) {
            $result = $this->parsed[static::ERROR_NODE];
        } else {
            $result = $this->getFirstElement();
        }
        if ($assoc == false) {
            $result = (object) ($result);
        }

        return $result;
    }

    /**
     * 获取原始响应.
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }
}
