<?php
/**
 * ALIPAY API: alipay.pcredit.huabei.auth.settle.apply request
 *
 * @author auto create
 *
 * @since 1.0, 2019-03-19 18:57:19
 */

namespace Alipay\Request;

class AlipayPcreditHuabeiAuthSettleApplyRequest extends AbstractAlipayRequest
{
    /**
     * 花呗先享支付接口
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
