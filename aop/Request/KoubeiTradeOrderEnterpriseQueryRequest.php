<?php
/**
 * ALIPAY API: koubei.trade.order.enterprise.query request
 *
 * @author auto create
 *
 * @since 1.0, 2019-03-26 19:55:00
 */

namespace Alipay\Request;

class KoubeiTradeOrderEnterpriseQueryRequest extends AbstractAlipayRequest
{
    /**
     * 口碑对接饿了么企业订单查询接口
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
