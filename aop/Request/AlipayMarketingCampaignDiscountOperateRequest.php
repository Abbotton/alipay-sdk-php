<?php
/**
 * ALIPAY API: alipay.marketing.campaign.discount.operate request
 *
 * @author auto create
 * @since  1.0, 2017-03-03 16:48:01
 */

namespace Alipay\Request;

class AlipayMarketingCampaignDiscountOperateRequest extends AbstractAlipayRequest
{

    /**
     * 支付宝营销优惠立减活动操作
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
