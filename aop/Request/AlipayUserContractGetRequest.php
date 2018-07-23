<?php
/**
 * ALIPAY API: alipay.user.contract.get request
 *
 * @author auto create
 * @since  1.0, 2016-06-06 20:23:18
 */

namespace Alipay\Request;

class AlipayUserContractGetRequest extends AbstractAlipayRequest
{

    /**
     * 订购者支付宝ID。session与subscriber_user_id二选一即可。
     **/
    private $subscriberUserId;

    private $apiParas = array();
    
    
    
    
    
    
    

    
    public function setSubscriberUserId($subscriberUserId)
    {
        $this->subscriberUserId = $subscriberUserId;
        $this->apiParas["subscriber_user_id"] = $subscriberUserId;
    }

    public function getSubscriberUserId()
    {
        return $this->subscriberUserId;
    }

    

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }




}
