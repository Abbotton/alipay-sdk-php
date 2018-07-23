<?php
/**
 * ALIPAY API: koubei.quality.test.cloudacpt.batch.query request
 *
 * @author auto create
 * @since  1.0, 2016-06-15 15:06:46
 */

namespace Alipay\Request;

class KoubeiQualityTestCloudacptBatchQueryRequest extends AbstractAlipayRequest
{

    /**
     * 云验收单品列表查询
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
