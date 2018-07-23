<?php
/**
 * ALIPAY API: alipay.marketing.campaign.cash.trigger request
 *
 * @author auto create
 * @since  1.0, 2018-05-21 17:25:00
 */

namespace Alipay\Request;

class AlipayMarketingCampaignCashTriggerRequest extends AbstractAlipayRequest
{

    /**
     * 触发现金红包活动
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
