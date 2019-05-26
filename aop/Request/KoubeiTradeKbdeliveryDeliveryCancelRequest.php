<?php
/**
 * ALIPAY API: koubei.trade.kbdelivery.delivery.cancel request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class KoubeiTradeKbdeliveryDeliveryCancelRequest extends AbstractAlipayRequest
{
    /**
     * 口碑物流单取消
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
