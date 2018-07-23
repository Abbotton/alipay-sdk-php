<?php
/**
 * ALIPAY API: zhima.credit.watchlistii.get request
 *
 * @author auto create
 * @since  1.0, 2018-02-05 20:32:44
 */

namespace Alipay\Request;

class ZhimaCreditWatchlistiiGetRequest extends AbstractAlipayRequest
{

    /**
     * 行业关注名单
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
