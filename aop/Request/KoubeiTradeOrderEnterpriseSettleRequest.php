<?php
/**
 * ALIPAY API: koubei.trade.order.enterprise.settle request
 *
 * @author auto create
 *
 * @since 1.0, 2019-03-27 17:14:30
 */

namespace Alipay\Request;

class KoubeiTradeOrderEnterpriseSettleRequest extends AbstractAlipayRequest
{
    /**
     * 口碑对接外部订单结算接口
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
