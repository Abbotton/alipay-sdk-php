<?php
/**
 * ALIPAY API: alipay.user.address.query request
 *
 * @author auto create
 * @since  1.0, 2018-05-15 15:52:25
 */

namespace Alipay\Request;

class AlipayUserAddressQueryRequest extends AbstractAlipayRequest
{

    /**
     * 根据addressId获取用户详细地址信息
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
