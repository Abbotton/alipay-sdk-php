<?php
/**
 * ALIPAY API: koubei.trade.kbdelivery.delivery.apply request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-25 20:35:00
 */

namespace Alipay\Request;

class KoubeiTradeKbdeliveryDeliveryApplyRequest extends AbstractAlipayRequest
{
    /**
     * 口碑物流单创建
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
