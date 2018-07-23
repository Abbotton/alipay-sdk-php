<?php
/**
 * ALIPAY API: alipay.mobile.bksigntoken.verify request
 *
 * @author auto create
 * @since  1.0, 2017-04-07 18:08:15
 */

namespace Alipay\Request;

class AlipayMobileBksigntokenVerifyRequest extends AbstractAlipayRequest
{

    /**
     * 设备标识
     **/
    private $deviceid;
    
    /**
     * 调用来源
     **/
    private $source;
    
    /**
     * 查询token
     **/
    private $token;

    private $apiParas = array();
    
    
    
    
    
    
    

    
    public function setDeviceid($deviceid)
    {
        $this->deviceid = $deviceid;
        $this->apiParas["deviceid"] = $deviceid;
    }

    public function getDeviceid()
    {
        return $this->deviceid;
    }

    public function setSource($source)
    {
        $this->source = $source;
        $this->apiParas["source"] = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setToken($token)
    {
        $this->token = $token;
        $this->apiParas["token"] = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }




}
