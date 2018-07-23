<?php
/**
 * ALIPAY API: koubei.craftsman.data.work.delete request
 *
 * @author auto create
 * @since  1.0, 2017-10-11 20:35:42
 */

namespace Alipay\Request;

class KoubeiCraftsmanDataWorkDeleteRequest extends AbstractAlipayRequest
{

    /**
     * 删除手艺人作品信息接口
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
