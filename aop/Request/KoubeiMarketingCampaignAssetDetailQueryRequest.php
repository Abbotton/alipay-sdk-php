<?php
/**
 * ALIPAY API: koubei.marketing.campaign.asset.detail.query request
 *
 * @author auto create
 * @since  1.0, 2018-01-10 14:14:43
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignAssetDetailQueryRequest extends AbstractAlipayRequest
{

    /**
     * 查询资产的详情信息
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
