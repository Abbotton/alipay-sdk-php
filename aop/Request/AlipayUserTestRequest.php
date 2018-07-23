<?php
/**
 * ALIPAY API: alipay.user.test request
 *
 * @author auto create
 * @since  1.0, 2016-01-14 17:47:44
 */

namespace Alipay\Request;

class AlipayUserTestRequest extends AbstractAlipayRequest
{

    /**
     * 顶顶顶
     **/
    private $userinfo;

    private $apiParas = array();
    
    
    
    
    
    
    

    
    public function setUserinfo($userinfo)
    {
        $this->userinfo = $userinfo;
        $this->apiParas["userinfo"] = $userinfo;
    }

    public function getUserinfo()
    {
        return $this->userinfo;
    }

    

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }




}
