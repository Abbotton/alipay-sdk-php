<?php
/**
 * ALIPAY API: alipay.eco.cplife.community.modify request
 *
 * @author auto create
 * @since  1.0, 2018-06-09 20:16:03
 */

namespace Alipay\Request;

class AlipayEcoCplifeCommunityModifyRequest extends AbstractAlipayRequest
{

    /**
     * 变更物业小区信息
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
