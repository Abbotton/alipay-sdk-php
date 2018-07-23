<?php
/**
 * ALIPAY API: zhima.credit.antifraud.risk.list request
 *
 * @author auto create
 * @since  1.0, 2017-10-30 10:56:24
 */

namespace Alipay\Request;

class ZhimaCreditAntifraudRiskListRequest extends AbstractAlipayRequest
{

    /**
     * 欺诈关注清单
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
