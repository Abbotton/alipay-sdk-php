<?php

namespace Alipay;

use Alipay\Exception\AlipayInvalidResponseException;

class AlipayResponseFactory
{
    protected $format;

    public function __construct($format = 'JSON')
    {
        $this->format = $format;
    }

    public function getFormat()
    {
        return $this->format;
    }

    /**
     * 解析原始响应数据
     *
     * @param string $raw
     *
     * @return AlipayResponse
     */
    public function parse($raw)
    {
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            $error = function_exists('json_last_error_msg') ? json_last_error_msg() : json_last_error();

            throw new AlipayInvalidResponseException($raw, $error);
        }

        return new AlipayResponse($raw, $data);
    }
}
