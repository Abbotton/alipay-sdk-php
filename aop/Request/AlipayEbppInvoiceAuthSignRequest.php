<?php
/**
 * ALIPAY API: alipay.ebpp.invoice.auth.sign request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class AlipayEbppInvoiceAuthSignRequest extends AbstractAlipayRequest
{
    /**
     * 发票授权关系同步
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
