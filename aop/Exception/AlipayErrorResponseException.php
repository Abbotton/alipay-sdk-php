<?php

namespace Alipay\Exception;

class AlipayErrorResponseException extends AlipayException
{
    /**
     * @param array $error
     */
    public function __construct($error)
    {
        $message = isset($error['msg']) ? $error['msg'] : '';
        $message .= isset($error['sub_msg']) ? ': ' . $error['sub_msg'] : '';
        $code = isset($error['code']) ? $error['code'] : 0;
        parent::__construct($message, $code);
    }
}
