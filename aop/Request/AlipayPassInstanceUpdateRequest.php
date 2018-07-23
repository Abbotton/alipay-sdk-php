<?php
/**
 * ALIPAY API: alipay.pass.instance.update request
 *
 * @author auto create
 * @since  1.0, 2018-03-14 18:20:33
 */

namespace Alipay\Request;

class AlipayPassInstanceUpdateRequest extends AbstractAlipayRequest
{

    /**
     * 支付宝pass更新卡券实例接口
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
