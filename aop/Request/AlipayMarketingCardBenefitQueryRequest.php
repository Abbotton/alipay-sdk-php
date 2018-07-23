<?php
/**
 * ALIPAY API: alipay.marketing.card.benefit.query request
 *
 * @author auto create
 * @since  1.0, 2018-01-10 18:26:27
 */

namespace Alipay\Request;

class AlipayMarketingCardBenefitQueryRequest extends AbstractAlipayRequest
{

    /**
     * 会员卡模板外部权益查询
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
