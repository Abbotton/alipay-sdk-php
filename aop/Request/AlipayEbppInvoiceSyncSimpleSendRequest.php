<?php
/**
 * ALIPAY API: alipay.ebpp.invoice.sync.simple.send request
 *
 * @author auto create
 *
 * @since 1.0, 2019-02-15 10:55:00
 */

namespace Alipay\Request;

class AlipayEbppInvoiceSyncSimpleSendRequest extends AbstractAlipayRequest
{
    /**
     * 简单模式发票回传接口
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
