<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.station.query request
 *
 * @author auto create
 * @since  1.0, 2016-08-03 16:10:49
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorStationQueryRequest extends AbstractAlipayRequest
{

    /**
     * 地铁购票站点查询
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
