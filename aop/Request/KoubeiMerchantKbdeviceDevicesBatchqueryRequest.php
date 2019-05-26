<?php
/**
 * ALIPAY API: koubei.merchant.kbdevice.devices.batchquery request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class KoubeiMerchantKbdeviceDevicesBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询门店下设备列表
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
