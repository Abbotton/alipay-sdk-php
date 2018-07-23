<?php
/**
 * ALIPAY API: ant.merchant.expand.image.upload request
 *
 * @author auto create
 * @since  1.0, 2018-05-23 11:30:21
 */

namespace Alipay\Request;

class AntMerchantExpandImageUploadRequest extends AbstractAlipayRequest
{

    /**
     * 图片二进制字节流
     **/
    private $imageContent;
    
    /**
     * 图片格式
     **/
    private $imageType;

    private $apiParas = array();
    private $terminalType;
    private $terminalInfo;
    private $prodCode;
    private $apiVersion = "1.0";
    private $notifyUrl;
    private $returnUrl;
    

    
    public function setImageContent($imageContent)
    {
        $this->imageContent = $imageContent;
        $this->apiParas["image_content"] = $imageContent;
    }

    public function getImageContent()
    {
        return $this->imageContent;
    }

    public function setImageType($imageType)
    {
        $this->imageType = $imageType;
        $this->apiParas["image_type"] = $imageType;
    }

    public function getImageType()
    {
        return $this->imageType;
    }

    

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    public function getApiParams()
    {
        return $this->apiParas;
    }

    public function getTerminalType()
    {
        return $this->terminalType;
    }

    public function setTerminalType($terminalType)
    {
        $this->terminalType = $terminalType;
    }

    public function getTerminalInfo()
    {
        return $this->terminalInfo;
    }

    public function setTerminalInfo($terminalInfo)
    {
        $this->terminalInfo = $terminalInfo;
    }

    public function getProdCode()
    {
        return $this->prodCode;
    }

    public function setProdCode($prodCode)
    {
        $this->prodCode = $prodCode;
    }

    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    public function getApiVersion()
    {
        return $this->apiVersion;
    }
}
