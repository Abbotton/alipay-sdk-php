<?php
/**
 * ALIPAY API: koubei.trade.ticket.ticketcode.query request
 *
 * @author auto create
 * @since  1.0, 2018-06-15 14:45:00
 */

namespace Alipay\Request;

class KoubeiTradeTicketTicketcodeQueryRequest extends AbstractAlipayRequest
{

    /**
     * 口碑凭证码查询
     **/
    private $bizContent;

    private $apiParas = array();
    
    
    
    
    
    
    

    
    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParas["biz_content"] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }

    

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }




}
