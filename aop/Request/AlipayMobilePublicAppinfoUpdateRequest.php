<?php
/**
 * ALIPAY API: alipay.mobile.public.appinfo.update request
 *
 * @author auto create
 * @since  1.0, 2016-01-06 21:23:43
 */

namespace Alipay\Request;

class AlipayMobilePublicAppinfoUpdateRequest extends AbstractAlipayRequest
{

    /**
     * 业务json
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
