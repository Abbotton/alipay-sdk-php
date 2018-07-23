<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.script.query request
 *
 * @author auto create
 * @since  1.0, 2015-12-09 16:24:55
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorScriptQueryRequest extends AbstractAlipayRequest
{

    /**
     * 查询城市一卡通的判卡、读卡脚本
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
