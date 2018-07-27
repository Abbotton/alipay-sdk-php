<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.voucher.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-21 15:05:13
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorVoucherUploadRequest extends AbstractAlipayRequest
{
    /**
     * 钱包中地铁票购票，获得核销码，线下地铁自助购票机上凭核销码取票，票号上传接口
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
