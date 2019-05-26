<?php
/**
 * ALIPAY API: alipay.security.test.query request
 *
 * @author auto create
 *
 * @since 1.0, 2019-02-21 14:25:00
 */

namespace Alipay\Request;

class AlipaySecurityTestQueryRequest extends AbstractAlipayRequest
{
    /**
     * 预发验证openapi内部
     **/
    private $bizContent;

    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParams['biz_content'] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }
}
