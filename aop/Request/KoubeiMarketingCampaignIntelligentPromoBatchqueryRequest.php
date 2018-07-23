<?php
/**
 * ALIPAY API: koubei.marketing.campaign.intelligent.promo.batchquery request
 *
 * @author auto create
 * @since  1.0, 2017-11-17 06:02:33
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignIntelligentPromoBatchqueryRequest extends AbstractAlipayRequest
{

    /**
     * 智能营销方案批量查询
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
