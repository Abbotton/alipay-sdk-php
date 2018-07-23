<?php
/**
 * ALIPAY API: alipay.trust.user.auth.send request
 *
 * @author auto create
 * @since  1.0, 2015-05-15 09:36:22
 */

namespace Alipay\Request;

class AlipayTrustUserAuthSendRequest extends AbstractAlipayRequest
{

    /**
     * 申请授权的用户信息
     **/
    private $aliTrustUserInfo;

    private $apiParas = array();
    
    
    
    
    
    
    

    
    public function setAliTrustUserInfo($aliTrustUserInfo)
    {
        $this->aliTrustUserInfo = $aliTrustUserInfo;
        $this->apiParas["ali_trust_user_info"] = $aliTrustUserInfo;
    }

    public function getAliTrustUserInfo()
    {
        return $this->aliTrustUserInfo;
    }

    

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }




}
