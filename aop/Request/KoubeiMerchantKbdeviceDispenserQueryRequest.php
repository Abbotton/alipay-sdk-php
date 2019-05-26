<?php
/**
 * ALIPAY API: koubei.merchant.kbdevice.dispenser.query request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class KoubeiMerchantKbdeviceDispenserQueryRequest extends AbstractAlipayRequest
{
    /**
     * 取餐柜详情查询接口
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
