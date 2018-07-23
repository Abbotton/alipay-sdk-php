<?php
/**
 * ALIPAY API: alipay.marketing.campaign.cash.list.query request
 *
 * @author auto create
 * @since  1.0, 2017-07-14 11:53:33
 */

namespace Alipay\Request;

class AlipayMarketingCampaignCashListQueryRequest extends AbstractAlipayRequest
{

    /**
     * 现金活动列表查询
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
