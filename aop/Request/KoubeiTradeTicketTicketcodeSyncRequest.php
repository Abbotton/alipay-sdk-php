<?php
/**
 * ALIPAY API: koubei.trade.ticket.ticketcode.sync request
 *
 * @author auto create
 *
 * @since 1.0, 2018-08-29 20:55:00
 */

namespace Alipay\Request;

class KoubeiTradeTicketTicketcodeSyncRequest extends AbstractAlipayRequest
{
    /**
     * 口碑凭证码同步
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
