<?php
/**
 * ALIPAY API: alipay.trade.orderinfo.sync request
 *
 * @author auto create
 *
 * @since 1.0, 2018-07-23 11:40:00
 */

namespace Alipay\Request;

class AlipayTradeOrderinfoSyncRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝订单信息同步接口
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
