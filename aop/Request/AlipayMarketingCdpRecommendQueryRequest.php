<?php
/**
 * ALIPAY API: alipay.marketing.cdp.recommend.query request
 *
 * @author auto create
 * @since  1.0, 2017-08-18 15:36:04
 */

namespace Alipay\Request;

class AlipayMarketingCdpRecommendQueryRequest extends AbstractAlipayRequest
{

    /**
     * 外部应用根据用户当前地理位置查询附近商家信息
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
