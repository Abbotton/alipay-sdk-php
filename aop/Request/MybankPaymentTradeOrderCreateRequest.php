<?php
/**
 * ALIPAY API: mybank.payment.trade.order.create request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-10 19:35:00
 */

namespace Alipay\Request;

class MybankPaymentTradeOrderCreateRequest extends AbstractAlipayRequest
{
    /**
     * 网商银行全渠道收单业务订单创建
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
