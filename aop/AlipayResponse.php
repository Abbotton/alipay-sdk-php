<?php

namespace Alipay;

use Alipay\Exception\AlipayResponseException;

class AlipayResponse
{
    /**
     * 响应签名节点名
     */
    const SIGN_NODE = "sign";

    /**
     * 原始响应数据
     *
     * @var string
     */
    public $raw;

    /**
     * 已解析的响应数据
     *
     * @var mixed
     */
    public $data;

    /**
     * 解析原始响应数据
     *
     * @param  string $raw
     * @return static
     */
    public static function parse($raw, $format = 'JSON')
    {
        if ($this->format !== 'JSON') {
            throw new AlipayResponseException($raw, "Unsupported response `{$format}` format");
        }
        $instance = new static();
        $instance->raw = $raw;
        if (($instance->data = json_decode($raw, true)) === null) {
            throw new AlipayResponseException(null, json_last_error_msg());
        }
        // $instance->data = new \ArrayObject($instance->data, \ArrayObject::ARRAY_AS_PROPS);
        return $instance;
    }

    protected function __construct()
    {
    }

    public function getRawData()
    {
        $nodeName = current(array_keys($this->data));
        $nodeIndex = strpos($this->raw, $nodeName);

        $signDataStartIndex = $nodeIndex + strlen($nodeName) + 2;
        $signIndex = strrpos($this->raw, "\"" . static::SIGN_NODE . "\"");
        
        $signDataEndIndex = $signIndex - 1;
        $indexLen = $signDataEndIndex - $signDataStartIndex;
        if ($indexLen < 0) {
            throw new AlipayResponseException($this->raw, 'Invalid response data');
        }
        return substr($this->raw, $signDataStartIndex, $indexLen);
    }

    public function getSign()
    {
        if (isset($this->data[static::SIGN_NODE])) {
            return $this->data[static::SIGN_NODE];
        }
        throw new AlipayResponseException($this->data, 'Response sign not found');
    }

    public function getData($assoc = false)
    {
        return $this->data;
    }
}
