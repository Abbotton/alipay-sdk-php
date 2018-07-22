<?php

namespace Alipay;

use Alipay\Exception\AlipayResponseException;

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

    /**
     * 解析原始响应数据
     *
     * @param  string $raw
     * @param  string $format
     * @return static
     */
    public static function parse($raw, $format = 'JSON')
    {
        if ($format !== 'JSON') {
            throw new AlipayResponseException($raw, "Unsupported response `{$format}` format");
        }
        $instance = new static();
        $instance->raw = $raw;
        if (($instance->data = json_decode($raw, true)) === null) {
            throw new AlipayResponseException(null, json_last_error_msg());
        }
        return $instance;
    }

    protected function __construct()
    {
    }

    /**
     * 获取原始响应的被签名数据，用于验证签名
     *
     * @return string
     * @see AlipaySign::verify()
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
            throw new AlipayResponseException($this->raw, 'Invalid response data');
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
        throw new AlipayResponseException($this->data, 'Response sign not found');
    }

    /**
     * 获取响应内的数据
     *
     * @param boolean $assoc
     * @return mixed
     */
    public function getData($assoc = false)
    {
        return $this->data;
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
}
