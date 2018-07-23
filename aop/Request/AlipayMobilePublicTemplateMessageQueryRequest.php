<?php
/**
 * ALIPAY API: alipay.mobile.public.template.message.query request
 *
 * @author auto create
 * @since  1.0, 2017-08-02 17:35:28
 */

namespace Alipay\Request;

class AlipayMobilePublicTemplateMessageQueryRequest extends AbstractAlipayRequest
{

    /**
     * 模板
     **/
    private $template;
    
    /**
     * 模板id
     **/
    private $templateId;

    private $apiParas = array();
    
    
    
    
    
    
    

    
    public function setTemplate($template)
    {
        $this->template = $template;
        $this->apiParas["template"] = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
        $this->apiParas["template_id"] = $templateId;
    }

    public function getTemplateId()
    {
        return $this->templateId;
    }

    

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }




}
