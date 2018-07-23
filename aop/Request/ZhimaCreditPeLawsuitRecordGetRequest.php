<?php
/**
 * ALIPAY API: zhima.credit.pe.lawsuit.record.get request
 *
 * @author auto create
 * @since  1.0, 2017-11-02 14:04:58
 */

namespace Alipay\Request;

class ZhimaCreditPeLawsuitRecordGetRequest extends AbstractAlipayRequest
{

    /**
     * 个人涉诉记录查询
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
